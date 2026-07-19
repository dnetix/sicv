@extends('layouts.app')

@section('title', 'Reporte de gastos')

@section('content')
    <x-report-header title="Reporte de gastos"
                     :subtitle="count($expenses).' gastos · '.money($expenses->sum('amount')).' en el rango'" />

    <x-report-filter :range="$range" :expense-types="$expenseTypes" />

    <div class="rounded-lg bg-white border border-line">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line-soft text-sm">
                <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                    <tr>
                        <th class="px-3 py-2">Fecha</th>
                        <th class="px-3 py-2">Concepto</th>
                        <th class="px-3 py-2">Tipo</th>
                        <th class="px-3 py-2">Empleado</th>
                        <th class="px-3 py-2 text-right">Valor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line-faint">
                    @forelse ($expenses as $expense)
                        <tr class="hover:bg-cream">
                            <td class="px-3 py-2 whitespace-nowrap">{{ $expense->spent_at->format('d/m/Y H:i') }}</td>
                            <td class="max-w-md truncate px-3 py-2">{{ $expense->description }}</td>
                            <td class="px-3 py-2 whitespace-nowrap">{{ $expense->type->name }}</td>
                            <td class="px-3 py-2 whitespace-nowrap">{{ $expense->user->name }}</td>
                            <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($expense->amount) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-ink-soft">Sin gastos en el rango seleccionado.</td></tr>
                    @endforelse
                </tbody>
                @if ($expenses->isNotEmpty())
                    <tfoot class="border-t-2 border-ink bg-cream font-bold">
                        <tr>
                            <td colspan="4" class="px-3 py-2">Total ({{ $expenses->count() }})</td>
                            <td class="px-3 py-2 text-right">{{ money($expenses->sum('amount')) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
