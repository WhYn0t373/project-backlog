<nav class="navbar" x-data="navbar()">
    <div class="navbar-brand">
        <a href="{{ route('home') }}">MyApp</a>
    </div>
    <div class="navbar-menu desktop-menu">
        <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('about') }}" class="navbar-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
        <a href="{{ route('dashboard') }}" class="navbar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
    </div>
    <div class="navbar-user">
        @auth
            <span class="user-role">Role: {{ Auth::user()->role }}</span>
        @endauth
    </div>
    <button class="hamburger" type="button" x-on:click="toggle()" aria-label="Toggle menu" aria-controls="mobile-menu" :aria-expanded="open">
        <svg width="24" height="24" viewBox="0 0 24 24">
            <path :class="{ hidden: open }" d="M4 6h16M4 12h16M4 18h16"/>
            <path :class="{ hidden: !open }" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <div id="mobile-menu" class="mobile-menu" x-show="open" @click.away="open = false" x-transition x-cloak>
        <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('about') }}" class="navbar-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
        <a href="{{ route('dashboard') }}" class="navbar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
    </div>
</nav>