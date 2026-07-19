@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <div class="mb-5">
        <h1 class="text-xl font-bold">Bienvenido, {{ auth()->user()->name }}</h1>
        <p class="mt-0.5 text-sm text-ink-soft">{{ now()->translatedFormat('l, j \d\e F \d\e Y') }}</p>
    </div>

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="flex flex-col gap-1.5 rounded-lg border border-line bg-white p-4">
            <span class="text-[11px] uppercase tracking-wide text-ink-soft">Entradas hoy</span>
            <span class="text-2xl font-bold">{{ money($cashFlow->totalIn()) }}</span>
            <span class="text-[11px] text-ink-soft">abonos + ventas + cancelaciones</span>
        </div>
        <div class="flex flex-col gap-1.5 rounded-lg border border-line bg-white p-4">
            <span class="text-[11px] uppercase tracking-wide text-ink-soft">Salidas hoy</span>
            <span class="text-2xl font-bold">{{ money($cashFlow->totalOut()) }}</span>
            <span class="text-[11px] text-ink-soft">préstamos + gastos + compras</span>
        </div>
        <div class="flex flex-col gap-1.5 rounded-lg border border-line bg-white p-4">
            <span class="text-[11px] uppercase tracking-wide text-ink-soft">Balance del día</span>
            <span class="text-2xl font-bold {{ $cashFlow->balance() >= 0 ? 'text-accent-deep' : 'text-red-700' }}">
                {{ $cashFlow->balance() >= 0 ? '+' : '−' }} {{ money(abs($cashFlow->balance())) }}
            </span>
            <span class="text-[11px] text-ink-soft">entradas − salidas</span>
        </div>
        <div class="flex flex-col gap-1.5 rounded-lg border border-accent-line bg-accent-pale p-4">
            <span class="text-[11px] uppercase tracking-wide text-ink-soft">Contratos vencidos</span>
            <span class="text-2xl font-bold">{{ number_format($expiredCount) }}</span>
            <a href="{{ route('reports.expired') }}" class="text-[11px] text-accent-deep hover:underline">ver reporte →</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
        <div class="space-y-4 lg:col-span-3">
        <div class="rounded-lg border border-line bg-white">
            <div class="flex items-baseline justify-between border-b border-line-soft px-4 py-3">
                <h2 class="text-[13px] font-bold">Buscar cliente</h2>
                <span class="text-[11px] text-ink-soft">documento o nombre · clic para crear contrato</span>
            </div>
            <div class="p-4">
                <x-client-search mode="contract" />
            </div>
        </div>

        <div class="rounded-lg border border-line bg-white">
            <h2 class="border-b border-line-soft px-4 py-3 text-[13px] font-bold">
                Contratos de hoy ({{ $todayContracts->count() }})
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-line-faint text-sm">
                    <thead class="text-left text-[11px] uppercase tracking-wide text-ink-soft">
                        <tr>
                            <th class="px-4 py-2 font-normal">#</th>
                            <th class="px-4 py-2 font-normal">Cliente</th>
                            <th class="px-4 py-2 font-normal">Artículo</th>
                            <th class="px-4 py-2 text-right font-normal">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-faint text-xs">
                        @forelse ($todayContracts as $contract)
                            <tr class="hover:bg-cream">
                                <td class="px-4 py-2.5">
                                    <a href="{{ route('contracts.show', $contract) }}" class="font-bold text-accent-deep hover:underline">{{ $contract->id }}</a>
                                </td>
                                <td class="max-w-[14rem] truncate px-4 py-2.5">{{ $contract->client->name }}</td>
                                <td class="max-w-[16rem] truncate px-4 py-2.5">{{ $contract->description }}</td>
                                <td class="px-4 py-2.5 text-right whitespace-nowrap">{{ money($contract->amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-ink-soft">Aún no hay contratos hoy.</td></tr>
                        @endforelse
                    </tbody>
                    @if ($todayContracts->isNotEmpty())
                        <tfoot class="bg-cream text-xs font-bold">
                            <tr class="border-t-2 border-ink">
                                <td colspan="3" class="px-4 py-2.5">Total prestado hoy</td>
                                <td class="px-4 py-2.5 text-right">{{ money($todayContracts->sum('amount')) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
        </div>

        <div class="rounded-lg border border-line bg-white lg:col-span-2">
            <h2 class="border-b border-line-soft px-4 py-3 text-[13px] font-bold">Abonos de hoy</h2>
            <div class="px-4 py-1">
                @forelse ($todayExtensions as $extension)
                    <div class="flex items-center justify-between gap-3 border-b border-line-faint py-2 text-xs last:border-b-0">
                        <div class="flex min-w-0 items-center gap-2.5">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-accent-tint text-[10px] font-bold text-accent-deep">
                                {{ mb_strtoupper(mb_substr($extension->contract->client->name, 0, 1)) }}
                            </span>
                            <div class="min-w-0">
                                <a href="{{ route('contracts.show', $extension->contract) }}"
                                   class="block truncate font-bold text-ink hover:text-accent-deep">
                                    {{ $extension->contract->client->name }}
                                </a>
                                <span class="text-ink-soft">
                                    Contrato {{ $extension->contract_id }} · {{ $extension->paid_at->format('g:i a') }}
                                </span>
                            </div>
                        </div>
                        <span class="whitespace-nowrap font-bold">{{ money($extension->amount) }}</span>
                    </div>
                @empty
                    <p class="py-5 text-center text-xs text-ink-soft">Aún no hay abonos hoy.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
