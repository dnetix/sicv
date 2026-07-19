@extends('layouts.app')

@section('title', 'Gastos')

@section('content')
    <h1 class="mb-6 text-xl font-bold">Gastos</h1>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('expenses.store') }}" class="space-y-4 self-start rounded-lg bg-white p-6 border border-line">
            @csrf

            <h2 class="font-medium text-ink">Nuevo gasto</h2>

            <x-field label="Fecha" name="date_display" :value="now()->format('d/m/Y')" disabled
                     class="w-full rounded-md border-line-soft bg-cream text-ink-soft" />

            <x-field label="Valor" name="amount" type="number" min="1" required :value="old('amount')" />

            <x-field label="Tipo de gasto" name="expense_type_id" required>
                <select id="expense_type_id" name="expense_type_id" required
                        class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">
                    <option value="">Seleccione…</option>
                    @foreach ($expenseTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('expense_type_id') == $type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
            </x-field>

            <x-field label="Concepto" name="description">
                <textarea id="description" name="description" rows="3"
                          class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">{{ old('description') }}</textarea>
            </x-field>

            <button type="submit"
                    class="w-full rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
                Guardar gasto
            </button>
        </form>

        <div class="lg:col-span-2 rounded-lg bg-white border border-line">
            <h2 class="border-b border-line-soft px-6 py-4 font-medium text-ink">
                Gastos del mes ({{ now()->translatedFormat('F Y') }})
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-line-soft text-sm">
                    <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                        <tr>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Concepto</th>
                            <th class="px-4 py-2">Tipo</th>
                            <th class="px-4 py-2">Empleado</th>
                            <th class="px-4 py-2 text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-faint">
                        @forelse ($expenses as $expense)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $expense->spent_at->format('d/m/Y H:i') }}</td>
                                <td class="max-w-xs truncate px-4 py-2">{{ $expense->description }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $expense->type->name }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $expense->user->name }}</td>
                                <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($expense->amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-ink-soft">Sin gastos este mes.</td></tr>
                        @endforelse
                    </tbody>
                    @if ($expenses->isNotEmpty())
                        <tfoot class="border-t-2 border-ink bg-cream font-bold">
                            <tr>
                                <td colspan="4" class="px-4 py-2">Total</td>
                                <td class="px-4 py-2 text-right">{{ money($expenses->sum('amount')) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
