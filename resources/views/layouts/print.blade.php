<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/css/print.css'])
</head>
<body class="bg-white text-ink antialiased">
    <div class="print:hidden fixed right-4 top-4 flex gap-2">
        <button onclick="print()"
                class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink shadow hover:bg-accent-strong">
            Imprimir
        </button>
        <a href="{{ route('dashboard') }}"
           class="rounded-md border border-line bg-white px-4 py-2 text-sm shadow hover:bg-cream">
            Finalizar
        </a>
    </div>

    @yield('content')
</body>
</html>
