<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ isset($title) ? $title : 'SICV' }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body class="antialiased">

@if(auth()->user())
    @include('partials._navigation')
@endif

<div class="w-full max-w-8xl mx-auto">
    @yield('content')
</div>

</body>
</html>
