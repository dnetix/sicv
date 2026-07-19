@extends('layouts.app')

@section('title', 'Artículos a la venta')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold">Artículos a la venta ({{ $items->count() }})</h1>
        <div class="flex gap-3">
            <a href="{{ route('store.create') }}"
               class="rounded-md border border-line bg-white px-4 py-2 text-sm hover:bg-cream">Nuevo artículo</a>
            <a href="{{ route('sales.create') }}"
               class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">Nueva venta</a>
        </div>
    </div>

    <div class="rounded-lg bg-white border border-line">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line-soft text-sm">
                <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                    <tr>
                        <th class="px-4 py-2">Id</th>
                        <th class="px-4 py-2">Contrato</th>
                        <th class="px-4 py-2">Artículo</th>
                        <th class="px-4 py-2">Tipo</th>
                        <th class="px-4 py-2">Ingreso</th>
                        <th class="px-4 py-2 text-right">V. Compra</th>
                        <th class="px-4 py-2 text-right">V. Venta</th>
                        <th class="px-4 py-2 text-right">Cantidad</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line-faint">
                    @forelse ($items as $item)
                        <tr class="hover:bg-cream">
                            <td class="px-4 py-2 font-medium">{{ $item->id }}</td>
                            <td class="px-4 py-2">
                                @if ($item->contract_id)
                                    <a href="{{ route('contracts.show', $item->contract_id) }}" class="font-bold text-accent-deep hover:underline">{{ $item->contract_id }}</a>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="max-w-md truncate px-4 py-2">{{ $item->description }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $item->itemType->name }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $item->entered_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($item->cost) }}</td>
                            <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($item->price) }}</td>
                            <td class="px-4 py-2 text-right">{{ $item->stock }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-6 text-center text-ink-soft">No hay artículos disponibles.</td></tr>
                    @endforelse
                </tbody>
                @if ($items->isNotEmpty())
                    <tfoot class="border-t-2 border-ink bg-cream font-bold">
                        <tr>
                            <td colspan="5" class="px-4 py-2">Totales</td>
                            <td class="px-4 py-2 text-right">{{ money($totals['cost']) }}</td>
                            <td class="px-4 py-2 text-right">{{ money($totals['price']) }}</td>
                            <td class="px-4 py-2 text-right">{{ $totals['stock'] }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
