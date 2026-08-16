{{--
    Theme Toggle Button
    Usage: @include('components.theme-toggle')
    Requires Alpine.js $store('theme') to be initialized in app.js
--}}
<button
    x-data
    @click="$store.theme.toggle()"
    class="theme-toggle"
    :title="$store.theme.dark ? 'Ganti ke Mode Terang' : 'Ganti ke Mode Gelap'"
    aria-label="Toggle theme"
    id="theme-toggle-btn">

    {{-- Sun icon (show when dark → click to go light) --}}
    <svg x-show="$store.theme.dark" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 rotate-90" x-transition:enter-end="opacity-100 rotate-0"
         width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="5"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
    </svg>

    {{-- Moon icon (show when light → click to go dark) --}}
    <svg x-show="!$store.theme.dark" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -rotate-90" x-transition:enter-end="opacity-100 rotate-0"
         width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
    </svg>
</button>
