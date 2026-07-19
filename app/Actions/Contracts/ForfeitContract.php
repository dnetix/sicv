<?php

namespace App\Actions\Contracts;

use App\Actions\RecordAmountOverride;
use App\Enums\ContractStatus;
use App\Models\Contract;
use App\Models\StoreItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ForfeitContract
{
    public function __construct(private readonly RecordAmountOverride $recordOverride) {}

    /**
     * Move the pawned item into the store inventory for sale. The operator
     * may change the suggested asking price (audited against whatever this
     * flow suggested: the payoff on the contract page, the loan amount on
     * the bulk pull screen). Cost is the loan amount.
     */
    public function __invoke(Contract $contract, int $askingPrice, User $user, ?float $suggestedPrice = null): StoreItem
    {
        if (! $contract->isActive()) {
            throw ValidationException::withMessages([
                'price' => 'Solo se puede mover al almacén un contrato activo.',
            ]);
        }

        $suggestedPrice ??= $contract->payoffAmount();

        return DB::transaction(function () use ($contract, $askingPrice, $user, $suggestedPrice) {
            ($this->recordOverride)('forfeit', $contract, $suggestedPrice, $askingPrice, $user);

            $item = StoreItem::query()->create([
                'contract_id' => $contract->id,
                'description' => $contract->description,
                'item_type_id' => $contract->item_type_id,
                'entered_at' => today(),
                'cost' => $contract->amount,
                'price' => $askingPrice,
                'stock' => 1,
            ]);

            $contract->update([
                'status' => ContractStatus::InStore,
                'ended_at' => now(),
                'settled_amount' => 0,
            ]);

            $contract->repossession()->delete();

            return $item;
        });
    }
}
