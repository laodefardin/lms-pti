<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-init="$store.theme.init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — LMS Pendidikan Teknologi Informasi Unsulbar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
    <script>
        (function() {
            var saved = localStorage.getItem('lms-theme');
            var isDark = saved !== null ? saved === 'dark' : true;
            document.documentElement.classList.add(isDark ? 'dark' : 'light');
        })();
    </script>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-[#f8f9fa] dark:bg-[#0f1117] transition-colors duration-200 overflow-hidden">
<div class="h-screen w-full flex flex-col md:flex-row overflow-hidden">
    
    <!-- Left Column (Blue) -->
    <div class="hidden md:flex md:w-[45%] lg:w-[45%] xl:w-1/2 bg-[#004b93] relative overflow-hidden text-white flex-col justify-between h-full">
        
        <!-- Background Pattern (Dots) -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <!-- Curved Yellow bottom -->
        <div class="absolute -bottom-[20%] -left-[10%] w-[120%] h-[40%] bg-[#fcb900] rounded-[100%] transform -rotate-6"></div>
        <div class="absolute -bottom-[25%] -left-[10%] w-[120%] h-[40%] bg-[#f6b000] rounded-[100%] transform -rotate-3 opacity-50"></div>

        <div class="relative z-10 p-10 lg:p-14 xl:p-16 flex flex-col h-full">
            <!-- Logo area -->
            <div class="mb-10 inline-block self-start">
                <div class="bg-white p-3 rounded-2xl shadow-lg">
                    <img src="{{ asset('images/logo-lms.png') }}" class="h-10 object-contain" alt="Logo">
                </div>
            </div>

            <!-- Welcome Text -->
            <h1 class="text-3xl lg:text-4xl xl:text-5xl font-bold mb-1">Selamat datang di</h1>
            <h1 class="text-5xl lg:text-6xl xl:text-7xl font-black mb-4">LMS <span class="text-[#fcb900]">PTI</span></h1>
            <h2 class="text-xl lg:text-2xl font-bold mb-1">Pendidikan Teknologi Informasi</h2>
            <h3 class="text-base lg:text-lg text-white/90 mb-6 pb-6 relative">
                Universitas Sulawesi Barat
                <div class="absolute bottom-0 left-0 w-24 h-1 bg-white/20"></div>
            </h3>
            
            <p class="text-sm lg:text-base text-white/90 max-w-md mb-10 leading-relaxed font-light">
                Platform pembelajaran terpadu untuk mendukung proses belajar mengajar yang inovatif, kolaboratif, dan berkualitas.
            </p>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 gap-6 max-w-sm mb-auto">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-black/20 flex items-center justify-center shrink-0 border border-white/10 shadow-inner">
                        <i class="fas fa-graduation-cap text-[#fcb900] text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm lg:text-base">Akses Materi</h4>
                        <p class="text-xs lg:text-sm text-white/70 mt-1">Akses materi pembelajaran kapan saja dan di mana saja.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-black/20 flex items-center justify-center shrink-0 border border-white/10 shadow-inner">
                        <i class="fas fa-users text-[#fcb900] text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm lg:text-base">Kolaboratif</h4>
                        <p class="text-xs lg:text-sm text-white/70 mt-1">Belajar bersama melalui forum, diskusi, dan tugas kelompok.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-black/20 flex items-center justify-center shrink-0 border border-white/10 shadow-inner">
                        <i class="fas fa-chart-bar text-[#fcb900] text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm lg:text-base">Terstruktur</h4>
                        <p class="text-xs lg:text-sm text-white/70 mt-1">Pembelajaran terorganisir dengan jadwal dan progres yang jelas.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-black/20 flex items-center justify-center shrink-0 border border-white/10 shadow-inner">
                        <i class="fas fa-shield-alt text-[#fcb900] text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm lg:text-base">Aman & Terpercaya</h4>
                        <p class="text-xs lg:text-sm text-white/70 mt-1">Sistem aman dengan data terlindungi dan terjamin.</p>
                    </div>
                </div>
            </div>

            <!-- Laptop CSS Illustration (Bottom Right) -->
            <div class="hidden xl:block absolute bottom-12 right-12 opacity-95 transition-transform hover:scale-105 duration-500">
                <div class="relative w-64 h-64 flex items-end justify-center drop-shadow-2xl">
                    <!-- Books -->
                    <div class="absolute bottom-0 w-48 h-6 bg-[#fcb900] rounded-sm transform -rotate-2 z-10 border-b-4 border-[#d97706] shadow-lg"></div>
                    <div class="absolute bottom-4 w-44 h-5 bg-white rounded-sm transform rotate-1 z-20 border-b-4 border-gray-300 shadow-md"></div>
                    <div class="absolute bottom-8 w-46 h-6 bg-[#0f766e] rounded-sm transform -rotate-1 z-30 border-b-4 border-[#0f5c56] shadow-md"></div>
                    <!-- Laptop Screen -->
                    <div class="absolute bottom-16 z-40 text-[#1e293b] flex flex-col items-center">
                        <div class="w-56 h-36 bg-[#1e293b] rounded-t-lg border-4 border-[#1e293b] flex items-center justify-center relative overflow-hidden shadow-[0_0_15px_rgba(0,0,0,0.5)]">
                            <!-- Screen Content -->
                            <div class="w-full h-full bg-[#004b93] flex flex-col items-center justify-center relative">
                                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 8px 8px;"></div>
                                <div class="text-white font-bold text-sm mb-3 z-10">LMS <span class="text-[#fcb900]">PTI</span></div>
                                <div class="flex gap-2 z-10">
                                    <div class="w-8 h-8 bg-white/20 rounded shadow flex items-center justify-center"><i class="fas fa-graduation-cap text-white text-xs"></i></div>
                                    <div class="w-8 h-8 bg-white/20 rounded shadow flex items-center justify-center"><i class="fas fa-book-open text-white text-xs"></i></div>
                                    <div class="w-8 h-8 bg-white/20 rounded shadow flex items-center justify-center"><i class="fas fa-chart-line text-white text-xs"></i></div>
                                </div>
                            </div>
                        </div>
                        <!-- Laptop Base -->
                        <div class="w-64 h-3 bg-[#94a3b8] rounded-b-xl shadow-lg relative">
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-[#475569] rounded-b-sm"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column (White/Form) -->
    <div class="w-full md:w-[55%] lg:w-[55%] xl:w-1/2 flex flex-col justify-between p-4 md:p-6 lg:p-8 relative h-full overflow-y-auto md:overflow-hidden">
        <!-- Right Column Dot Pattern -->
        <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.02]" style="background-image: radial-gradient(black 2px, transparent 2px); background-size: 24px 24px;"></div>

        <!-- Header (Mobile Safe) -->
        <div class="flex justify-between items-center w-full mb-6 md:absolute md:top-4 md:left-0 md:w-full md:px-6 md:mb-0 z-20">
            <!-- Back Button -->
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors" title="Kembali ke Beranda">
                <i class="fas fa-arrow-left"></i>
            </a>

            <!-- Theme Toggle -->
            <div>
                @include('components.theme-toggle')
            </div>
        </div>

        <div class="flex-1 flex items-center justify-center w-full z-10 py-2 md:py-8">
            <div class="w-full max-w-[440px] bg-white dark:bg-[#1a1d27] rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.2)] p-6 md:p-8 border border-gray-100 dark:border-gray-800 relative overflow-hidden">
                
                <!-- Subtle glow effect behind form -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-32 bg-gradient-to-b from-[#004b93]/5 to-transparent dark:from-[#3b8df0]/5 pointer-events-none"></div>

                <!-- Form Header -->
                <div class="text-center mb-8 relative z-10">
                    <div class="w-16 h-16 bg-[#004b93]/5 dark:bg-[#004b93]/20 rounded-full flex items-center justify-center mx-auto mb-4 border border-[#004b93]/10 dark:border-[#3b8df0]/20">
                        <i class="fas fa-graduation-cap text-3xl text-[#004b93] dark:text-[#3b8df0]"></i>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white">Login ke <span class="text-[#004b93] dark:text-[#3b8df0]">LMS</span> <span class="text-[#fcb900]">PTI</span></h2>
                    <p class="text-sm text-gray-500 mt-2">Masuk untuk melanjutkan ke akun Anda</p>
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-600 mb-5 relative z-10">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-600 mb-5 relative z-10">
                        <strong class="font-bold">⚠️ Login gagal:</strong>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" id="loginForm" class="relative z-10">
                    @csrf

                    {{-- Username/Email --}}
                    <div class="mb-5">
                        <label class="block text-[13px] font-bold text-gray-700 dark:text-gray-300 mb-2" for="email">Username atau Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 w-12 flex items-center justify-center pointer-events-none text-gray-400 border-r border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-[#151821] rounded-l-xl">
                                <i class="far fa-user"></i>
                            </div>
                            <input id="email" name="email" type="email" class="w-full bg-white dark:bg-[#151821] border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 pl-14 pr-4 text-sm text-gray-900 dark:text-white focus:border-[#004b93] focus:ring focus:ring-[#004b93]/20 transition-all outline-none"
                                   value="{{ old('email') }}"
                                   placeholder="Masukkan username atau email Anda"
                                   required autofocus autocomplete="username">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-5">
                        <label class="block text-[13px] font-bold text-gray-700 dark:text-gray-300 mb-2" for="password">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 w-12 flex items-center justify-center pointer-events-none text-gray-400 border-r border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-[#151821] rounded-l-xl">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input id="password" name="password" type="password" class="w-full bg-white dark:bg-[#151821] border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 pl-14 pr-10 text-sm text-gray-900 dark:text-white focus:border-[#004b93] focus:ring focus:ring-[#004b93]/20 transition-all outline-none"
                                   placeholder="Masukkan password Anda"
                                   required autocomplete="current-password">
                            <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" onclick="togglePassword()">
                                <i id="eye-icon" class="far fa-eye-slash"></i>
                            </button>
                        </div>
                        
                        <div class="text-right mt-2.5">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[13px] font-bold text-[#004b93] dark:text-[#3b8df0] hover:underline">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="submitBtn" class="w-full bg-[#004b93] hover:bg-[#003770] text-white font-bold py-3.5 px-4 rounded-xl shadow-[0_4px_14px_rgba(0,75,147,0.3)] hover:shadow-[0_6px_20px_rgba(0,75,147,0.4)] transition-all flex items-center justify-center gap-2 outline-none mt-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1" id="btn-icon-svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span id="btn-text">Login</span>
                        <span id="btn-loading" style="display:none;" class="flex items-center justify-center gap-2">
                            <i class="fas fa-circle-notch fa-spin"></i> Memproses...
                        </span>
                    </button>
                </form>

                <div class="flex items-center gap-4 my-7 relative z-10">
                    <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                    <span class="text-xs text-gray-400 font-medium">atau masuk dengan</span>
                    <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                </div>

                {{-- SSO Buttons --}}
                <div class="grid grid-cols-2 gap-4 relative z-10">
                    <button type="button" class="flex items-center justify-center gap-2 bg-white dark:bg-[#151821] border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 shadow-sm transition-colors cursor-not-allowed opacity-80" title="Belum tersedia">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                        Google
                    </button>
                    <button type="button" class="flex items-center justify-center gap-2 bg-white dark:bg-[#151821] border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 shadow-sm transition-colors cursor-not-allowed opacity-80" title="Belum tersedia">
                        <svg class="w-5 h-5" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#f35325" d="M1 1h9v9H1z"/>
                            <path fill="#81bc06" d="M11 1h9v9h-9z"/>
                            <path fill="#05a6f0" d="M1 11h9v9H1z"/>
                            <path fill="#ffba08" d="M11 11h9v9h-9z"/>
                        </svg>
                        Microsoft
                    </button>
                </div>

                <div class="text-center mt-8 text-[13px] text-gray-600 dark:text-gray-400 relative z-10">
                    Belum punya akun? <a href="#" class="font-bold text-[#004b93] dark:text-[#3b8df0] hover:underline">Hubungi Administrator PTI</a>
                </div>

                {{-- Quick Login Hints (dev only) --}}
                @if(config('app.debug'))
                <div class="flex flex-wrap justify-center gap-2 mt-6 pt-6 border-t border-gray-100 dark:border-gray-800 relative z-10">
                    <div class="w-full text-center text-xs text-gray-400 mb-1">Dev Login:</div>
                    <button class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 rounded px-2 py-1 text-xs text-gray-700 dark:text-gray-300" onclick="fillLogin('mahasiswa@pti.unsulbar.ac.id','mhs123')">Mahasiswa</button>
                    <button class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 rounded px-2 py-1 text-xs text-gray-700 dark:text-gray-300" onclick="fillLogin('dosen@pti.unsulbar.ac.id','dosen123')">Dosen</button>
                    <button class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 rounded px-2 py-1 text-xs text-gray-700 dark:text-gray-300" onclick="fillLogin('admin@pti.unsulbar.ac.id','admin123')">Admin</button>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="z-10 mt-8 md:mt-auto pt-4 md:pt-6 border-t border-gray-200 dark:border-gray-800 text-center pb-4 md:pb-0">
            <div class="flex flex-wrap md:flex-nowrap gap-4 md:gap-6 items-center justify-center lg:justify-between text-xs text-gray-500 dark:text-gray-400 max-w-3xl mx-auto w-full px-2">
                <div class="flex items-center gap-2 text-left">
                    <i class="fas fa-globe text-lg md:text-2xl text-[#004b93] dark:text-[#3b8df0]"></i>
                    <div class="hidden md:block">
                        <div class="font-bold text-gray-900 dark:text-gray-200 text-sm">LMS PTI</div>
                        <div class="text-[11px]">Pendidikan Teknologi Informasi<br>Universitas Sulawesi Barat</div>
                    </div>
                    <div class="block md:hidden font-bold">LMS PTI</div>
                </div>
                <div class="flex items-center gap-2 text-left">
                    <i class="far fa-envelope text-lg md:text-2xl text-[#004b93] dark:text-[#3b8df0]"></i>
                    <div class="hidden md:block">
                        <div class="font-bold text-gray-900 dark:text-gray-200 text-sm">Email</div>
                        <div class="text-[11px]">pti@unsulbar.ac.id</div>
                    </div>
                    <div class="block md:hidden font-bold">Email</div>
                </div>
                <div class="flex items-center gap-2 text-left">
                    <i class="far fa-question-circle text-lg md:text-2xl text-[#004b93] dark:text-[#3b8df0]"></i>
                    <div class="hidden md:block">
                        <div class="font-bold text-gray-900 dark:text-gray-200 text-sm">Bantuan</div>
                        <div class="text-[11px]">help.lmspti@unsulbar.ac.id</div>
                    </div>
                    <div class="block md:hidden font-bold">Bantuan</div>
                </div>
            </div>
            <div class="text-center text-[10px] md:text-[11px] text-gray-400 mt-6 md:mt-8 mb-2">
                &copy; {{ date('Y') }} LMS PTI - Universitas Sulawesi Barat. All rights reserved.
            </div>
        </div>
    </div>
</div>

@livewireScripts
<script>
function togglePassword() {
    const pw = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (pw.type === 'password') {
        pw.type = 'text';
        icon.className = 'far fa-eye';
    } else {
        pw.type = 'password';
        icon.className = 'far fa-eye-slash';
    }
}

function fillLogin(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}

document.getElementById('loginForm').addEventListener('submit', function() {
    document.getElementById('btn-text').style.display = 'none';
    document.getElementById('btn-icon-svg').style.display = 'none';
    document.getElementById('btn-loading').style.display = 'flex';
    document.getElementById('submitBtn').disabled = true;
});
</script>
</body>
</html>
