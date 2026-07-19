@extends('layouts.app')

@section('title', 'Contratos en pre-saca')

@section('content')
    <x-report-header title="Contratos en pre-saca"
                     :subtitle="$contracts->count().' contratos marcados para sacar'" />

    <form method="POST" x-data="{ selected: {{ $contracts->pluck('id')->toJson() }}, action: null }"
          @submit="$el.action = action === 'pull' ? '{{ route('operations.pull') }}' : '{{ route('operations.unqueue') }}'">
        @csrf

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 print:hidden">
            <div class="flex items-center gap-3 text-sm">
                @if ($hasGold)
                    <label for="scrap_gold" class="font-medium">Oro seleccionado:</label>
                    <select id="scrap_gold" name="scrap_gold" class="rounded-md border-line text-sm">
                        <option value="1">Chatarrizar</option>
                        <option value="0">Mover al almacén</option>
                    </select>
                @else
                    <input type="hidden" name="scrap_gold" value="0">
                @endif
            </div>

            <div class="flex gap-3">
                <button type="submit" @click="action = 'unqueue'" :disabled="selected.length === 0"
                        class="rounded-md border border-line bg-white px-4 py-2 text-sm hover:bg-cream disabled:opacity-50">
                    Remover de pre-saca
                </button>
                <button type="submit" @click="action = 'pull'" :disabled="selected.length === 0"
                        class="rounded-md bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500 disabled:cursor-not-allowed disabled:opacity-50">
                    Sacar seleccionados (<span x-text="selected.length"></span>)
                </button>
            </div>
        </div>

        <div class="rounded-lg bg-white border border-line">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-line-soft text-sm">
                    <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                        <tr>
                            <th class="px-3 py-2"></th>
                            <th class="px-3 py-2">Contrato</th>
                            <th class="px-3 py-2">Cliente</th>
                            <th class="px-3 py-2">Artículo</th>
                            <th class="px-3 py-2">Tipo</th>
                            <th class="px-3 py-2">Fecha</th>
                            <th class="px-3 py-2 text-right">Valor</th>
                            <th class="px-3 py-2 text-right">Meses transc.</th>
                            <th class="px-3 py-2 text-right">Valor venta</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-faint">
                        @forelse ($contracts as $contract)
                            <tr class="hover:bg-cream">
                                <td class="px-3 py-2">
                                    <input type="checkbox" name="contracts[]" value="{{ $contract->id }}"
                                           x-model.number="selected" class="rounded border-line">
                                </td>
                                <td class="px-3 py-2 font-medium">
                                    <a href="{{ route('contracts.show', $contract) }}" class="font-bold text-accent-deep hover:underline">{{ $contract->id }}</a>
                                </td>
                                <td class="max-w-[12rem] truncate px-3 py-2">{{ $contract->client->name }}</td>
                                <td class="max-w-[14rem] truncate px-3 py-2">{{ $contract->description }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $contract->itemType->name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $contract->started_at->format('d/m/Y') }}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($contract->amount) }}</td>
                                <td class="px-3 py-2 text-right">{{ $contract->view_months_elapsed }}</td>
                                <td class="px-3 py-2 text-right">
                                    {{-- Suggested price = loan amount (legacy default); editable, audited. --}}
                                    <input type="number" min="0" name="prices[{{ $contract->id }}]"
                                           value="{{ $contract->amount }}"
                                           class="w-32 rounded-md border-line text-right text-sm">
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-4 py-6 text-center text-ink-soft">No hay contratos en pre-saca.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
@endsection
