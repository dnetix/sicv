@extends('layouts.app')

@section('title', 'Nueva venta')

@section('content')
    <div x-data="posCart()">
        <h1 class="mb-6 text-xl font-bold">Nueva venta</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                {{-- Item search --}}
                <div class="rounded-lg bg-white p-6 border border-line"
                     x-data="remoteSearch('{{ route('sales.search-items') }}')" @click.outside="close()">
                    <h2 class="mb-3 font-medium text-ink">Buscar artículos</h2>
                    <div class="relative">
                        <input type="search" x-model="query" @input="input()" autocomplete="off"
                               placeholder="Id, contrato o descripción del artículo…"
                               class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">

                        <div x-show="open" x-cloak
                             class="absolute z-10 mt-1 max-h-80 w-full overflow-y-auto rounded-md border border-line-soft bg-white shadow-lg">
                            <template x-if="results.length === 0">
                                <p class="px-4 py-3 text-sm text-ink-soft">No hay artículos disponibles con esa búsqueda.</p>
                            </template>
                            <template x-for="item in results" :key="item.id">
                                <button type="button" @click="$dispatch('item-picked', item); close(); query = ''"
                                        class="flex w-full items-center justify-between px-4 py-2 text-left text-sm hover:bg-cream">
                                    <span>
                                        <span class="font-medium" x-text="'No. ' + item.id"></span>
                                        <template x-if="item.contract_id">
                                            <span class="text-ink-soft" x-text="' (contrato ' + item.contract_id + ')'"></span>
                                        </template>
                                        <span class="block truncate text-ink-soft" x-text="item.description"></span>
                                    </span>
                                    <span class="ml-3 whitespace-nowrap font-medium" x-text="'$ ' + new Intl.NumberFormat('es-CO').format(item.price)"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Cart --}}
                <form method="POST" action="{{ route('sales.store') }}" @item-picked.window="addItem($event.detail)"
                      class="rounded-lg bg-white border border-line">
                    @csrf
                    <input type="hidden" name="client_id" :value="client?.id">

                    <h2 class="border-b border-line-soft px-6 py-4 font-medium text-ink">Productos</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-line-soft text-sm">
                            <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                                <tr>
                                    <th class="px-4 py-2">Id</th>
                                    <th class="px-4 py-2">Contrato</th>
                                    <th class="px-4 py-2">Artículo</th>
                                    <th class="px-4 py-2 text-right">V. Compra</th>
                                    <th class="px-4 py-2 text-right">Precio de venta</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line-faint">
                                <template x-if="lines.length === 0">
                                    <tr><td colspan="6" class="px-4 py-6 text-center text-ink-soft">Agregue artículos con el buscador.</td></tr>
                                </template>
                                <template x-for="(line, index) in lines" :key="line.id">
                                    <tr>
                                        <td class="px-4 py-2 font-medium" x-text="line.id"></td>
                                        <td class="px-4 py-2" x-text="line.contract_id ?? '—'"></td>
                                        <td class="max-w-xs truncate px-4 py-2" x-text="line.description"></td>
                                        <td class="px-4 py-2 text-right" x-text="formatMoney(line.cost)"></td>
                                        <td class="px-4 py-2 text-right">
                                            <input type="hidden" :name="`items[${index}][store_item_id]`" :value="line.id">
                                            <input type="number" min="0" required :name="`items[${index}][price]`"
                                                   x-model="line.salePrice"
                                                   class="w-32 rounded-md border-line text-right text-sm">
                                        </td>
                                        <td class="px-2 py-2">
                                            <button type="button" @click="removeLine(index)"
                                                    class="text-red-500 hover:text-red-700" title="Quitar">&times;</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot class="bg-cream font-medium" x-show="lines.length > 0" x-cloak>
                                <tr>
                                    <td colspan="4" class="px-4 py-2">Total</td>
                                    <td class="px-4 py-2 text-right" x-text="formatMoney(total)"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="flex flex-wrap items-end justify-between gap-4 border-t border-line-soft px-6 py-4">
                        <div>
                            <label for="warranty_days" class="mb-1 block text-sm font-medium">Días de garantía</label>
                            <input id="warranty_days" name="warranty_days" type="number" min="0" max="365"
                                   x-model="warranty" value="0" required
                                   class="w-28 rounded-md border-line text-sm">
                        </div>

                        <button type="submit" :disabled="!client || lines.length === 0"
                                class="rounded-md bg-accent px-6 py-2 text-sm font-bold text-ink hover:bg-accent-strong disabled:cursor-not-allowed disabled:opacity-50">
                            Registrar venta
                        </button>
                    </div>
                </form>
            </div>

            {{-- Client --}}
            <div class="rounded-lg bg-white p-6 border border-line" @client-selected.window="client = $event.detail">
                <h2 class="mb-3 font-medium text-ink">Cliente <span class="text-red-500">*</span></h2>

                <template x-if="client">
                    <div class="mb-3 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3">
                        <p class="font-medium" x-text="client.name"></p>
                        <p class="text-sm text-ink-soft" x-text="client.document_number"></p>
                        <button type="button" class="mt-1 text-sm text-ink-soft underline" @click="client = null">Cambiar</button>
                    </div>
                </template>

                <div x-show="!client" x-cloak>
                    <x-client-search mode="select" />
                    <p class="mt-3 text-sm text-ink-soft">
                        La venta requiere un cliente registrado.
                        <a href="{{ route('clients.create') }}" class="font-bold text-accent-deep hover:underline">Crear cliente nuevo</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
