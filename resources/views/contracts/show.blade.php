@extends('layouts.app')

@section('title', "Contrato No. {$contract->id}")

@php
    use App\Enums\ClientNoteSeverity;
    use App\Enums\ContractStatus;

    $isOperable = $contract->isActive() && ! $contract->isQueued();
    $payoff = $contract->isActive() ? (int) round($contract->payoffAmount()) : null;
    $client = $contract->client;

    // Timeline: creation, every extension, then the closing event.
    $history = collect([[
        'at' => $contract->started_at,
        'text' => 'Contrato creado por <b>'.e($contract->user->name).'</b>',
    ]]);

    foreach ($contract->extensions as $extension) {
        $history->push([
            'at' => $extension->paid_at,
            'text' => 'Abono de <b>'.money($extension->amount).'</b> registrado por <b>'.e($extension->user->name).'</b>',
        ]);
    }

    $closing = match ($contract->status) {
        ContractStatus::Redeemed => 'El cliente canceló pagando <b>'.money($contract->settled_amount).'</b> y retiró el artículo',
        ContractStatus::InStore => 'El artículo pasó al almacén',
        ContractStatus::Sold => 'El artículo pasó al almacén y fue vendido',
        ContractStatus::Scrapped => 'El artículo fue chatarrizado',
        ContractStatus::LegalHold => 'El contrato quedó en problema legal',
        default => null,
    };

    if ($closing !== null && $contract->ended_at !== null) {
        $history->push(['at' => $contract->ended_at, 'text' => $closing]);
    }

    if ($contract->status === ContractStatus::Voided && $contract->void !== null) {
        $history->push([
            'at' => $contract->void->voided_at,
            'text' => 'Contrato anulado por <b>'.e($contract->void->user?->name ?? '—').'</b>',
        ]);
    }

    $history = $history->sortBy('at')->values();
@endphp

@section('content')
    <div x-data="{ modal: null }">
        {{-- Header: id + status pills + barcode --}}
        <div class="mb-5 flex flex-wrap items-start justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <h1 class="text-[22px] font-bold">Contrato #{{ $contract->id }}</h1>

                    <span @class([
                        'rounded-full border px-3 py-0.5 text-xs font-bold',
                        'border-[#8fb886] bg-[#e3f0e0] text-[#3e6e35]' => $contract->status === ContractStatus::Active,
                        'border-sky-300 bg-sky-50 text-sky-800' => $contract->status === ContractStatus::Redeemed,
                        'border-amber-300 bg-amber-50 text-amber-800' => in_array($contract->status, [ContractStatus::InStore, ContractStatus::Sold]),
                        'border-line bg-line-soft text-ink-nav' => in_array($contract->status, [ContractStatus::Scrapped, ContractStatus::LegalHold, ContractStatus::Voided]),
                    ])>
                        {{ $contract->status->label() }}
                    </span>

                    @if ($contract->isExpired())
                        <span class="rounded-full border border-danger-line bg-danger-pale px-3 py-0.5 text-xs font-bold text-danger-deep">
                            Vencido
                        </span>
                    @elseif ($contract->isActive() && $contract->dueDate()->isToday())
                        <span class="rounded-full border border-accent-line bg-accent-pale px-3 py-0.5 text-xs font-bold text-accent-deep">
                            Vence hoy
                        </span>
                    @endif
                </div>
                <p class="mt-0.5 text-xs text-ink-soft">
                    Creado {{ $contract->started_at->format('d/m/Y H:i') }} · por {{ $contract->user->name }}
                    @if ($contract->isActive()) · vence {{ $contract->dueDate()->format('d/m/Y') }} @endif
                </p>
            </div>

            <div class="hidden flex-col items-center sm:flex">
                <span class="barcode text-[34px] leading-none">{{ $barcode }}</span>
                <span class="font-mono text-[10px] text-ink-soft">{{ $contract->id }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-5 lg:flex-row">
            <div class="min-w-0 flex-1 space-y-3.5">

                {{-- Client + notes --}}
                <div class="space-y-2.5 rounded-lg border border-line bg-white p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-2.5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-accent-tint text-xs font-bold text-accent-deep">
                                {{ mb_strtoupper(mb_substr($client->name, 0, 1)) }}
                            </span>
                            <div class="min-w-0 text-xs">
                                <p class="truncate text-[13px] font-bold">
                                    {{ $client->name }}
                                    @if ($client->notes->isNotEmpty())
                                        <span @class([
                                            'text-danger-deep' => $client->notes->contains(fn ($note) => $note->severity === ClientNoteSeverity::Alert),
                                            'text-accent-deep' => ! $client->notes->contains(fn ($note) => $note->severity === ClientNoteSeverity::Alert),
                                        ])>⚑ {{ $client->notes->count() }} {{ Str::plural('nota', $client->notes->count()) }}</span>
                                    @endif
                                </p>
                                <p class="text-ink-soft">
                                    {{ $client->document_type }} {{ $client->document_number }}@if ($client->phone ?: $client->mobile) · Tel. {{ $client->phone ?: $client->mobile }}@endif @if ($client->address) · {{ $client->address }}@if ($client->city), {{ $client->city }}@endif @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('clients.show', $client) }}" class="shrink-0 text-xs font-bold text-accent-deep hover:underline">
                            Ver ficha →
                        </a>
                    </div>

                    @foreach ($client->notes as $note)
                        <div @class([
                            'flex items-start justify-between gap-3 rounded-md border px-3 py-2 text-xs',
                            'border-accent-line bg-accent-pale' => $note->severity === ClientNoteSeverity::Warning,
                            'border-danger-line bg-danger-pale text-danger-deep' => $note->severity === ClientNoteSeverity::Alert,
                        ])>
                            <p>⚑ {{ $note->body }}</p>
                            <p class="whitespace-nowrap text-[11px] text-ink-soft">{{ $note->user->name }} · {{ $note->created_at->format('d/m/Y') }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Item --}}
                <div class="space-y-2 rounded-lg border border-line bg-white p-4">
                    <h2 class="text-[13px] font-bold">Artículo</h2>
                    <p class="whitespace-pre-line text-[13px]">{{ $contract->description }}</p>
                    <div class="flex flex-wrap gap-6 text-xs text-ink-soft">
                        <span>Tipo: <b class="text-ink">{{ $contract->itemType->name }}</b></span>
                        @if ($contract->weight_grams)
                            <span>Peso: <b class="text-ink">{{ rtrim(rtrim(number_format($contract->weight_grams, 2, ',', '.'), '0'), ',') }} g</b></span>
                        @endif
                        <a href="{{ route('seals.index', ['from' => $contract->started_at->toDateString(), 'to' => $contract->started_at->toDateString()]) }}"
                           class="text-accent-deep hover:underline">Imprimir sello</a>
                    </div>
                </div>

                {{-- Money strip --}}
                <div class="flex flex-col gap-4 rounded-lg border border-line bg-white p-4 sm:flex-row sm:divide-x sm:divide-line-soft">
                    <div class="flex-1 space-y-1 sm:pr-4">
                        <p class="text-[11px] uppercase tracking-wide text-ink-soft">Préstamo</p>
                        <p class="text-xl font-bold">{{ money($contract->amount) }}</p>
                        <p class="text-[11px] text-ink-soft">
                            {{ rtrim(rtrim(number_format($contract->monthly_rate, 2, '.', ''), '0'), '.') }}% mensual · {{ $contract->term_months }} meses
                        </p>
                    </div>
                    <div class="flex-1 space-y-1 sm:px-4">
                        <p class="text-[11px] uppercase tracking-wide text-ink-soft">Interés mensual</p>
                        <p class="text-xl font-bold">{{ money($contract->monthlyInterest()) }}</p>
                        <p class="text-[11px] text-ink-soft">meses transcurridos: {{ $contract->monthsElapsed() }}</p>
                    </div>
                    <div class="flex-1 space-y-1 sm:px-4">
                        <p class="text-[11px] uppercase tracking-wide text-ink-soft">Abonado</p>
                        <p class="text-xl font-bold">{{ money($contract->extensions->sum('amount')) }}</p>
                        <p class="text-[11px] text-ink-soft">= {{ number_format($contract->extendedMonths(), 2, ',', '.') }} meses de prórroga</p>
                    </div>
                    @if ($contract->isActive())
                        <div class="flex-1 space-y-1 sm:pl-4">
                            <p class="text-[11px] uppercase tracking-wide text-ink-soft">A pagar hoy</p>
                            <p class="text-xl font-bold text-accent-deep">{{ money($payoff) }}</p>
                            <p class="text-[11px] text-ink-soft">
                                ({{ $contract->monthsElapsed() }} − {{ number_format($contract->extendedMonths(), 2, ',', '.') }})
                                × {{ number_format($contract->monthlyInterest(), 0, ',', '.') }}
                                + {{ number_format($contract->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Status-specific panel --}}
                @if ($contract->status === ContractStatus::Redeemed)
                    <div class="rounded-lg border border-sky-200 bg-sky-50 p-4 text-sm text-sky-900">
                        El cliente canceló y retiró el artículo el
                        <strong>{{ $contract->ended_at?->format('d/m/Y H:i') }}</strong>
                        pagando <strong>{{ money($contract->settled_amount) }}</strong>.
                    </div>
                @elseif ($contract->status === ContractStatus::Voided)
                    <div class="rounded-lg border border-danger-line bg-danger-pale p-4 text-sm text-danger-deep">
                        <p>Contrato anulado el <strong>{{ $contract->void?->voided_at?->format('d/m/Y') }}</strong>
                            por {{ $contract->void?->user?->name }}.</p>
                        <p class="mt-1">Motivo: {{ $contract->void?->reason }}</p>
                        @if ($contract->void?->original_amount)
                            <p class="mt-1">Valor original: <strong>{{ money($contract->void->original_amount) }}</strong></p>
                        @endif
                    </div>
                @elseif ($contract->status === ContractStatus::Sold && $saleInfo)
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                        Vendido en el almacén el <strong>{{ $saleInfo->sale->sold_at->format('d/m/Y') }}</strong>
                        por <strong>{{ money($saleInfo->price) }}</strong>
                        a {{ $saleInfo->sale->client->name }} (Nota de cobro NC{{ $saleInfo->sale_id }}).
                    </div>
                @elseif (in_array($contract->status, [ContractStatus::InStore, ContractStatus::Scrapped, ContractStatus::LegalHold]))
                    <div class="rounded-lg border border-line-soft bg-cream p-4 text-sm text-ink-nav">
                        Salió del estado activo el <strong>{{ $contract->ended_at?->format('d/m/Y H:i') }}</strong>.
                    </div>
                @elseif ($contract->isQueued())
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                        <p>Este contrato está en la <strong>pre-saca</strong>: las operaciones se habilitan al removerlo.</p>
                        <form method="POST" action="{{ route('contracts.queue.remove', $contract) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-md bg-amber-600 px-3 py-2 font-medium text-white hover:bg-amber-500">
                                Remover de la pre-saca
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Extensions --}}
                <div class="rounded-lg border border-line bg-white">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-line-soft px-4 py-3">
                        <h2 class="text-[13px] font-bold">Abonos / prórrogas ({{ $contract->extensions->count() }})</h2>
                        @if ($contract->extensions->isNotEmpty())
                            <span class="text-xs text-ink-soft">
                                total: {{ money($contract->extensions->sum('amount')) }} ·
                                {{ number_format($contract->extendedMonths(), 2, ',', '.') }} meses
                            </span>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-line-soft text-sm">
                            <thead class="text-left text-[11px] uppercase tracking-wide text-ink-soft">
                                <tr>
                                    <th class="px-4 py-2 font-normal">Fecha</th>
                                    <th class="px-4 py-2 text-right font-normal">Monto</th>
                                    <th class="px-4 py-2 text-right font-normal">Meses comprados</th>
                                    <th class="px-4 py-2 text-right font-normal">Operador</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line-faint text-xs">
                                @forelse ($contract->extensions as $extension)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $extension->paid_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-2 text-right whitespace-nowrap font-bold">{{ money($extension->amount) }}</td>
                                        <td class="px-4 py-2 text-right">{{ number_format($extension->months, 4, ',', '.') }}</td>
                                        <td class="px-4 py-2 text-right text-ink-soft">{{ $extension->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-5 text-center text-ink-soft">Sin abonos registrados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="border-t border-line-faint px-4 py-2.5 text-[11px] text-ink-faint">
                        meses comprados = abono ÷ interés mensual · las fracciones cuentan completas para el saldo,
                        y mueven la fecha de vencimiento solo al completar un mes
                    </p>
                </div>

                {{-- History --}}
                <div class="space-y-2 rounded-lg border border-line bg-white p-4">
                    <h2 class="text-[13px] font-bold">Historial</h2>
                    @foreach ($history as $event)
                        <div class="flex items-baseline gap-3 text-xs">
                            <span class="w-28 shrink-0 text-ink-soft">{{ $event['at']->format('d/m/Y H:i') }}</span>
                            <span>{!! $event['text'] !!}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Operations sidebar --}}
            <aside class="w-full shrink-0 space-y-3 self-start lg:w-[280px]">
                <p class="text-[11px] uppercase tracking-wide text-ink-soft">Operaciones</p>

                @if ($contract->isActive())
                    <div class="space-y-2.5 rounded-lg border border-accent-line bg-white p-3.5"
                         x-data="abonoPreview({
                             monthly: {{ $contract->monthlyInterest() }},
                             startedAt: '{{ $contract->started_at->toDateString() }}',
                             term: {{ $contract->term_months }},
                             extended: {{ $contract->extendedMonths() }},
                         })">
                        <h2 class="text-[13px] font-bold">Registrar abono</h2>
                        <form method="POST" action="{{ route('contracts.extend', $contract) }}" class="space-y-2.5">
                            @csrf
                            <input name="amount" type="number" min="1" required x-model="amount"
                                   placeholder="Valor en pesos"
                                   class="w-full rounded-md border-line text-sm font-bold focus:border-accent-deep focus:ring-accent-deep">
                            @error('amount')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-[11px] text-ink-soft" x-show="monthsBought !== null" x-cloak>
                                <b x-text="monthsBoughtLabel + ' meses'"></b> · nuevo vencimiento: <span x-text="newDueDate"></span>
                            </p>
                            <button type="submit"
                                    class="h-10 w-full rounded-md bg-accent text-[13px] font-bold text-ink hover:bg-accent-strong">
                                + Registrar abono
                            </button>
                        </form>
                    </div>
                @endif

                @if ($isOperable)
                    <div class="space-y-2.5 rounded-lg border border-line bg-white p-3.5">
                        <h2 class="text-[13px] font-bold">Cliente cancela</h2>
                        <p class="text-[11px] text-ink-soft">
                            Sugerido: <b>{{ money($payoff) }}</b> — editable; la diferencia queda auditada
                        </p>
                        <form method="POST" action="{{ route('contracts.redeem', $contract) }}" class="space-y-2.5">
                            @csrf
                            <input name="amount" type="number" min="0" required value="{{ $payoff }}"
                                   class="w-full rounded-md border-line text-sm font-bold focus:border-accent-deep focus:ring-accent-deep">
                            <button type="submit"
                                    class="h-9 w-full rounded-md bg-ink text-xs font-bold text-white hover:bg-night">
                                Cancelar y devolver artículo
                            </button>
                        </form>
                    </div>

                    <button type="button" @click="modal = 'forfeit'"
                            class="h-10 w-full rounded-md border border-line bg-white text-[13px] text-ink-nav hover:bg-cream">
                        Mover al almacén
                    </button>

                    <button type="button" @click="modal = 'void'"
                            class="h-10 w-full rounded-md border border-danger-line bg-white text-[13px] text-danger-line hover:bg-danger-pale">
                        Anular contrato…
                    </button>
                    <p class="text-[11px] text-ink-faint">Anular exige un motivo; el monto original queda registrado</p>
                @endif

                <div class="h-px bg-line-soft"></div>

                {{-- Reprints from this page are always watermarked DUPLICADO, as in the legacy app. --}}
                <a href="{{ route('contracts.print', [$contract, 'copy' => 1]) }}"
                   class="flex h-10 w-full items-center justify-center gap-2 rounded-md border border-line bg-white text-[13px] text-ink-nav hover:bg-cream">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6V2h8v4M4 12H2.5A1.5 1.5 0 0 1 1 10.5v-3A1.5 1.5 0 0 1 2.5 6h11A1.5 1.5 0 0 1 15 7.5v3a1.5 1.5 0 0 1-1.5 1.5H12"/>
                        <rect x="4" y="10" width="8" height="4.5" rx="0.5"/>
                    </svg>
                    Reimprimir contrato
                </a>
                <p class="text-center text-[11px] text-ink-faint">la reimpresión lleva marca de agua DUPLICADO</p>
            </aside>
        </div>

        @if ($isOperable)
            {{-- Forfeit modal --}}
            <x-modal name="forfeit" title="Mover el artículo al almacén">
                <form method="POST" action="{{ route('contracts.forfeit', $contract) }}" class="space-y-4">
                    @csrf
                    <p class="text-sm text-ink-soft">
                        El artículo quedará disponible para la venta. Precio sugerido (valor de cancelación):
                        <strong>{{ money($payoff) }}</strong>.
                    </p>
                    <div>
                        <label class="mb-1 block text-sm font-medium" for="forfeit-price">Precio de venta</label>
                        <input id="forfeit-price" name="price" type="number" min="0" required value="{{ $payoff }}"
                               class="w-full rounded-md border-line shadow-sm">
                        <p class="mt-1 text-xs text-ink-soft">Si modifica el valor sugerido, la diferencia quedará registrada para revisión.</p>
                    </div>
                    <button type="submit" class="w-full rounded-md bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                        Mover al almacén
                    </button>
                </form>
            </x-modal>

            {{-- Void modal --}}
            <x-modal name="void" title="Anular contrato">
                <form method="POST" action="{{ route('contracts.void', $contract) }}" class="space-y-4">
                    @csrf
                    <p class="text-sm text-ink-soft">
                        El contrato quedará anulado y su valor no contará en los informes.
                        El valor original ({{ money($contract->amount) }}) quedará registrado.
                    </p>
                    <div>
                        <label class="mb-1 block text-sm font-medium" for="void-reason">Motivo</label>
                        <textarea id="void-reason" name="reason" rows="3" required minlength="3"
                                  class="w-full rounded-md border-line shadow-sm"></textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">
                        Anular contrato
                    </button>
                </form>
            </x-modal>
        @endif
    </div>
@endsection
