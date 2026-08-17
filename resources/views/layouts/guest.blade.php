<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-init="$store.theme.init()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Theme Script (FOUC Prevention) -->
        <script>
            (function() {
                var saved = localStorage.getItem('lms-theme');
                var isDark = saved !== null ? saved === 'dark' : true;
                document.documentElement.classList.add(isDark ? 'dark' : 'light');
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased bg-gray-100 dark:bg-[#0f1117] transition-colors duration-200">
        
        <!-- Theme Toggle Button Floating -->
        <div class="absolute top-4 right-4">
            @include('components.theme-toggle')
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex flex-col items-center gap-2 text-decoration-none hover:opacity-90 transition">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center shadow-[0_4px_12px_rgba(20,167,160,0.4)]">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <div class="text-center mt-2">
                        <div class="text-xl font-bold leading-tight text-gray-900 dark:text-[#f0f4f8]">LMS Pendidikan Teknologi Informasi</div>
                        <div class="text-xs text-gray-500 dark:text-[#8b95a8]">Unsulbar</div>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-white dark:bg-gray-800 shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                {{ $slot }}
            </div>
        </div>

        @livewireScripts
    </body>
</html>
