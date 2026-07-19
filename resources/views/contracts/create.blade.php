@extends('layouts.app')

@section('title', 'Nuevo contrato')

@section('content')
    <div x-data="contractForm({
             quickStoreUrl: '{{ route('clients.quick-store') }}',
             client: @js($client),
             term: {{ $defaultTerm }},
             rate: {{ $defaultRate }},
         })">

        <div class="mb-5">
            <h1 class="text-xl font-bold">Nuevo contrato</h1>
            <p class="mt-0.5 text-xs text-ink-soft">Al guardar se abre el contrato legal listo para imprimir</p>
        </div>

        <div class="flex flex-col gap-5 lg:flex-row">
            <div class="min-w-0 flex-1 space-y-4">

                {{-- Step 1: client --}}
                <div class="rounded-lg border border-line bg-white p-4.5">
                    <div class="mb-3 flex items-center gap-2.5">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-bold">1</span>
                        <h2 class="text-sm font-bold">Cliente (EL VENDEDOR)</h2>
                    </div>

                    <template x-if="client">
                        <div>
                            <div class="flex items-center justify-between gap-3 rounded-md border border-line bg-canvas px-3 py-2.5">
                                <div class="flex min-w-0 items-center gap-2.5">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-accent-tint text-xs font-bold text-accent-deep"
                                          x-text="client.name.trim().charAt(0).toUpperCase()"></span>
                                    <div class="min-w-0 text-xs">
                                        <p class="truncate text-[13px] font-bold" x-text="client.name"></p>
                                        <p class="text-ink-soft">
                                            <span x-text="(client.document_type ?? 'CC') + ' ' + client.document_number"></span><span x-show="client.city"> · <span x-text="client.city"></span></span><span x-show="client.contracts_count > 0"> · <span x-text="client.contracts_count"></span> contratos previos</span>
                                        </p>
                                        <p class="text-ink-soft" x-show="client.phone || client.address">
                                            <span x-show="client.phone">Tel. <span x-text="client.phone"></span></span><span x-show="client.phone && client.address"> · </span><span x-show="client.address" x-text="client.address"></span>
                                        </p>
                                    </div>
                                </div>
                                <button type="button" class="shrink-0 text-xs font-bold text-accent-deep hover:underline" @click="client = null">Cambiar</button>
                            </div>

                            <div class="mt-3">
                                <div class="mb-2 flex items-center justify-between">
                                    <p class="flex items-center gap-1.5 text-xs font-bold">
                                        <svg class="h-3.5 w-3.5 text-accent-deep" viewBox="0 0 16 16" fill="currentColor">
                                            <path d="M3 1.5a.5.5 0 0 1 1 0V2h9.2a.5.5 0 0 1 .4.8L11.4 5.5l2.2 2.7a.5.5 0 0 1-.4.8H4v5.5a.5.5 0 0 1-1 0v-13Z"/>
                                        </svg>
                                        Notas del cliente
                                    </p>
                                    <a :href="client.url + '#notes'" class="text-[11px] font-bold text-accent-deep hover:underline">+ Agregar nota</a>
                                </div>

                                <div class="space-y-2">
                                    <template x-for="note in client.notes" :key="note.body + note.date">
                                        <div class="flex items-start justify-between gap-3 rounded-md border px-3 py-2 text-xs"
                                             :class="note.severity === 'alert'
                                                 ? 'border-danger-line bg-danger-pale text-danger-deep'
                                                 : 'border-accent-line bg-accent-pale'">
                                            <p x-text="note.body"></p>
                                            <p class="whitespace-nowrap text-[11px] text-ink-soft" x-text="note.author + ' · ' + note.date"></p>
                                        </div>
                                    </template>
                                    <p x-show="!client.notes || client.notes.length === 0" class="text-xs text-ink-faint">Sin notas registradas.</p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="!client" x-cloak @client-selected.window="selectClient($event.detail)">
                        <x-client-search mode="select" placeholder="Buscar por cédula (prefijo) o nombre…" />

                        <button type="button" @click="creatingClient = !creatingClient"
                                class="mt-3 text-xs font-bold text-accent-deep hover:underline">
                            + Crear cliente nuevo
                        </button>

                        <form x-show="creatingClient" x-cloak class="mt-3 space-y-3 border-t border-line-soft pt-4"
                              @submit.prevent="submitNewClient($event.target)">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <select name="document_type" class="rounded-md border-line text-sm">
                                    <option value="CC">C.C.</option>
                                    <option value="CE">C.E.</option>
                                </select>
                                <input name="document_number" required minlength="3" placeholder="Documento *"
                                       class="rounded-md border-line text-sm sm:col-span-2">
                            </div>
                            <input name="name" required minlength="3" placeholder="Nombre completo *"
                                   class="w-full rounded-md border-line text-sm">
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <input name="document_issue_place" required placeholder="Lugar de expedición *"
                                       class="rounded-md border-line text-sm">
                                <input name="city" required minlength="3" placeholder="Ciudad *"
                                       class="rounded-md border-line text-sm">
                            </div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <input name="address" placeholder="Dirección" class="rounded-md border-line text-sm">
                                <input name="phone" placeholder="Teléfono" class="rounded-md border-line text-sm">
                                <input name="mobile" placeholder="Celular" class="rounded-md border-line text-sm">
                            </div>
                            <p x-show="clientError" x-cloak x-text="clientError" class="text-sm text-amber-700"></p>
                            <button type="submit"
                                    class="rounded-md border border-line bg-white px-4 py-2 text-sm font-bold text-ink hover:bg-cream">
                                Guardar cliente
                            </button>
                        </form>
                    </div>

                    @error('client_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    </div>

                <form id="contract-form" method="POST" action="{{ route('contracts.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="client_id" :value="client?.id">

                    {{-- Step 2: item --}}
                    <div class="space-y-3 rounded-lg border border-line bg-white p-4.5">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-bold">2</span>
                            <h2 class="text-sm font-bold">Artículo</h2>
                        </div>

                        <x-field label="Descripción" name="description" required>
                            <textarea id="description" name="description" rows="3" required
                                      placeholder="Cadena de oro 18k, 15 gramos, con dije…"
                                      class="w-full rounded-md border-line shadow-sm placeholder-ink-faint focus:border-accent-deep focus:ring-accent-deep">{{ old('description') }}</textarea>
                        </x-field>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <x-field label="Tipo de artículo" name="item_type_id" required>
                                <select id="item_type_id" name="item_type_id" required x-ref="itemType"
                                        class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">
                                    <option value="">Seleccione…</option>
                                    @foreach ($itemTypes as $type)
                                        <option value="{{ $type->id }}" @selected(old('item_type_id') == $type->id)>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </x-field>

                            <x-field label="Peso (gramos)" name="weight_grams" type="number"
                                     step="0.01" min="0" :value="old('weight_grams')" />
                        </div>

                        <p class="text-[11px] text-ink-faint">El peso es obligatorio solo cuando el tipo es Oro</p>
                    </div>

                    {{-- Step 3: loan terms --}}
                    <div class="space-y-3 rounded-lg border border-line bg-white p-4.5">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-bold">3</span>
                            <h2 class="text-sm font-bold">Condiciones del préstamo</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <x-field label="Monto (pesos)" name="amount" required>
                                <input id="amount" name="amount" type="number" min="1" required x-model="amount"
                                       value="{{ old('amount') }}"
                                       class="w-full rounded-md border-2 border-ink text-base font-bold focus:border-accent-deep focus:ring-accent-deep">
                            </x-field>

                            <x-field label="Interés mensual %" name="monthly_rate" required>
                                <input id="monthly_rate" name="monthly_rate" type="number" step="0.1" min="0.1" max="100" required
                                       x-model="rate" value="{{ old('monthly_rate', $defaultRate) }}"
                                       class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">
                            </x-field>

                            <x-field label="Plazo (meses)" name="term_months" required>
                                <input id="term_months" name="term_months" type="number" min="1" max="24" required
                                       x-model="term" value="{{ old('term_months', $defaultTerm) }}"
                                       class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">
                            </x-field>
                        </div>

                        <p class="text-[11px] text-ink-faint">
                            Por defecto: {{ $defaultTerm }} meses al {{ $defaultRate }}% · editable por contrato
                        </p>
                    </div>
                </form>
            </div>

            {{-- Summary sidebar --}}
            <aside class="w-full shrink-0 space-y-3.5 self-start lg:sticky lg:top-6 lg:w-[300px]">
                <div class="space-y-3.5 rounded-lg bg-night p-5 text-xs text-accent-tint">
                    <h2 class="text-[13px] font-bold text-accent">Resumen del contrato</h2>
                    <div class="flex justify-between">
                        <span class="text-night-soft">Préstamo</span>
                        <span class="font-bold" x-text="amount ? formatMoney(parseInt(amount, 10)) : '—'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-night-soft">Interés mensual</span>
                        <span class="font-bold" x-text="monthlyPayment !== null ? formatMoney(monthlyPayment) : '—'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-night-soft">Vence</span>
                        <span class="font-bold" x-text="dueDate ?? '—'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-night-soft">Retroventa (impreso)</span>
                        <span class="font-bold text-accent" x-text="buyBack !== null ? formatMoney(buyBack) : '—'"></span>
                    </div>
                    <p class="text-[10px] text-night-faint">
                        retroventa = monto + monto × <span x-text="rate"></span>% × <span x-text="term"></span> meses
                    </p>
                </div>

                <button type="submit" form="contract-form" :disabled="!client"
                        class="h-12 w-full rounded-md bg-accent text-sm font-bold text-ink hover:bg-accent-strong disabled:cursor-not-allowed disabled:opacity-50">
                    Guardar e imprimir contrato
                </button>

                <a href="{{ route('dashboard') }}"
                   class="flex h-10 w-full items-center justify-center rounded-md border border-line bg-white text-[13px] text-ink-nav hover:bg-cream">
                    Cancelar
                </a>

                <p class="text-center text-[11px] text-ink-faint">
                    Se imprime el original con código de barras; las reimpresiones llevan marca DUPLICADO
                </p>
            </aside>
        </div>
    </div>
@endsection
