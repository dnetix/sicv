<?php

namespace App\Http\Controllers;

use App\Actions\Contracts\PullQueuedContracts;
use App\Actions\Contracts\QueueContracts;
use App\Models\Contract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BulkOperationController extends Controller
{
    /**
     * Queue selected expired contracts for repossession ("pre-sacar").
     */
    public function queue(Request $request, QueueContracts $queueContracts): RedirectResponse
    {
        $validated = $request->validate(
            [
                'contracts' => ['required', 'array', 'min:1'],
                'contracts.*' => ['integer'],
            ],
            ['contracts.required' => 'No hay contratos que pre-sacar.'],
        );

        $queued = $queueContracts($validated['contracts'], $request->user());

        return redirect()
            ->route('reports.queued')
            ->with('status', "$queued contrato(s) marcados en pre-saca.");
    }

    /**
     * The bulk pull ("sacar"): forfeit each selected queued contract at the
     * given price, or scrap gold when that option was chosen.
     */
    public function pull(Request $request, PullQueuedContracts $pullContracts): RedirectResponse
    {
        $validated = $request->validate(
            [
                'contracts' => ['required', 'array', 'min:1'],
                'contracts.*' => ['integer'],
                'prices' => ['required', 'array'],
                'prices.*' => ['required', 'integer', 'min:0'],
                'scrap_gold' => ['required', 'boolean'],
            ],
            ['contracts.required' => 'No hay contratos seleccionados para sacar.'],
        );

        $prices = collect($validated['contracts'])
            ->mapWithKeys(fn (int $id) => [$id => (int) ($validated['prices'][$id] ?? 0)])
            ->all();

        $result = $pullContracts($prices, (bool) $validated['scrap_gold'], $request->user());

        return redirect()
            ->route('reports.queued')
            ->with('status', sprintf(
                '%d artículo(s) movidos al almacén, %d contrato(s) chatarrizados.',
                $result['forfeited'],
                $result['scrapped'],
            ));
    }

    /**
     * Remove selected contracts from the repossession queue.
     */
    public function unqueue(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'contracts' => ['required', 'array', 'min:1'],
                'contracts.*' => ['integer'],
            ],
            ['contracts.required' => 'No hay contratos seleccionados.'],
        );

        $removed = Contract::query()
            ->whereIn('id', $validated['contracts'])
            ->get()
            ->each(fn (Contract $contract) => $contract->repossession()->delete())
            ->count();

        return redirect()
            ->route('reports.queued')
            ->with('status', "$removed contrato(s) removidos de la pre-saca.");
    }
}
