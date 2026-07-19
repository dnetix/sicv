@extends('layouts.app')

@section('title', 'Artículos vendidos')

@section('content')
    <x-report-header title="Artículos vendidos"
                     :subtitle="count($items).' artículos · '.money($items->sum('price')).' vendidos en el rango'" />

    <x-report-filter :range="$range" :item-types="$itemTypes" />

    <div class="rounded-lg bg-white border border-line">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line-soft text-sm">
                <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                    <tr>
                        <th class="px-3 py-2">Nota de cobro</th>
                        <th class="px-3 py-2">Fecha</th>
                        <th class="px-3 py-2">Cliente</th>
                        <th class="px-3 py-2">Artículo</th>
                        <th class="px-3 py-2">Contrato</th>
                        <th class="px-3 py-2">Tipo</th>
                        <th class="px-3 py-2 text-right">Cantidad</th>
                        <th class="px-3 py-2 text-right">Valor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line-faint">
                    @forelse ($items as $item)
                        <tr class="hover:bg-cream">
                            <td class="px-3 py-2 font-medium">
                                <a href="{{ route('sales.show', $item->sale_id) }}" class="font-bold text-accent-deep hover:underline">NC{{ $item->sale_id }}</a>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap">{{ $item->sale->sold_at->format('d/m/Y') }}</td>
                            <td class="max-w-[12rem] truncate px-3 py-2">{{ $item->sale->client->name }}</td>
                            <td class="max-w-[14rem] truncate px-3 py-2">{{ $item->storeItem->description }}</td>
                            <td class="px-3 py-2">{{ $item->storeItem->contract_id ?? '—' }}</td>
                            <td class="px-3 py-2 whitespace-nowrap">{{ $item->storeItem->itemType->name }}</td>
                            <td class="px-3 py-2 text-right">{{ $item->quantity }}</td>
                            <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($item->price) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-6 text-center text-ink-soft">Sin ventas en el rango seleccionado.</td></tr>
                    @endforelse
                </tbody>
                @if ($items->isNotEmpty())
                    <tfoot class="border-t-2 border-ink bg-cream font-bold">
                        <tr>
                            <td colspan="6" class="px-3 py-2">Totales</td>
                            <td class="px-3 py-2 text-right">{{ $items->sum('quantity') }}</td>
                            <td class="px-3 py-2 text-right">{{ money($items->sum('price')) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
