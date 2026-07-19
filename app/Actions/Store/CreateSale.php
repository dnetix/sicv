<?php

namespace App\Actions\Store;

use App\Actions\RecordAmountOverride;
use App\Enums\ContractStatus;
use App\Models\Client;
use App\Models\Sale;
use App\Models\StoreItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateSale
{
    public function __construct(private readonly RecordAmountOverride $recordOverride) {}

    /**
     * POS checkout: one invoice (nota de cobro) with one unit per line.
     * Line prices default to the asking price but are operator-editable
     * (audited). Sourced-from-contract items flip their contract to Sold.
     *
     * @param  array<int, array{store_item_id: int, price: int}>  $lines
     */
    public function __invoke(Client $client, array $lines, int $warrantyDays, User $user): Sale
    {
        if ($lines === []) {
            throw ValidationException::withMessages([
                'items' => 'Es necesario ingresar los productos a vender.',
            ]);
        }

        $itemIds = array_column($lines, 'store_item_id');

        if (count($itemIds) !== count(array_unique($itemIds))) {
            throw ValidationException::withMessages([
                'items' => 'Hay artículos repetidos en la venta.',
            ]);
        }

        return DB::transaction(function () use ($client, $lines, $warrantyDays, $user) {
            $items = StoreItem::query()
                ->whereIn('id', array_column($lines, 'store_item_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($lines as $line) {
                $item = $items->get($line['store_item_id']);

                if ($item === null || $item->stock < 1) {
                    throw ValidationException::withMessages([
                        'items' => "El artículo {$line['store_item_id']} no está disponible.",
                    ]);
                }
            }

            $sale = Sale::query()->create([
                'client_id' => $client->id,
                'sold_at' => today(),
                'total' => array_sum(array_column($lines, 'price')),
                'warranty_days' => $warrantyDays,
                'user_id' => $user->id,
            ]);

            foreach ($lines as $line) {
                $item = $items->get($line['store_item_id']);

                $saleItem = $sale->items()->create([
                    'store_item_id' => $item->id,
                    'price' => $line['price'],
                    'quantity' => 1,
                ]);

                ($this->recordOverride)('sale_line', $saleItem, $item->price, $line['price'], $user);

                $item->decrement('stock');

                if ($item->contract_id !== null) {
                    $contract = $item->contract;
                    $contract->update([
                        'status' => ContractStatus::Sold,
                        // Legacy setVendido: only stamp the exit if it was
                        // never stamped (kept when sold from the store).
                        'ended_at' => $contract->ended_at ?? now(),
                        'settled_amount' => $contract->settled_amount ?? 0,
                    ]);
                }
            }

            return $sale;
        });
    }
}
