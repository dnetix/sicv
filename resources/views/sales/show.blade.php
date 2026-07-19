@extends('layouts.app')

@section('title', "Nota de Cobro NC{$sale->id}")

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold">Nota de Cobro NC{{ $sale->id }}</h1>
            <p class="text-sm text-ink-soft">
                {{ $sale->sold_at->format('d/m/Y') }} — registrada por {{ $sale->user->name }}
            </p>
        </div>
        <a href="{{ route('sales.print', $sale) }}"
           class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
            Imprimir
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 rounded-lg bg-white border border-line">
            <h2 class="border-b border-line-soft px-6 py-4 font-medium text-ink">Productos vendidos</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-line-soft text-sm">
                    <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                        <tr>
                            <th class="px-4 py-2">Id</th>
                            <th class="px-4 py-2">Contrato</th>
                            <th class="px-4 py-2">Artículo</th>
                            <th class="px-4 py-2 text-right">Cantidad</th>
                            <th class="px-4 py-2 text-right">Precio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-faint">
                        @foreach ($sale->items as $item)
                            <tr>
                                <td class="px-4 py-2 font-medium">{{ $item->store_item_id }}</td>
                                <td class="px-4 py-2">
                                    @if ($item->storeItem->contract_id)
                                        <a href="{{ route('contracts.show', $item->storeItem->contract_id) }}" class="font-bold text-accent-deep hover:underline">
                                            {{ $item->storeItem->contract_id }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="max-w-md truncate px-4 py-2">{{ $item->storeItem->description }}</td>
                                <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                                <td class="px-4 py-2 text-right">{{ money($item->price) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-ink bg-cream font-bold">
                        <tr>
                            <td colspan="4" class="px-4 py-2">Total</td>
                            <td class="px-4 py-2 text-right">{{ money($sale->total) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg bg-white p-6 border border-line">
                <h2 class="mb-3 font-medium text-ink">Cliente</h2>
                <p class="font-medium">
                    <a href="{{ route('clients.show', $sale->client) }}" class="font-bold text-accent-deep hover:underline">{{ $sale->client->name }}</a>
                </p>
                <p class="text-sm text-ink-soft">{{ $sale->client->document_type }} {{ $sale->client->document_number }}</p>
            </div>

            <div class="rounded-lg border border-line bg-white p-6 text-sm">
                <h2 class="mb-3 font-medium text-ink">Garantía</h2>
                @if ($sale->warranty_days > 0)
                    <p>{{ $sale->warranty_days }} días de garantía.</p>
                @else
                    <p class="text-ink-soft">Sin garantía (artículo usado).</p>
                @endif
            </div>
        </div>
    </div>
@endsection
