<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SICV') — {{ config('app.name') }}</title>
    {{-- print.css also carries the Code 128 barcode webfont used on-screen. --}}
    @vite(['resources/css/app.css', 'resources/css/print.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-canvas text-ink antialiased print:bg-white">
    @auth
        <div class="flex min-h-screen">
            <aside class="sticky top-0 flex h-screen w-56 shrink-0 flex-col overflow-y-auto border-r border-line bg-white py-5 print:hidden">
                <a href="{{ route('dashboard') }}" class="mb-4 flex items-center gap-2.5 px-5">
                    @if ($company->logo_path)
                        <img src="{{ route('company.logo') }}" alt="" class="h-8 w-8 rounded-full border border-line-soft bg-white object-contain p-0.5">
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-accent-tint text-sm font-bold text-accent-deep">
                            {{ mb_substr($company->name, 0, 1) }}
                        </span>
                    @endif
                    <span class="text-sm font-bold">{{ $company->name }}</span>
                </a>

                <nav class="flex flex-1 flex-col gap-0.5 text-[13px]">
                    @foreach ($navigation as $section)
                        @php($sectionActive = request()->routeIs(...explode('|', $section['match'])))

                        @if (isset($section['children']))
                            <div x-data="{ open: @js($sectionActive) }">
                                <button type="button" @click="open = !open"
                                        class="flex w-full items-center justify-between px-5 py-2.5 text-left
                                               {{ $sectionActive
                                                   ? 'border-r-[3px] border-accent-deep bg-accent-tint font-bold text-accent-deep'
                                                   : 'text-ink-nav hover:bg-cream' }}">
                                    {{ $section['label'] }}
                                    <svg class="h-3 w-3 transition-transform" :class="open && 'rotate-180'" viewBox="0 0 12 12" fill="none">
                                        <path d="M3 4.5 6 7.5 9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <div x-show="open" x-cloak class="py-0.5">
                                    @foreach ($section['children'] as $item)
                                        <a href="{{ route($item['route']) }}"
                                           class="block py-1.5 pl-9 pr-5 text-xs
                                                  {{ request()->routeIs($item['route'])
                                                      ? 'font-bold text-accent-deep'
                                                      : 'text-ink-soft hover:text-ink' }}">
                                            {{ $item['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ route($section['route']) }}"
                               class="block px-5 py-2.5
                                      {{ $sectionActive
                                          ? 'border-r-[3px] border-accent-deep bg-accent-tint font-bold text-accent-deep'
                                          : 'text-ink-nav hover:bg-cream' }}">
                                {{ $section['label'] }}
                            </a>
                        @endif
                    @endforeach
                </nav>

                <div class="mt-4 flex items-center gap-2.5 border-t border-line-soft px-5 pt-4">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-line-soft text-xs font-bold text-ink-nav">
                        {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1 text-xs leading-tight">
                        <a href="{{ route('profile.edit') }}" class="block truncate font-bold text-ink hover:text-accent-deep"
                           title="Mis datos">{{ auth()->user()->name }}</a>
                        <span class="text-ink-soft">{{ auth()->user()->role->label() }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-ink-faint hover:text-ink" title="Cerrar sesión" aria-label="Cerrar sesión">
                            <svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 14H3.5A1.5 1.5 0 0 1 2 12.5v-9A1.5 1.5 0 0 1 3.5 2H6"/>
                                <path d="M10.5 11 14 8l-3.5-3M14 8H6"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col">
                <div class="flex items-center gap-4 px-6 pt-6 print:hidden">
                    <form method="GET" action="{{ route('quick-search') }}" class="min-w-0 flex-1">
                        <label class="flex h-11 items-center gap-2.5 rounded-full border border-line bg-white px-4.5 text-[13px] focus-within:border-accent-deep">
                            <svg class="h-4 w-4 shrink-0 text-ink-faint" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                <circle cx="7" cy="7" r="4.5"/>
                                <path d="m13.5 13.5-3.2-3.2"/>
                            </svg>
                            <input type="search" name="q" placeholder="Búsqueda rápida — NC123, CL79456123, 4581, o nombre de cliente"
                                   title="NC{nota} / CL{documento} / número de contrato / nombre"
                                   class="w-full border-0 bg-transparent p-0 text-[13px] placeholder-ink-faint focus:ring-0">
                        </label>
                    </form>
                    <a href="{{ route('contracts.create') }}"
                       class="flex h-11 shrink-0 items-center rounded-full bg-accent px-5 text-[13px] font-bold text-ink hover:bg-accent-strong">
                        + Nuevo contrato
                    </a>
                </div>

                <main class="flex-1 px-6 py-6 print:p-0">
                    <x-flash />

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <main class="min-h-screen">
            <x-flash />

            @yield('content')
        </main>
    @endauth
</body>
</html>
