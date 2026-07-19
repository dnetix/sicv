@extends('layouts.app')

@section('title', 'Valores modificados')

@section('content')
    <h1 class="mb-2 text-xl font-bold">Valores modificados por operadores</h1>
    <p class="mb-6 text-sm text-ink-soft">
        Operaciones donde el valor cobrado difiere del valor sugerido por el sistema.
        La operación nunca se bloquea; aquí queda el registro para revisión.
    </p>

    <x-report-filter :range="$range">
        <div>
            <label for="operation" class="mb-1 block text-xs font-medium text-ink-soft">Operación</label>
            <select id="operation" name="operation" class="rounded-md border-line text-sm">
                <option value="">Todas</option>
                @foreach ($operations as $value => $label)
                    <option value="{{ $value }}" @selected(request('operation') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </x-report-filter>

    <div class="rounded-lg bg-white border border-line">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line-soft text-sm">
                <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                    <tr>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Operación</th>
                        <th class="px-4 py-2">Referencia</th>
                        <th class="px-4 py-2">Usuario</th>
                        <th class="px-4 py-2 text-right">Valor sugerido</th>
                        <th class="px-4 py-2 text-right">Valor cobrado</th>
                        <th class="px-4 py-2 text-right">Diferencia</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line-faint">
                    @forelse ($overrides as $override)
                        @php $difference = $override->entered_amount - $override->computed_amount; @endphp
                        <tr class="hover:bg-cream">
                            <td class="px-4 py-2 whitespace-nowrap">{{ $override->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $operations[$override->operation] ?? $override->operation }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                @if ($override->auditable instanceof \App\Models\Contract)
                                    <a href="{{ route('contracts.show', $override->auditable) }}" class="font-bold text-accent-deep hover:underline">Contrato {{ $override->auditable_id }}</a>
                                @elseif ($override->auditable instanceof \App\Models\SaleItem)
                                    <a href="{{ route('sales.show', $override->auditable->sale_id) }}" class="font-bold text-accent-deep hover:underline">NC{{ $override->auditable->sale_id }}</a>
                                @else
                                    {{ class_basename($override->auditable_type) }} {{ $override->auditable_id }}
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $override->user->name }}</td>
                            <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($override->computed_amount) }}</td>
                            <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($override->entered_amount) }}</td>
                            <td @class([
                                'px-4 py-2 text-right whitespace-nowrap font-medium',
                                'text-red-700' => $difference < 0,
                                'text-emerald-700' => $difference > 0,
                            ])>
                                {{ $difference > 0 ? '+' : ($difference < 0 ? '−' : '') }}{{ money(abs($difference)) }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-6 text-center text-ink-soft">Sin valores modificados en el rango seleccionado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($overrides->hasPages())
            <div class="border-t border-line-soft px-4 py-3">
                {{ $overrides->links() }}
            </div>
        @endif
    </div>
@endsection
