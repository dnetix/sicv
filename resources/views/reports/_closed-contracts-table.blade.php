{{-- Shared table for the pulled/redeemed reports. Expects $contracts with
     months_run and extensions_amount_total report columns. --}}
<div class="rounded-lg bg-white border border-line">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-line-soft text-sm">
            <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                <tr>
                    <th class="px-3 py-2">Contrato</th>
                    <th class="px-3 py-2">Cliente</th>
                    <th class="px-3 py-2">Artículo</th>
                    <th class="px-3 py-2">Tipo</th>
                    <th class="px-3 py-2">Ingreso</th>
                    <th class="px-3 py-2">Salida</th>
                    <th class="px-3 py-2">Estado</th>
                    <th class="px-3 py-2 text-right">Meses</th>
                    <th class="px-3 py-2 text-right">Valor</th>
                    <th class="px-3 py-2 text-right">Cancelado</th>
                    <th class="px-3 py-2 text-right">Abonos</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line-faint">
                @forelse ($contracts as $contract)
                    <tr class="hover:bg-cream">
                        <td class="px-3 py-2 font-medium">
                            <a href="{{ route('contracts.show', $contract) }}" class="font-bold text-accent-deep hover:underline">{{ $contract->id }}</a>
                        </td>
                        <td class="max-w-[12rem] truncate px-3 py-2">{{ $contract->client->name }}</td>
                        <td class="max-w-[14rem] truncate px-3 py-2">{{ $contract->description }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $contract->itemType->name }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $contract->started_at->format('d/m/Y') }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $contract->ended_at->format('d/m/Y') }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $contract->status->label() }}</td>
                        <td class="px-3 py-2 text-right">{{ $contract->months_run }}</td>
                        <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($contract->amount) }}</td>
                        <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($contract->settled_amount) }}</td>
                        <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($contract->extensions_amount_total) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="px-4 py-6 text-center text-ink-soft">Sin contratos en el rango seleccionado.</td></tr>
                @endforelse
            </tbody>
            @if ($contracts->isNotEmpty())
                <tfoot class="border-t-2 border-ink bg-cream font-bold">
                    <tr>
                        <td colspan="8" class="px-3 py-2">Totales ({{ $contracts->count() }})</td>
                        <td class="px-3 py-2 text-right">{{ money($contracts->sum('amount')) }}</td>
                        <td class="px-3 py-2 text-right">{{ money($contracts->sum('settled_amount')) }}</td>
                        <td class="px-3 py-2 text-right">{{ money($contracts->sum('extensions_amount_total')) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
