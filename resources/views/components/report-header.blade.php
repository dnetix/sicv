@props(['title', 'subtitle' => null, 'print' => true])

<div class="mb-5 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h1 class="text-xl font-bold">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-0.5 text-xs text-ink-soft">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="flex flex-wrap items-center gap-3 print:hidden">
        {{ $slot }}

        @if ($print)
            <button type="button" onclick="print()"
                    class="flex h-9 items-center gap-2 rounded-md border border-line bg-white px-4 text-xs hover:bg-cream">
                <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6V2h8v4M4 12H2.5A1.5 1.5 0 0 1 1 10.5v-3A1.5 1.5 0 0 1 2.5 6h11A1.5 1.5 0 0 1 15 7.5v3a1.5 1.5 0 0 1-1.5 1.5H12"/>
                    <rect x="4" y="10" width="8" height="4.5" rx="0.5"/>
                </svg>
                Imprimir
            </button>
        @endif
    </div>
</div>
