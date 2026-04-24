<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Main app stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Conversion page specific stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/conversion.css') }}">
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    <!-- Main app script -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Conversion page specific script -->
    <script src="{{ asset('js/conversion.js') }}"></script>
</body>
</html>