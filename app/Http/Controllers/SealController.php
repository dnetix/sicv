<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Support\Code128;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class SealController extends Controller
{
    /**
     * Printable barcode seals (the physical tags attached to pawned items),
     * for all contracts created in a date range. Defaults to today.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $from = ($validated['from'] ?? null) ? Carbon::parse($validated['from']) : today();
        $to = ($validated['to'] ?? null) ? Carbon::parse($validated['to']) : today();

        $contracts = Contract::query()
            ->whereBetween('started_at', [$from->startOfDay(), $to->copy()->endOfDay()])
            ->orderBy('id')
            ->get();

        return view('contracts.seals', [
            'contracts' => $contracts,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'barcodes' => $contracts->mapWithKeys(
                fn (Contract $contract) => [$contract->id => Code128::encode((string) $contract->id)]
            ),
        ]);
    }
}
