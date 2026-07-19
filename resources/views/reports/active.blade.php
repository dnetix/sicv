@extends('layouts.app')

@section('title', 'Contratos activos')

@section('content')
    <x-report-header title="Contratos activos"
                     :subtitle="number_format($totals->count).' contratos activos · '.money($totals->amount).' prestados en total'" />

    <x-report-filter :range="$range" :item-types="$itemTypes" />

    @if (! $filtered)
        <p class="rounded-lg bg-white p-6 text-sm text-ink-soft border border-line">
            Use el filtro para listar los contratos activos por rango de fecha de creación.
        </p>
    @else
        <div class="rounded-lg bg-white border border-line">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-line-soft text-sm">
                    <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                        <tr>
                            <th class="px-3 py-2">Contrato</th>
                            <th class="px-3 py-2">Cliente</th>
                            <th class="px-3 py-2">Teléfono</th>
                            <th class="px-3 py-2">Fecha</th>
                            <th class="px-3 py-2">Artículo</th>
                            <th class="px-3 py-2 text-right">Valor</th>
                            <th class="px-3 py-2 text-right">Meses transc.</th>
                            <th class="px-3 py-2 text-right">Meses abonados</th>
                            <th class="px-3 py-2 text-right">Faltante</th>
                            <th class="px-3 py-2 text-right">A pagar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-faint">
                        @forelse ($contracts as $contract)
                            <tr class="hover:bg-cream">
                                <td class="px-3 py-2 font-medium">
                                    <a href="{{ route('contracts.show', $contract) }}" class="font-bold text-accent-deep hover:underline">{{ $contract->id }}</a>
                                </td>
                                <td class="max-w-[12rem] truncate px-3 py-2">{{ $contract->client->name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $contract->client->phone ?: $contract->client->mobile }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $contract->started_at->format('d/m/Y') }}</td>
                                <td class="max-w-[14rem] truncate px-3 py-2">{{ $contract->description }}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($contract->amount) }}</td>
                                <td class="px-3 py-2 text-right">{{ $contract->view_months_elapsed }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($contract->extended_months_total, 2, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($contract->reportOwed()) }}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap font-medium">{{ money($contract->reportPayoff()) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="px-4 py-6 text-center text-ink-soft">Sin contratos en el rango seleccionado.</td></tr>
                        @endforelse
                    </tbody>
                    @if ($contracts->isNotEmpty())
                        <tfoot class="border-t-2 border-ink bg-cream font-bold">
                            <tr>
                                <td colspan="5" class="px-3 py-2">Totales ({{ $contracts->count() }})</td>
                                <td class="px-3 py-2 text-right">{{ money($contracts->sum('amount')) }}</td>
                                <td colspan="2"></td>
                                <td class="px-3 py-2 text-right">{{ money($contracts->sum(fn ($c) => $c->reportOwed())) }}</td>
                                <td class="px-3 py-2 text-right">{{ money($contracts->sum(fn ($c) => $c->reportPayoff())) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    @endif
@endsection
