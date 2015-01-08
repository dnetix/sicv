<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="{{ public_assets('favicon.ico') }}">

    <title>{{ isset($title) ? $title : 'SICV' }}</title>

    <link href="{{ public_assets('bracket/css/style.default.css') }}" rel="stylesheet">
    <link href="{{ public_assets('bracket/css/jquery.gritter.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ public_assets('bracket/js/html5shiv.js') }}"></script>
    <script src="{{ public_assets('bracket/js/respond.min.js') }}"></script>
    <![endif]-->
    @yield('head')
</head>

<body class="leftpanel-collapsed">
    <section>
    @include('layouts.partials._leftpanel')
        <div class="mainpanel">
            @include('layouts.partials._topbar')
            @yield('pageheader')
            @include('layouts.partials._flash_notification')
            @yield('content')
        </div>
    </section>

    <script src="{{ public_assets('bracket/js/jquery-1.10.2.min.js') }}"></script>
    <script src="{{ public_assets('bracket/js/bootstrap.min.js') }}"></script>
    <script src="{{ public_assets('bracket/js/modernizr.min.js') }}"></script>
    <script src="{{ public_assets('bracket/js/toggles.min.js') }}"></script>
    <script src="{{ public_assets('bracket/js/jquery.gritter.min.js') }}"></script>

    <script src="{{ public_assets('bracket/js/custom.js') }}"></script>
    @yield('js')
</body>
</html>