<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AmountOverride;
use App\Support\DateRange;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmountOverrideController extends Controller
{
    /**
     * Review screen for operator-modified amounts: what the system computed
     * vs what was actually charged, who and when.
     */
    public function index(Request $request): View
    {
        $range = DateRange::fromRequest($request);

        $overrides = AmountOverride::query()
            ->with(['user', 'auditable'])
            ->whereBetween('created_at', $range->bounds())
            ->when($request->filled('operation'), fn ($query) => $query->where('operation', $request->string('operation')))
            ->orderByDesc('created_at')
            ->paginate(50)
            ->withQueryString();

        return view('admin.overrides', [
            'overrides' => $overrides,
            'range' => $range,
            'operations' => [
                'redeem' => 'Cancelación de contrato',
                'forfeit' => 'Paso al almacén',
                'sale_line' => 'Precio de venta',
            ],
        ]);
    }
}
