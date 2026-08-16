import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// ─── Theme Store ──────────────────────────────────────────────────
Alpine.store('theme', {
    dark: true,

    init() {
        // Baca preferensi dari localStorage, fallback ke dark
        const saved = localStorage.getItem('lms-theme');
        this.dark = saved !== null ? saved === 'dark' : true;
        this.apply();
    },

    toggle() {
        this.dark = !this.dark;
        localStorage.setItem('lms-theme', this.dark ? 'dark' : 'light');
        this.apply();
    },

    apply() {
        if (this.dark) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        } else {
            document.documentElement.classList.add('light');
            document.documentElement.classList.remove('dark');
        }
    }
});

// Terapkan tema SEBELUM Alpine start (cegah flash)
(function () {
    const saved = localStorage.getItem('lms-theme');
    const isDark = saved !== null ? saved === 'dark' : true;
    document.documentElement.classList.add(isDark ? 'dark' : 'light');
})();

Alpine.start();
