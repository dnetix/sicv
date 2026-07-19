@extends('layouts.app')

@section('title', 'Reporte financiero')

@section('content')
    <x-report-header title="Reporte financiero"
                     :subtitle="$range->from->format('d/m/Y').($range->from->isSameDay($range->to) ? '' : ' al '.$range->to->format('d/m/Y'))" />

    <x-report-filter :range="$range" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-lg bg-white border border-line">
            <h2 class="border-b border-line-soft px-6 py-4 font-medium text-red-700">Salidas de dinero</h2>
            <dl class="divide-y divide-line-faint text-sm">
                <div class="flex justify-between px-6 py-3">
                    <dt>Prestado en contratos nuevos</dt>
                    <dd class="font-medium">{{ money($out['loans']) }}</dd>
                </div>
                <div class="flex justify-between px-6 py-3">
                    <dt>Gastos</dt>
                    <dd class="font-medium">{{ money($out['expenses']) }}</dd>
                </div>
                <div class="flex justify-between px-6 py-3">
                    <dt>Compras de artículos para el almacén</dt>
                    <dd class="font-medium">{{ money($out['purchases']) }}</dd>
                </div>
                <div class="flex justify-between bg-red-50 px-6 py-3 font-semibold text-red-800">
                    <dt>Total salidas</dt>
                    <dd>{{ money(array_sum($out)) }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-lg bg-white border border-line">
            <h2 class="border-b border-line-soft px-6 py-4 font-medium text-emerald-700">Entradas de dinero</h2>
            <dl class="divide-y divide-line-faint text-sm">
                <div class="flex justify-between px-6 py-3">
                    <dt>Abonos a contratos</dt>
                    <dd class="font-medium">{{ money($in['extensions']) }}</dd>
                </div>
                <div class="flex justify-between px-6 py-3">
                    <dt>Ventas del almacén</dt>
                    <dd class="font-medium">{{ money($in['sales']) }}</dd>
                </div>
                <div class="flex justify-between px-6 py-3">
                    <dt>
                        Cancelaciones de contratos
                        <span class="block text-xs text-ink-soft">
                            Capital {{ money($redeemedCapital) }} —
                            utilidad {{ money($in['redemptions'] - $redeemedCapital) }}
                        </span>
                    </dt>
                    <dd class="font-medium">{{ money($in['redemptions']) }}</dd>
                </div>
                <div class="flex justify-between bg-emerald-50 px-6 py-3 font-semibold text-emerald-800">
                    <dt>Total entradas</dt>
                    <dd>{{ money(array_sum($in)) }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-5 rounded-lg bg-accent px-6 py-4 text-ink">
        <div class="flex justify-between text-sm font-bold">
            <span>Balance del período</span>
            <span>{{ money(array_sum($in) - array_sum($out)) }}</span>
        </div>
    </div>
@endsection
