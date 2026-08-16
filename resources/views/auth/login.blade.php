<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — LMS PTI Unsulbar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-bg {
            min-height: 100vh;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(20,167,160,0.18) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 80%, rgba(139,92,246,0.12) 0%, transparent 55%),
                #0f1117;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background dots */
        .auth-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 30px 30px;
            pointer-events: none;
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

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: rgba(26,29,39,0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 2.5rem;
            position: relative;
            z-index: 1;
            box-shadow: 0 40px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(20,167,160,0.05);
        }

        .auth-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: #f0f4f8;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            outline: none;
        }
        .auth-input:focus {
            border-color: #14a7a0;
            background: rgba(20,167,160,0.05);
            box-shadow: 0 0 0 3px rgba(20,167,160,0.1);
        }
        .auth-input::placeholder { color: #5a6478; }

        .auth-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #8b95a8;
            margin-bottom: 0.5rem;
        }

        .auth-btn {
            width: 100%;
            background: linear-gradient(135deg, #14a7a0, #0e8a84);
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.85rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 6px 20px rgba(20,167,160,0.4);
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.01em;
        }
        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(20,167,160,0.55);
        }
        .auth-btn:active { transform: translateY(0); }

        .input-group { position: relative; }
        .input-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #5a6478;
            pointer-events: none;
        }
        .input-icon + .auth-input { padding-left: 2.75rem; }

        .toggle-pw {
            position: absolute;
            right: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #5a6478;
            padding: 0;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: #8b95a8; }

        .divider {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            margin: 1.25rem 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.07);
        }
        .divider span { font-size: 0.72rem; color: #5a6478; white-space: nowrap; }

        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            color: #f87171;
            margin-bottom: 1.25rem;
        }
        .alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.25);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            color: #4ade80;
            margin-bottom: 1.25rem;
        }

        .hint-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(20,167,160,0.1);
            border: 1px solid rgba(20,167,160,0.2);
            border-radius: 8px;
            padding: 0.4rem 0.75rem;
            font-size: 0.72rem;
            color: #14a7a0;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .hint-badge:hover { background: rgba(20,167,160,0.18); }
    </style>
</head>
<body>
<div class="auth-bg">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="auth-card fade-in">

        {{-- Logo --}}
        <div style="text-align:center; margin-bottom:2rem;">
            <div style="width:56px; height:56px; background:linear-gradient(135deg,#14a7a0,#0e8a84); border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; box-shadow:0 8px 24px rgba(20,167,160,0.45);">
                <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h1 style="font-size:1.4rem; font-weight:800; color:#f0f4f8; margin-bottom:0.25rem;">Selamat Datang Kembali</h1>
            <p style="font-size:0.82rem; color:#8b95a8;">LMS PTI — Universitas Sulawesi Barat</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert-error">
                <strong>⚠️ Login gagal:</strong>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            {{-- Email --}}
            <div style="margin-bottom:1rem;">
                <label class="auth-label" for="email">Email / NIM / NIDN</label>
                <div class="input-group">
                    <svg class="input-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input id="email" name="email" type="email" class="auth-input"
                           value="{{ old('email') }}"
                           placeholder="email@pti.unsulbar.ac.id"
                           required autofocus autocomplete="username">
                </div>
            </div>

            {{-- Password --}}
            <div style="margin-bottom:1.25rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                    <label class="auth-label" for="password" style="margin-bottom:0;">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size:0.75rem; color:#14a7a0; text-decoration:none;" onmouseover="this.style.color='#1bbdb5'" onmouseout="this.style.color='#14a7a0'">
                            Lupa password?
                        </a>
                    @endif
                </div>
                <div class="input-group">
                    <svg class="input-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input id="password" name="password" type="password" class="auth-input"
                           placeholder="••••••••"
                           required autocomplete="current-password"
                           style="padding-right:3rem;">
                    <button type="button" class="toggle-pw" onclick="togglePassword()" title="Tampilkan/sembunyikan password">
                        <svg id="eye-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Remember Me --}}
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.5rem;">
                <input id="remember_me" name="remember" type="checkbox"
                       style="width:16px; height:16px; border-radius:4px; accent-color:#14a7a0; cursor:pointer;">
                <label for="remember_me" style="font-size:0.8rem; color:#8b95a8; cursor:pointer;">Ingat saya selama 30 hari</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="auth-btn" id="submitBtn">
                <span id="btn-text">Masuk ke LMS</span>
                <span id="btn-loading" style="display:none;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline; animation:spin 1s linear infinite;">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                    Memproses...
                </span>
            </button>
        </form>

        <div class="divider"><span>atau masuk cepat sebagai</span></div>

        {{-- Quick Login Hints (dev only) --}}
        @if(config('app.debug'))
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap; justify-content:center;">
            <button class="hint-badge" onclick="fillLogin('mahasiswa@pti.unsulbar.ac.id','mhs123')">👨‍🎓 Mahasiswa</button>
            <button class="hint-badge" onclick="fillLogin('dosen@pti.unsulbar.ac.id','dosen123')">👨‍🏫 Dosen</button>
            <button class="hint-badge" onclick="fillLogin('admin@pti.unsulbar.ac.id','admin123')">⚙️ Admin</button>
        </div>
        @endif

        {{-- Back to landing --}}
        <div style="text-align:center; margin-top:1.5rem;">
            <a href="{{ route('home') }}" style="font-size:0.78rem; color:#5a6478; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; transition:color 0.2s;" onmouseover="this.style.color='#8b95a8'" onmouseout="this.style.color='#5a6478'">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>

<script>
function togglePassword() {
    const pw = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (pw.type === 'password') {
        pw.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
    } else {
        pw.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
}

function fillLogin(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
    document.getElementById('email').focus();
}

document.getElementById('loginForm').addEventListener('submit', function() {
    document.getElementById('btn-text').style.display = 'none';
    document.getElementById('btn-loading').style.display = 'inline-flex';
    document.getElementById('submitBtn').disabled = true;
});
</script>
</body>
</html>
