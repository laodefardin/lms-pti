import './bootstrap';

// Anti-Flash IIFE
(function () {
    var saved = localStorage.getItem('lms-theme');
    var isDark = saved === 'dark';
    if (isDark) {
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
    } else {
        document.documentElement.classList.add('light');
        document.documentElement.classList.remove('dark');
    }
})();

document.addEventListener('livewire:navigated', () => {
    var saved = localStorage.getItem('lms-theme');
    var isDark = saved === 'dark';
    if (isDark) {
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
    } else {
        document.documentElement.classList.add('light');
        document.documentElement.classList.remove('dark');
    }
});
