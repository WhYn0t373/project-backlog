<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MyApp')</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @stack('styles')
</head>
<body class="antialiased">
    <x-navbar />
    <main role="main" aria-label="Main Content" class="p-4">
        @yield('content')
    </main>
    @stack('scripts')
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>