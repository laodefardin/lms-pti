{{--
    Theme Toggle Button
    Usage: @include('components.theme-toggle')
--}}
<button
    x-data="{
        dark: document.documentElement.classList.contains('dark'),
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('lms-theme', this.dark ? 'dark' : 'light');
            if (this.dark) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            } else {
                document.documentElement.classList.add('light');
                document.documentElement.classList.remove('dark');
            }
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { dark: this.dark } }));
        }
    }"
    @click="toggle()"
    class="theme-toggle relative"
    :title="dark ? 'Ganti ke Mode Terang' : 'Ganti ke Mode Gelap'"
    aria-label="Toggle theme"
    id="theme-toggle-btn">

    {{-- Sun icon (show when dark → click to go light) --}}
    <i x-show="dark" style="position: absolute;" 
       x-transition:enter="transition ease-out duration-200" 
       x-transition:enter-start="opacity-0 rotate-90" 
       x-transition:enter-end="opacity-100 rotate-0"
       class="fas fa-sun text-[1.1rem] w-[1.1rem] text-center"></i>

    {{-- Moon icon (show when light → click to go dark) --}}
    <i x-show="!dark" style="position: absolute;" 
       x-transition:enter="transition ease-out duration-200" 
       x-transition:enter-start="opacity-0 -rotate-90" 
       x-transition:enter-end="opacity-100 rotate-0"
       class="fas fa-moon text-[1.1rem] w-[1.1rem] text-center"></i>
</button>
