<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-init="$store.theme.init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — LMS Pendidikan Teknologi Informasi Unsulbar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
    <style>
        .auth-bg {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            background-color: #f3f4f6; /* light: bg-gray-100 */
        }
        .dark .auth-bg {
            background-color: #0f1117; /* dark bg */
            background-image:
                radial-gradient(ellipse at 20% 20%, rgba(20,167,160,0.18) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 80%, rgba(139,92,246,0.12) 0%, transparent 55%);
        }
        .light .auth-bg {
            background-image:
                radial-gradient(ellipse at 20% 20%, rgba(20,167,160,0.08) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 80%, rgba(139,92,246,0.05) 0%, transparent 55%);
        }

        /* Animated background dots */
        .auth-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(20, 167, 160, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            pointer-events: none;
        }
        .dark .auth-bg::before {
            background-image: radial-gradient(rgba(255,255,255,0.04) 1px, transparent 1px);
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            animation: float 8s ease-in-out infinite;
        }
        .orb-1 {
            width: 300px; height: 300px;
            background: rgba(20,167,160,0.12);
            top: -80px; left: -80px;
        }
        .orb-2 {
            width: 250px; height: 250px;
            background: rgba(139,92,246,0.1);
            bottom: -60px; right: -60px;
            animation-delay: -4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50%       { transform: translateY(-20px) scale(1.05); }
        }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
    <!-- Theme Script (FOUC Prevention) -->
    <script>
        (function() {
            var saved = localStorage.getItem('lms-theme');
            var isDark = saved !== null ? saved === 'dark' : true;
            document.documentElement.classList.add(isDark ? 'dark' : 'light');
        })();
    </script>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 transition-colors duration-200">
<div class="auth-bg">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    
    <!-- Theme Toggle Floating -->
    <div class="absolute top-6 right-6 z-50">
        @include('components.theme-toggle')
    </div>

    <div class="w-full max-w-[420px] bg-white/90 dark:bg-[#1a1d27]/85 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-10 relative z-10 shadow-2xl dark:shadow-[0_40px_80px_rgba(0,0,0,0.5),0_0_0_1px_rgba(20,167,160,0.05)] transition-colors duration-300">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-[0_8px_24px_rgba(20,167,160,0.45)]">
                <i class="fas fa-graduation-cap text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-[#f0f4f8] mb-1">Selamat Datang</h1>
            <p class="text-sm text-gray-500 dark:text-[#8b95a8]">LMS Pendidikan Teknologi Informasi — Universitas Sulawesi Barat</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/25 rounded-xl px-4 py-3 text-sm text-green-600 dark:text-green-400 mb-5">
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/25 rounded-xl px-4 py-3 text-sm text-red-600 dark:text-red-400 mb-5">
                <strong class="font-bold">⚠️ Login gagal:</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            {{-- Email --}}
            <div class="mb-4 relative">
                <label class="block text-xs font-bold text-gray-600 dark:text-[#8b95a8] mb-2" for="email">Email / NIM / NIDN</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-[#5a6478]">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input id="email" name="email" type="email" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl py-3 pl-10 pr-4 text-sm text-gray-900 dark:text-[#f0f4f8] focus:border-teal-500 focus:ring focus:ring-teal-500/20 dark:focus:bg-teal-500/5 transition-all outline-none placeholder-gray-400 dark:placeholder-[#5a6478]"
                           value="{{ old('email') }}"
                           placeholder="email@pti.unsulbar.ac.id"
                           required autofocus autocomplete="username">
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-6 relative">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-bold text-gray-600 dark:text-[#8b95a8]" for="password">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 transition-colors">
                            Lupa password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-[#5a6478]">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input id="password" name="password" type="password" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl py-3 pl-10 pr-10 text-sm text-gray-900 dark:text-[#f0f4f8] focus:border-teal-500 focus:ring focus:ring-teal-500/20 dark:focus:bg-teal-500/5 transition-all outline-none placeholder-gray-400 dark:placeholder-[#5a6478]"
                           placeholder="••••••••"
                           required autocomplete="current-password">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-[#5a6478] hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none transition-colors" onclick="togglePassword()" title="Tampilkan/sembunyikan password">
                        <i id="eye-icon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-2 mb-6">
                <input id="remember_me" name="remember" type="checkbox"
                       class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-teal-600 focus:ring-teal-500 bg-gray-50 dark:bg-gray-700 cursor-pointer transition-colors">
                <label for="remember_me" class="text-sm text-gray-600 dark:text-[#8b95a8] cursor-pointer select-none">Ingat saya selama 30 hari</label>
            </div>

            {{-- Submit --}}
            <button type="submit" id="submitBtn" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-[0_6px_20px_rgba(20,167,160,0.3)] hover:shadow-[0_10px_28px_rgba(20,167,160,0.45)] hover:-translate-y-0.5 transition-all outline-none">
                <span id="btn-text">Masuk ke LMS</span>
                <span id="btn-loading" style="display:none;" class="flex items-center justify-center gap-2">
                    <i class="fas fa-circle-notch fa-spin"></i> Memproses...
                </span>
            </button>
        </form>

        <div class="flex items-center gap-4 my-6">
            <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
            <span class="text-xs text-gray-400 dark:text-[#5a6478] whitespace-nowrap">atau masuk cepat sebagai</span>
            <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
        </div>

        {{-- Quick Login Hints (dev only) --}}
        @if(config('app.debug'))
        <div class="flex flex-wrap justify-center gap-2">
            <button class="inline-flex items-center gap-1.5 bg-teal-50 dark:bg-teal-500/10 hover:bg-teal-100 dark:hover:bg-teal-500/20 border border-teal-200 dark:border-teal-500/20 rounded-lg px-3 py-1.5 text-xs font-semibold text-teal-700 dark:text-teal-400 transition-colors" onclick="fillLogin('mahasiswa@pti.unsulbar.ac.id','mhs123')"><i class="fas fa-user-graduate"></i> Mahasiswa</button>
            <button class="inline-flex items-center gap-1.5 bg-teal-50 dark:bg-teal-500/10 hover:bg-teal-100 dark:hover:bg-teal-500/20 border border-teal-200 dark:border-teal-500/20 rounded-lg px-3 py-1.5 text-xs font-semibold text-teal-700 dark:text-teal-400 transition-colors" onclick="fillLogin('dosen@pti.unsulbar.ac.id','dosen123')"><i class="fas fa-chalkboard-teacher"></i> Dosen</button>
            <button class="inline-flex items-center gap-1.5 bg-teal-50 dark:bg-teal-500/10 hover:bg-teal-100 dark:hover:bg-teal-500/20 border border-teal-200 dark:border-teal-500/20 rounded-lg px-3 py-1.5 text-xs font-semibold text-teal-700 dark:text-teal-400 transition-colors" onclick="fillLogin('admin@pti.unsulbar.ac.id','admin123')"><i class="fas fa-cog"></i> Admin</button>
        </div>
        @endif

        {{-- Back to landing --}}
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-[#5a6478] hover:text-gray-800 dark:hover:text-[#8b95a8] transition-colors">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Beranda
            </a>
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
        icon.className = 'fas fa-eye-slash';
    } else {
        pw.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function fillLogin(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
    document.getElementById('email').focus();
}

document.getElementById('loginForm').addEventListener('submit', function() {
    document.getElementById('btn-text').style.display = 'none';
    document.getElementById('btn-loading').style.display = 'flex';
    document.getElementById('submitBtn').disabled = true;
});
</script>
</body>
</html>
