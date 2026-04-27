```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- CSS bundle --}}
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    {{-- JavaScript bundle --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body>
    <header>
        {{-- Example logo image with lazy loading and responsive sources --}}
        <img src="{{ asset('img/logo.webp') }}"
             loading="lazy"
             srcset="{{ asset('img/logo-sm.webp') }} 480w,
                      {{ asset('img/logo-md.webp') }} 800w,
                      {{ asset('img/logo-lg.webp') }} 1200w"
             alt="App Logo">
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        {{-- Example footer image --}}
        <img src="{{ asset('img/footer.webp') }}"
             loading="lazy"
             srcset="{{ asset('img/footer-sm.webp') }} 480w,
                      {{ asset('img/footer-md.webp') }} 800w,
                      {{ asset('img/footer-lg.webp') }} 1200w"
             alt="Footer Image">
    </footer>
</body>
</html>
```