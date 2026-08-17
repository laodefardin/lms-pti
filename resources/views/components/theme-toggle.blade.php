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
    <i x-show="$store.theme.dark" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 rotate-90" x-transition:enter-end="opacity-100 rotate-0"
       class="fas fa-sun" style="font-size: 1.1rem; width: 1.1rem; text-align: center;"></i>

    {{-- Moon icon (show when light → click to go dark) --}}
    <i x-show="!$store.theme.dark" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -rotate-90" x-transition:enter-end="opacity-100 rotate-0"
       class="fas fa-moon" style="font-size: 1.1rem; width: 1.1rem; text-align: center;"></i>
</button>
