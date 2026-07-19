<?php

namespace App\Http\Controllers;

use App\Models\ItemType;
use App\Models\StoreItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreItemController extends Controller
{
    /**
     * Items currently for sale.
     */
    public function index(): View
    {
        $items = StoreItem::query()
            ->available()
            ->with('itemType')
            ->orderBy('id')
            ->get();

        return view('store.index', [
            'items' => $items,
            'totals' => [
                'cost' => $items->sum('cost'),
                'price' => $items->sum('price'),
                'stock' => $items->sum('stock'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('store.create', [
            'itemTypes' => ItemType::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'description' => ['required', 'string'],
                'item_type_id' => ['required', 'exists:item_types,id'],
                // The legacy form silently defaulted an empty cost to 10;
                // both prices are now explicit.
                'cost' => ['required', 'integer', 'min:0'],
                'price' => ['required', 'integer', 'min:0'],
                'stock' => ['required', 'integer', 'min:1'],
            ],
            [],
            [
                'description' => 'artículo',
                'item_type_id' => 'tipo de artículo',
                'cost' => 'valor de compra',
                'price' => 'valor de venta',
                'stock' => 'cantidad',
            ],
        );

        $item = StoreItem::query()->create([
            ...$validated,
            'entered_at' => today(),
        ]);

        return redirect()
            ->route('store.create')
            ->with('status', "Artículo No. {$item->id} guardado exitosamente.");
    }
}
