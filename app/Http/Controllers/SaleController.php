<?php

namespace App\Http\Controllers;

use App\Actions\Store\CreateSale;
use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Sale;
use App\Models\StoreItem;
use App\Queries\SearchStoreItems;
use App\Support\Code128;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function create(): View
    {
        return view('sales.create');
    }

    public function searchItems(Request $request, SearchStoreItems $search): JsonResponse
    {
        $items = $search($request->string('q'));

        return response()->json($items->map(fn (StoreItem $item) => [
            'id' => $item->id,
            'contract_id' => $item->contract_id,
            'description' => $item->description,
            'cost' => $item->cost,
            'price' => $item->price,
            'stock' => $item->stock,
        ]));
    }

    public function store(Request $request, CreateSale $createSale): RedirectResponse
    {
        $validated = $request->validate(
            [
                'client_id' => ['required', 'exists:clients,id'],
                'warranty_days' => ['required', 'integer', 'min:0', 'max:365'],
                'items' => ['required', 'array', 'min:1'],
                'items.*.store_item_id' => ['required', 'integer'],
                'items.*.price' => ['required', 'integer', 'min:0'],
            ],
            [],
            [
                'client_id' => 'cliente',
                'warranty_days' => 'días de garantía',
                'items' => 'productos',
            ],
        );

        $sale = $createSale(
            Client::query()->findOrFail($validated['client_id']),
            $validated['items'],
            $validated['warranty_days'],
            $request->user(),
        );

        return redirect()
            ->route('sales.print', $sale)
            ->with('status', "Venta registrada: Nota de Cobro No. {$sale->id}.");
    }

    public function show(Sale $sale): View
    {
        $sale->load(['client', 'items.storeItem', 'user']);

        return view('sales.show', ['sale' => $sale]);
    }

    public function print(Sale $sale): View
    {
        $sale->load(['client', 'items.storeItem']);

        return view('sales.print', [
            'sale' => $sale,
            'company' => CompanySetting::current(),
            'barcode' => Code128::encode('NC'.$sale->id),
        ]);
    }
}
