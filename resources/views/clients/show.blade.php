@extends('layouts.app')

@section('title', $client->name)

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold">{{ $client->name }}</h1>
            <p class="text-sm text-ink-soft">{{ $client->document_type }} {{ $client->document_number }}</p>
        </div>
        <a href="{{ route('contracts.create', ['client' => $client->id]) }}"
           class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
            Nuevo contrato
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <form method="POST" action="{{ route('clients.update', $client) }}"
                  class="space-y-4 rounded-lg bg-white p-6 border border-line">
                @csrf
                @method('PUT')

                <h2 class="font-medium text-ink">Datos del cliente</h2>

                <x-field label="Documento" name="document_number_display" :value="$client->document_number" disabled
                         class="w-full rounded-md border-line-soft bg-cream text-ink-soft" />

                <x-field label="Tipo" name="document_type" required>
                    <select id="document_type" name="document_type"
                            class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">
                        <option value="CC" @selected(old('document_type', $client->document_type) === 'CC')>Cédula de ciudadanía</option>
                        <option value="CE" @selected(old('document_type', $client->document_type) === 'CE')>Cédula de extranjería</option>
                    </select>
                </x-field>

                <x-field label="Nombre completo" name="name" :value="$client->name" required />
                <x-city-field label="Lugar de expedición" name="document_issue_place" :value="$client->document_issue_place" required />
                <x-city-field label="Ciudad" name="city" :value="$client->city" />
                <x-field label="Dirección" name="address" :value="$client->address" />
                <x-field label="Teléfono" name="phone" :value="$client->phone" />
                <x-field label="Celular" name="mobile" :value="$client->mobile" />
                <x-field label="Correo electrónico" name="email" type="email" :value="$client->email" />

                <div class="flex justify-end">
                    <button type="submit"
                            class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-6 lg:col-span-2">
            <div id="notes" class="rounded-lg border border-line bg-white p-6">
                <h2 class="mb-4 flex items-center gap-2 font-medium text-ink">
                    <svg class="h-4 w-4 text-accent-deep" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M3 1.5a.5.5 0 0 1 1 0V2h9.2a.5.5 0 0 1 .4.8L11.4 5.5l2.2 2.7a.5.5 0 0 1-.4.8H4v5.5a.5.5 0 0 1-1 0v-13Z"/>
                    </svg>
                    Notas del cliente
                </h2>

                <div class="space-y-2">
                    @forelse ($client->notes as $note)
                        <div @class([
                            'flex items-start justify-between gap-2 rounded-md border px-3 py-2 text-sm',
                            'border-accent-line bg-accent-pale' => $note->severity === \App\Enums\ClientNoteSeverity::Warning,
                            'border-danger-line bg-danger-pale text-danger-deep' => $note->severity === \App\Enums\ClientNoteSeverity::Alert,
                        ])>
                            <div class="min-w-0">
                                <p>{{ $note->body }}</p>
                                <p class="mt-0.5 text-[11px] text-ink-soft">{{ $note->user->name }} · {{ $note->created_at->format('d/m/Y') }}</p>
                            </div>
                            @if (auth()->user()->isAdministrator())
                                <form method="POST" action="{{ route('clients.notes.destroy', [$client, $note]) }}"
                                      onsubmit="return confirm('¿Eliminar esta nota?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-ink-faint hover:text-danger-deep" aria-label="Eliminar nota">&times;</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-ink-soft">Sin notas registradas.</p>
                    @endforelse
                </div>

                <form method="POST" action="{{ route('clients.notes.store', $client) }}"
                      class="mt-4 flex flex-wrap items-start gap-3 border-t border-line-soft pt-4">
                    @csrf
                    <div class="min-w-0 flex-1">
                        <textarea name="body" rows="1" required minlength="3" maxlength="500"
                                  placeholder="Nueva nota — ej. Dejó su cédula física, guardada en la caja fuerte"
                                  class="w-full rounded-md border-line text-sm focus:border-accent-deep focus:ring-accent-deep">{{ old('body') }}</textarea>
                        @error('body')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <select name="severity" class="rounded-md border-line text-sm focus:border-accent-deep focus:ring-accent-deep">
                        @foreach (\App\Enums\ClientNoteSeverity::cases() as $severity)
                            <option value="{{ $severity->value }}" @selected(old('severity') === $severity->value)>{{ $severity->label() }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
                        Agregar nota
                    </button>
                </form>
            </div>

            <div class="rounded-lg bg-white border border-line">
                <h2 class="border-b border-line-soft px-6 py-4 font-medium text-ink">
                    Contratos ({{ $client->contracts->count() }})
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-line-soft text-sm">
                        <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                            <tr>
                                <th class="px-4 py-2">No.</th>
                                <th class="px-4 py-2">Fecha</th>
                                <th class="px-4 py-2">Artículo</th>
                                <th class="px-4 py-2 text-right">Valor</th>
                                <th class="px-4 py-2">Estado</th>
                                <th class="px-4 py-2 text-right">Abonos</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line-faint">
                            @forelse ($client->contracts as $contract)
                                <tr class="hover:bg-cream">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('contracts.show', $contract) }}" class="font-bold text-accent-deep hover:underline">{{ $contract->id }}</a>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $contract->started_at->format('d/m/Y') }}</td>
                                    <td class="max-w-xs truncate px-4 py-2">{{ $contract->description }}</td>
                                    <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($contract->amount) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $contract->status->label() }}
                                        @if ($contract->status === \App\Enums\ContractStatus::Redeemed && $contract->settled_amount !== null)
                                            <span class="text-ink-soft">— {{ money($contract->settled_amount) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($contract->extensions_sum_amount) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-6 text-center text-ink-soft">Sin contratos registrados.</td></tr>
                            @endforelse
                        </tbody>
                        @if ($client->contracts->isNotEmpty())
                            <tfoot class="border-t-2 border-ink bg-cream font-bold">
                                <tr>
                                    <td colspan="3" class="px-4 py-2">Totales</td>
                                    <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($client->contracts->sum('amount')) }}</td>
                                    <td></td>
                                    <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($client->contracts->sum('extensions_sum_amount')) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <div class="rounded-lg bg-white border border-line">
                <h2 class="border-b border-line-soft px-6 py-4 font-medium text-ink">
                    Compras en almacén ({{ $client->sales->count() }})
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-line-soft text-sm">
                        <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                            <tr>
                                <th class="px-4 py-2">Nota de cobro</th>
                                <th class="px-4 py-2">Fecha</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line-faint">
                            @forelse ($client->sales as $sale)
                                <tr class="hover:bg-cream">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('sales.show', $sale) }}" class="font-bold text-accent-deep hover:underline">NC{{ $sale->id }}</a>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $sale->sold_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($sale->total) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-6 text-center text-ink-soft">Sin compras registradas.</td></tr>
                            @endforelse
                        </tbody>
                        @if ($client->sales->isNotEmpty())
                            <tfoot class="border-t-2 border-ink bg-cream font-bold">
                                <tr>
                                    <td colspan="2" class="px-4 py-2">Total</td>
                                    <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($client->sales->sum('total')) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
