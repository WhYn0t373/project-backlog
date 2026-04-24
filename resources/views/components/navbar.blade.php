<nav role="navigation" aria-label="Main navigation" tabindex="0" class="bg-gray-800 text-white">
    <ul role="menubar" class="flex space-x-4">
        <li role="menuitem">
            <a href="{{ route('home') }}"
               class="block px-3 py-2"
               {{ Request::routeIs('home') ? 'aria-current="page"' : '' }}>
                Home
            </a>
        </li>
        <li role="menuitem">
            <a href="{{ route('about') }}"
               class="block px-3 py-2"
               {{ Request::routeIs('about') ? 'aria-current="page"' : '' }}>
                About
            </a>
        </li>
        <li role="menuitem">
            <a href="{{ route('contact') }}"
               class="block px-3 py-2"
               {{ Request::routeIs('contact') ? 'aria-current="page"' : '' }}>
                Contact
            </a>
        </li>
    </ul>
</nav>