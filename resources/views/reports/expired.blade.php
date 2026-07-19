@extends('layouts.app')

@section('title', 'Contratos vencidos')

@section('content')
    <x-report-header title="Contratos vencidos"
                     :subtitle="$contracts->count().' contratos vencidos sin pre-saca'" />

    <form method="POST" action="{{ route('operations.queue') }}" x-data="{ selected: [] }">
        @csrf

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 print:hidden">
            <p class="text-sm text-ink-soft">
                Seleccione los contratos a marcar para pre-saca. Los contratos en pre-saca no aparecen en esta lista.
            </p>
            <div class="flex gap-3">
                <button type="submit" :disabled="selected.length === 0"
                        class="rounded-md bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500 disabled:cursor-not-allowed disabled:opacity-50">
                    Pre-sacar seleccionados (<span x-text="selected.length"></span>)
                </button>
            </div>
        </div>

        <div class="rounded-lg bg-white border border-line">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-line-soft text-sm">
                    <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                        <tr>
                            <th class="px-3 py-2 print:hidden"></th>
                            <th class="px-3 py-2">Contrato</th>
                            <th class="px-3 py-2">Cliente</th>
                            <th class="px-3 py-2">Teléfono</th>
                            <th class="px-3 py-2">Fecha</th>
                            <th class="px-3 py-2">Artículo</th>
                            <th class="px-3 py-2 text-right">Valor</th>
                            <th class="px-3 py-2 text-right">Meses transc.</th>
                            <th class="px-3 py-2 text-right">Meses abonados</th>
                            <th class="px-3 py-2 text-right">Abono mensual</th>
                            <th class="px-3 py-2 text-right">Faltante</th>
                            <th class="px-3 py-2 text-right">A pagar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-faint">
                        @forelse ($contracts as $contract)
                            <tr class="hover:bg-cream">
                                <td class="px-3 py-2 print:hidden">
                                    <input type="checkbox" name="contracts[]" value="{{ $contract->id }}"
                                           x-model="selected" class="rounded border-line">
                                </td>
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
                                <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($contract->reportMonthlyCharge()) }}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($contract->reportOwed()) }}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap font-medium">{{ money($contract->reportPayoff()) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="12" class="px-4 py-6 text-center text-ink-soft">No hay contratos vencidos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
@endsection
