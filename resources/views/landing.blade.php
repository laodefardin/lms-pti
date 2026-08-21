<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-init="$store.theme.init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Platform belajar digital Prodi Pendidikan Teknologi Informasi, Universitas Sulawesi Barat. Akses materi, kuis, tugas, dan nilai secara online.">
    <title>LMS Pendidikan Teknologi Informasi Unsulbar — Platform Belajar Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-text {
            color: #004b93; /* deep blue in light mode */
        }
        .dark .gradient-text {
            color: #fcb900; /* golden yellow in dark mode */
        }
        .dot-grid {
            background-image: radial-gradient(rgba(0, 75, 147, 0.15) 1px, transparent 1px);
            background-size: 28px 28px;
        }
        .dark .dot-grid {
            background-image: radial-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px);
        }
    </style>
    <script>
        (function() {
            var saved = localStorage.getItem('lms-theme');
            var isDark = saved === 'dark'; // default: light
            document.documentElement.classList.add(isDark ? 'dark' : 'light');
        })();
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
</head>
<body class="bg-white dark:bg-[#0f1117] text-gray-800 dark:text-[#f0f4f8] font-sans antialiased transition-colors duration-200">

    {{-- ── Navbar ───────────────────────────────────────────────── --}}
    <nav class="fixed w-full z-50 top-0 bg-white/80 dark:bg-[#0f1117]/80 backdrop-blur-md border-b border-gray-200 dark:border-white/10" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-2 md:py-3">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-3 text-decoration-none hover:opacity-90 transition">
                    <img
                        x-data
                        :src="document.documentElement.classList.contains('dark') ? '{{ asset('images/logo-dark.png') }}' : '{{ asset('images/logo-landing.png') }}'"
                        x-effect="$el.src = document.documentElement.classList.contains('dark') ? '{{ asset('images/logo-dark.png') }}' : '{{ asset('images/logo-landing.png') }}'"
                        @theme-changed.window="$el.src = document.documentElement.classList.contains('dark') ? '{{ asset('images/logo-dark.png') }}' : '{{ asset('images/logo-landing.png') }}'"
                        alt="Logo LMS PTI"
                        class="h-16 md:h-24 lg:h-32 w-auto object-contain"
                    >
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-8">
                    <div class="flex gap-6">
                        <a href="#fitur" class="text-sm font-medium text-gray-600 dark:text-[#8b95a8] hover:text-teal-600 dark:hover:text-[#f0f4f8] transition-colors">Fitur</a>
                        <a href="#tentang" class="text-sm font-medium text-gray-600 dark:text-[#8b95a8] hover:text-teal-600 dark:hover:text-[#f0f4f8] transition-colors">Tentang</a>
                        <a href="#kontak" class="text-sm font-medium text-gray-600 dark:text-[#8b95a8] hover:text-teal-600 dark:hover:text-[#f0f4f8] transition-colors">Kontak</a>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        @include('components.theme-toggle')
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-[#004b93] hover:bg-[#003770] shadow-[0_4px_14px_rgba(0,75,147,0.4)] transition-all hover:-translate-y-0.5">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk
                        </a>
                    </div>
                </div>

                {{-- Mobile menu button --}}
                <div class="md:hidden flex items-center gap-3">
                    @include('components.theme-toggle')
                    <button @click="open = !open" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white focus:outline-none">
                        <i class="fas fa-bars text-xl" x-show="!open"></i>
                        <i class="fas fa-times text-xl" x-show="open" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-transition class="md:hidden bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800" x-cloak>
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#fitur" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-teal-600 hover:bg-gray-50 dark:hover:bg-gray-800">Fitur</a>
                <a href="#tentang" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-teal-600 hover:bg-gray-50 dark:hover:bg-gray-800">Tentang</a>
                <a href="#kontak" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-teal-600 hover:bg-gray-50 dark:hover:bg-gray-800">Kontak</a>
                <a href="{{ route('login') }}" class="block px-3 py-2 mt-4 rounded-md text-base font-medium text-white bg-teal-600 hover:bg-teal-700 text-center">Masuk ke LMS</a>
            </div>
        </div>
    </nav>

    {{-- ── Hero Section ─────────────────────────────────────────── --}}
    <section class="dot-grid pt-32 pb-16 md:pt-44 md:pb-24 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Left Content --}}
                <div class="fade-in max-w-2xl">
                    <div class="inline-flex items-center gap-2 bg-[#004b93]/5 dark:bg-[#004b93]/20 border border-[#004b93]/20 dark:border-[#004b93]/30 rounded-full px-3 py-1.5 mb-6">
                        <span class="w-2 h-2 bg-[#fcb900] rounded-full shadow-[0_0_8px_rgba(252,185,0,0.6)]"></span>
                        <span class="text-xs font-bold text-[#004b93] dark:text-[#3b8df0] tracking-wide uppercase">Platform Belajar Digital</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-tight mb-5 text-gray-900 dark:text-white">
                        Belajar Lebih Cerdas di Program Studi
                        <span class="gradient-text">Pendidikan Teknologi Informasi</span> 
                    </h1>

                    <p class="text-lg text-gray-600 dark:text-[#8b95a8] leading-relaxed mb-8 max-w-lg">
                        Platform LMS khusus Program Studi Pendidikan Teknologi Informasi — akses materi, kerjakan kuis, kumpul tugas, dan pantau nilaimu kapan saja, di mana saja.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3.5 text-base font-bold text-white rounded-xl bg-[#004b93] hover:bg-[#003770] shadow-[0_8px_24px_rgba(0,75,147,0.4)] transition-all hover:-translate-y-1">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk Sekarang
                        </a>
                        <a href="#fitur" class="inline-flex items-center justify-center px-6 py-3.5 text-base font-bold text-gray-700 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            Lihat Fitur <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>

                    {{-- Mini Stats --}}
                    <div class="flex gap-8 mt-12 pt-8 border-t border-gray-200 dark:border-white/10">
                        @foreach([['100+','Mahasiswa'],['10+','Matakuliah'],['1','Program Studi']] as $stat)
                        <div>
                            <div class="text-3xl font-extrabold text-teal-600 dark:text-teal-400">{{ $stat[0] }}</div>
                            <div class="text-sm font-medium text-gray-500 dark:text-[#8b95a8]">{{ $stat[1] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Right — Floating UI Preview --}}
                <div class="relative hidden lg:block fade-in" style="transition-delay: 0.2s;">
                    {{-- Dashboard preview card --}}
                    <div class="bg-white/80 dark:bg-[#1a1d27]/90 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-6 shadow-2xl shadow-teal-900/10 relative z-10">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            <span class="text-xs font-medium text-gray-400 ml-auto">Dashboard Mahasiswa</span>
                        </div>

                        <div class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                            Selamat Datang, Fardin <i class="fas fa-hand-sparkles text-yellow-400 ml-2"></i>
                        </div>

                        {{-- Stat mini cards --}}
                        <div class="grid grid-cols-3 gap-3 mb-6">
                            @foreach([['fas fa-book','3','Matakuliah','bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400'],['fas fa-check-circle','12','Selesai','bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400'],['fas fa-tasks','2','Tugas','bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400']] as $s)
                            <div class="rounded-xl p-3 text-center {{ $s[3] }}">
                                <div class="text-xl mb-1"><i class="{{ $s[0] }}"></i></div>
                                <div class="text-lg font-bold">{{ $s[1] }}</div>
                                <div class="text-xs font-medium opacity-80">{{ $s[2] }}</div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Course progress items --}}
                        <div class="text-xs font-bold text-gray-500 dark:text-[#8b95a8] uppercase tracking-wider mb-3">LANJUTKAN BELAJAR</div>
                        @foreach([['Pemrograman Web Dasar','70%'],['Basis Data','45%'],['Pemrograman OOP','20%']] as $c)
                        <div class="bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-xl p-3 mb-2">
                            <div class="flex justify-between mb-2 items-center">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $c[0] }}</span>
                                <span class="text-xs font-bold text-teal-600 dark:text-teal-400">{{ $c[1] }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-teal-500 rounded-full" style="width: {{ $c[1] }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Floating badge --}}
                    <div class="absolute -top-6 -right-6 bg-teal-600 rounded-xl px-4 py-2.5 shadow-lg shadow-teal-600/30 text-white font-bold text-sm whitespace-nowrap z-20 flex items-center gap-2 transform rotate-3">
                        <i class="fas fa-bullseye text-yellow-300"></i> TALL Stack
                    </div>

                    {{-- Floating notification --}}
                    <div class="absolute -bottom-6 -left-6 bg-white dark:bg-[#1a1d27]/95 border border-gray-200 dark:border-teal-500/30 rounded-xl p-3 backdrop-blur-md shadow-xl flex items-center gap-3 z-20">
                        <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 flex items-center justify-center text-lg">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-800 dark:text-white">Nilai masuk!</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Kuis Pemrograman Web</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Features Section ─────────────────────────────────────── --}}
    <section id="fitur" class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(0,75,147,0.05)_0%,_transparent_70%)] dark:bg-[radial-gradient(ellipse_at_center,_rgba(0,75,147,0.05)_0%,_transparent_70%)]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-16 fade-in">
                <div class="inline-block bg-[#004b93]/5 dark:bg-[#004b93]/10 border border-[#004b93]/20 dark:border-[#004b93]/20 rounded-full px-4 py-1.5 text-xs font-bold text-[#004b93] dark:text-[#3b8df0] tracking-wide uppercase mb-4">FITUR UNGGULAN</div>
                <h2 class="text-3xl sm:text-4xl font-black mb-4 text-gray-900 dark:text-white">Semua yang kamu butuhkan <span class="gradient-text">dalam satu platform</span></h2>
                <p class="text-lg text-gray-600 dark:text-[#8b95a8] leading-relaxed">Dirancang khusus untuk kebutuhan perkuliahan Program Studi Pendidikan Teknologi Informasi, bukan platform kursus umum.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['fas fa-video','Materi Interaktif','Video kuliah, PDF, artikel, dan live code editor untuk pemrograman.','from-blue-400 to-blue-600','text-[#004b93] dark:text-blue-400'],
                    ['fas fa-tasks','Tugas Online','Upload tugas, pantau deadline, dan lihat feedback dosen langsung.','from-blue-400 to-blue-600','text-[#004b93] dark:text-blue-400'],
                    ['fas fa-bolt','Kuis & Ujian','Kuis dengan timer otomatis, soal diacak, dan nilai langsung muncul.','from-blue-400 to-blue-600','text-[#004b93] dark:text-blue-400'],
                    ['fas fa-chart-bar','Pantau Nilai','Nilai semua komponen: tugas, kuis, UTS, UAS, dan kehadiran.','from-blue-400 to-blue-600','text-[#004b93] dark:text-blue-400'],
                    ['fas fa-comments','Forum Diskusi','Diskusi per matakuliah dan jalur khusus Tanya Dosen.','from-blue-400 to-blue-600','text-[#004b93] dark:text-blue-400'],
                    ['fas fa-trophy','Gamifikasi','Poin, badge, dan leaderboard untuk motivasi belajar.','from-blue-400 to-blue-600','text-[#004b93] dark:text-blue-400'],
                ] as [$icon, $title, $desc, $gradient, $textColor])
                <div class="bg-white dark:bg-[#1a1d27]/70 backdrop-blur-sm border border-gray-100 dark:border-white/5 rounded-2xl p-6 hover:-translate-y-2 hover:border-[#004b93]/30 dark:hover:border-[#004b93]/30 hover:shadow-xl hover:shadow-[#004b93]/5 transition-all duration-300 fade-in group">
                    <div class="w-14 h-14 bg-gray-50 dark:bg-gray-800 rounded-xl flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                        <i class="{{ $icon }} {{ $textColor }}"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-[#f0f4f8]">{{ $title }}</h3>
                    <p class="text-gray-600 dark:text-[#8b95a8] leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── About Section ─────────────────────────────────────────── --}}
    <section id="tentang" class="py-24 bg-white dark:bg-[#0f1117] border-t border-gray-100 dark:border-white/5">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-in">
            <h2 class="text-3xl sm:text-4xl font-black mb-6 text-gray-900 dark:text-white">
                Program Studi <span class="gradient-text">Pendidikan Teknologi Informasi</span>
            </h2>
            <p class="text-lg text-gray-600 dark:text-[#8b95a8] leading-relaxed mb-12">
                Universitas Sulawesi Barat — mencetak pendidik teknologi informasi yang kompeten, adaptif, dan inovatif untuk menghadapi tantangan era digital.
            </p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([['fas fa-graduation-cap','Pendidikan Berkualitas'],['fas fa-laptop-code','Fokus Teknologi'],['fas fa-map-marker-alt','Sulawesi Barat'],['fas fa-globe','Berbasis Digital']] as [$icon, $label])
                <div class="bg-[#004b93]/5 dark:bg-[#004b93]/10 border border-[#004b93]/10 dark:border-[#004b93]/10 rounded-xl p-6 hover:bg-[#004b93]/10 dark:hover:bg-[#004b93]/20 transition-colors">
                    <div class="text-3xl text-[#004b93] dark:text-blue-400 mb-3"><i class="{{ $icon }}"></i></div>
                    <div class="text-sm font-bold text-gray-800 dark:text-[#f0f4f8]">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── CTA Section ─────────────────────────────────────────────── --}}
    <section id="kontak" class="py-24 px-4">
        <div class="relative z-10 max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-[#004b93]/5 to-indigo-50 dark:from-[#004b93]/20 dark:to-indigo-900/20 border border-[#004b93]/20 dark:border-[#004b93]/20 rounded-3xl p-10 md:p-16 text-center fade-in shadow-xl">
            <h2 class="text-3xl md:text-4xl font-black mb-6 text-gray-900 dark:text-white">Siap Untuk Memulai Pembelajaran?</h2>
            <p class="text-lg text-gray-600 dark:text-[#8b95a8] mb-10 max-w-2xl mx-auto">Bergabunglah dengan ribuan mahasiswa lainnya dan rasakan pengalaman belajar yang lebih interaktif dan modern.</p>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white rounded-xl bg-[#004b93] hover:bg-[#003770] shadow-[0_8px_24px_rgba(0,75,147,0.4)] transition-all hover:-translate-y-1">
                Akses LMS Sekarang <i class="fas fa-arrow-right ml-3"></i>
            </a>
        </div>
    </section>

    {{-- ── Footer ─────────────────────────────────────────────────── --}}
    <footer class="py-8 border-t border-gray-200 dark:border-white/5 text-center bg-white dark:bg-[#0f1117]">
        <p class="text-sm text-gray-500 dark:text-gray-500 font-medium">
            &copy; {{ date('Y') }} LMS Pendidikan Teknologi Informasi — Program Studi Pendidikan Teknologi Informasi, Universitas Sulawesi Barat.
        </p>
    </footer>

    @livewireScripts
    <script>
        // Smooth reveal on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-8');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.fade-in').forEach(el => {
            el.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-8');
            observer.observe(el);
        });
    </script>
</body>
</html>
