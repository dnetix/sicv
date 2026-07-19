@extends('layouts.app')

@section('title', 'Abonos')

@section('content')
    <x-report-header title="Abonos"
                     :subtitle="count($extensions).' abonos · '.money($extensions->sum('amount')).' recibidos en el rango'" />

    <x-report-filter :range="$range" />

    <div class="rounded-lg bg-white border border-line">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line-soft text-sm">
                <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                    <tr>
                        <th class="px-3 py-2">Fecha</th>
                        <th class="px-3 py-2">Contrato</th>
                        <th class="px-3 py-2">Cliente</th>
                        <th class="px-3 py-2">Artículo</th>
                        <th class="px-3 py-2 text-right">Valor contrato</th>
                        <th class="px-3 py-2 text-right">Abonado</th>
                        <th class="px-3 py-2 text-right">Meses</th>
                        <th class="px-3 py-2">Registrado por</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line-faint">
                    @forelse ($extensions as $extension)
                        <tr class="hover:bg-cream">
                            <td class="px-3 py-2 whitespace-nowrap">{{ $extension->paid_at->format('d/m/Y H:i') }}</td>
                            <td class="px-3 py-2 font-medium">
                                <a href="{{ route('contracts.show', $extension->contract_id) }}" class="font-bold text-accent-deep hover:underline">{{ $extension->contract_id }}</a>
                            </td>
                            <td class="max-w-[12rem] truncate px-3 py-2">{{ $extension->contract->client->name }}</td>
                            <td class="max-w-[14rem] truncate px-3 py-2">{{ $extension->contract->description }}</td>
                            <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($extension->contract->amount) }}</td>
                            <td class="px-3 py-2 text-right whitespace-nowrap">{{ money($extension->amount) }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($extension->months, 2, ',', '.') }}</td>
                            <td class="px-3 py-2 whitespace-nowrap">{{ $extension->user->name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-6 text-center text-ink-soft">Sin abonos en el rango seleccionado.</td></tr>
                    @endforelse
                </tbody>
                @if ($extensions->isNotEmpty())
                    <tfoot class="border-t-2 border-ink bg-cream font-bold">
                        <tr>
                            <td colspan="5" class="px-3 py-2">Total ({{ $extensions->count() }})</td>
                            <td class="px-3 py-2 text-right">{{ money($extensions->sum('amount')) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
