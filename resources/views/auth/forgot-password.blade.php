<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — LMS Pendidikan Teknologi Informasi Unsulbar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-bg {
            min-height: 100vh;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(20,167,160,0.18) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 80%, rgba(139,92,246,0.12) 0%, transparent 55%),
                #0f1117;
            display: flex; align-items: center; justify-content: center; padding: 2rem;
            position: relative; overflow: hidden;
        }
        .auth-bg::before {
            content: ''; position: absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 30px 30px; pointer-events: none;
        }
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; animation: float 8s ease-in-out infinite; }
        .orb-1 { width: 300px; height: 300px; background: rgba(20,167,160,0.12); top:-80px; left:-80px; }
        .orb-2 { width: 250px; height: 250px; background: rgba(139,92,246,0.1); bottom:-60px; right:-60px; animation-delay:-4s; }
        @keyframes float { 0%,100%{transform:translateY(0) scale(1);} 50%{transform:translateY(-20px) scale(1.05);} }
        .auth-card { width:100%; max-width:420px; background:rgba(26,29,39,0.85); backdrop-filter:blur(24px); border:1px solid rgba(255,255,255,0.08); border-radius:24px; padding:2.5rem; position:relative; z-index:1; box-shadow:0 40px 80px rgba(0,0,0,0.5); }
        .auth-input { width:100%; background:rgba(255,255,255,0.05); border:1.5px solid rgba(255,255,255,0.1); border-radius:12px; padding:0.75rem 0.75rem 0.75rem 2.75rem; color:#f0f4f8; font-size:0.9rem; font-family:'Inter',sans-serif; transition:all 0.2s; outline:none; }
        .auth-input:focus { border-color:#004b93; background:rgba(0,75,147,0.05); box-shadow:0 0 0 3px rgba(0,75,147,0.1); }
        .auth-input::placeholder { color:#5a6478; }
        .auth-btn { width:100%; background:#004b93; color:white; font-weight:700; font-size:0.95rem; padding:0.85rem; border-radius:12px; border:none; cursor:pointer; transition:all 0.25s; box-shadow:0 6px 20px rgba(0,75,147,0.4); font-family:'Inter',sans-serif; }
        .auth-btn:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(20,167,160,0.55); }
        .input-group { position:relative; }
        .input-icon { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#5a6478; pointer-events:none; }
        .alert-success { background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.25); border-radius:10px; padding:0.75rem 1rem; font-size:0.8rem; color:#4ade80; margin-bottom:1.25rem; }
    </style>
</head>
<body>
<div class="auth-bg">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="auth-card fade-in">
        {{-- Logo --}}
        <div style="text-align:center; margin-bottom:2rem;">
            <div style="width:56px; height:56px; background:linear-gradient(135deg,#f59e0b,#d97706); border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; box-shadow:0 8px 24px rgba(245,158,11,0.4);">
                <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h1 style="font-size:1.4rem; font-weight:800; color:#f0f4f8; margin-bottom:0.25rem;">Lupa Password?</h1>
            <p style="font-size:0.82rem; color:#8b95a8;">Masukkan email dan kami kirimkan link reset</p>
        </div>

        @if (session('status'))
            <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#8b95a8; margin-bottom:0.5rem;">Email</label>
                <div class="input-group">
                    <svg class="input-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input name="email" type="email" class="auth-input" value="{{ old('email') }}" placeholder="email@pti.unsulbar.ac.id" required autofocus>
                </div>
                @error('email')
                    <div style="font-size:0.75rem; color:#f87171; margin-top:0.3rem;">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="auth-btn">Kirim Link Reset Password</button>
        </form>

        <div style="text-align:center; margin-top:1.5rem;">
            <a href="{{ route('login') }}" style="font-size:0.78rem; color:#5a6478; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='#8b95a8'" onmouseout="this.style.color='#5a6478'">
                ← Kembali ke Login
            </a>
        </div>
    </div>
</div>
</body>
</html>
