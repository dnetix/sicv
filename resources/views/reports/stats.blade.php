@extends('layouts.app')

@section('title', 'Estadísticas de contratos')

@php
    $monthNames = [1 => 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    $maxAmount = max(1, $months->max('amount'));

    // Chart geometry (SVG user units).
    $chartWidth = 720;
    $chartHeight = 240;
    $plotTop = 12;
    $plotBottom = $chartHeight - 28;
    $plotHeight = $plotBottom - $plotTop;
    $slot = $chartWidth / 12;
    $barWidth = 34;
@endphp

@section('content')
    <x-report-header :title="'Estadísticas de contratos — '.$year" subtitle="Contratos creados y dinero prestado por mes">
        <form method="GET" action="{{ route('reports.stats') }}" class="flex items-center gap-2">
            <label for="year" class="text-[11px] text-ink-soft">Año</label>
            <input id="year" name="year" type="number" min="2000" max="2100" value="{{ $year }}"
                   class="h-9 w-24 rounded-md border-line text-sm focus:border-accent-deep focus:ring-accent-deep">
            <button type="submit" class="h-9 rounded-md bg-accent px-4 text-xs font-bold text-ink hover:bg-accent-strong">
                Ver
            </button>
        </form>
    </x-report-header>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <figure class="rounded-lg border border-line bg-white p-6 xl:col-span-2">
            <figcaption class="mb-4 text-sm font-medium text-ink">
                Dinero prestado en contratos por mes
            </figcaption>

            <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" role="img" class="w-full"
                 aria-label="Dinero prestado por mes del año {{ $year }}">
                {{-- Recessive gridlines at 0%, 50% and 100% of the max --}}
                @foreach ([0, 0.5, 1] as $fraction)
                    @php $y = $plotBottom - $fraction * $plotHeight; @endphp
                    <line x1="0" y1="{{ $y }}" x2="{{ $chartWidth }}" y2="{{ $y }}"
                          stroke="#e5e1d6" stroke-width="1" />
                    <text x="4" y="{{ $y - 4 }}" font-size="10" fill="#aca597">
                        $ {{ number_format($maxAmount * $fraction / 1_000_000, 1, ',', '.') }}M
                    </text>
                @endforeach

                @foreach ($monthNames as $month => $name)
                    @php
                        $row = $months->get($month);
                        $amount = (int) ($row->amount ?? 0);
                        $barHeight = round($amount / $maxAmount * $plotHeight, 1);
                        $x = ($month - 1) * $slot + ($slot - $barWidth) / 2;
                    @endphp

                    @if ($amount > 0)
                        <rect x="{{ $x }}" y="{{ $plotBottom - $barHeight }}"
                              width="{{ $barWidth }}" height="{{ $barHeight }}"
                              rx="4" ry="4" fill="#ffdc00">
                            <title>{{ $monthNames[$month] }} {{ $year }}: {{ money($amount) }} ({{ $row->contracts ?? 0 }} contratos)</title>
                        </rect>
                        {{-- Square off the bottom corners: bars anchor flat on the baseline --}}
                        <rect x="{{ $x }}" y="{{ max($plotTop, $plotBottom - 4) }}"
                              width="{{ $barWidth }}" height="4" fill="#ffdc00" />
                    @endif

                    <text x="{{ ($month - 1) * $slot + $slot / 2 }}" y="{{ $chartHeight - 10 }}"
                          font-size="11" fill="#8a8272" text-anchor="middle">{{ $name }}</text>
                @endforeach
            </svg>
        </figure>

        <div class="rounded-lg bg-white border border-line">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-line-soft text-sm">
                    <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                        <tr>
                            <th class="px-4 py-2">Mes</th>
                            <th class="px-4 py-2 text-right">Contratos</th>
                            <th class="px-4 py-2 text-right">Total prestado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-faint">
                        @foreach ($monthNames as $month => $name)
                            @php $row = $months->get($month); @endphp
                            <tr @class(['text-ink-faint' => $row === null])>
                                <td class="px-4 py-2">{{ $name }}</td>
                                <td class="px-4 py-2 text-right">{{ $row->contracts ?? 0 }}</td>
                                <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($row->amount ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-ink bg-cream font-bold">
                        <tr>
                            <td class="px-4 py-2">Total {{ $year }}</td>
                            <td class="px-4 py-2 text-right">{{ $months->sum('contracts') }}</td>
                            <td class="px-4 py-2 text-right whitespace-nowrap">{{ money($months->sum('amount')) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
