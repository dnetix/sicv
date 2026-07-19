@extends('layouts.print')

@section('title', 'Sellos de contratos')

@section('content')
    <div class="mx-auto max-w-5xl px-6 py-6">
        <form method="GET" action="{{ route('seals.index') }}"
              class="print:hidden mb-6 flex flex-wrap items-end gap-3 rounded-lg bg-cream p-4">
            <span class="text-sm">Nro de sellos: <strong>{{ $contracts->count() }}</strong></span>

            <div>
                <label for="from" class="mb-1 block text-xs font-medium text-ink-soft">Fecha inicial</label>
                <input id="from" name="from" type="date" value="{{ $from }}" class="rounded-md border-line text-sm">
            </div>
            <div>
                <label for="to" class="mb-1 block text-xs font-medium text-ink-soft">Fecha final</label>
                <input id="to" name="to" type="date" value="{{ $to }}" class="rounded-md border-line text-sm">
            </div>

            <button type="submit" class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
                Filtrar
            </button>
        </form>

        <div class="grid grid-cols-3 gap-4">
            @foreach ($contracts as $contract)
                <div class="break-inside-avoid border border-line p-3 text-center">
                    <p class="barcode text-[34px]">{{ $barcodes[$contract->id] }}</p>
                    <h3 class="font-bold">{{ $contract->id }}</h3>
                    <p class="text-xs">{{ $contract->started_at->format('Y-m-d H:i') }}</p>
                    <p class="text-xs">{{ mb_substr($contract->description, 0, 100) }}</p>
                    <p class="text-sm font-medium">{{ money($contract->amount) }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
