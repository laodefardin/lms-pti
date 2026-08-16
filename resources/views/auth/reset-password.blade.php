<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — LMS PTI Unsulbar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-bg { min-height:100vh; background:radial-gradient(ellipse at 20% 20%, rgba(20,167,160,0.18) 0%, transparent 55%), radial-gradient(ellipse at 80% 80%, rgba(139,92,246,0.12) 0%, transparent 55%), #0f1117; display:flex; align-items:center; justify-content:center; padding:2rem; position:relative; overflow:hidden; }
        .auth-bg::before { content:''; position:absolute; inset:0; background-image:radial-gradient(rgba(255,255,255,0.04) 1px, transparent 1px); background-size:30px 30px; pointer-events:none; }
        .orb { position:absolute; border-radius:50%; filter:blur(80px); pointer-events:none; animation:float 8s ease-in-out infinite; }
        .orb-1 { width:300px; height:300px; background:rgba(20,167,160,0.12); top:-80px; left:-80px; }
        .orb-2 { width:250px; height:250px; background:rgba(139,92,246,0.1); bottom:-60px; right:-60px; animation-delay:-4s; }
        @keyframes float { 0%,100%{transform:translateY(0) scale(1);} 50%{transform:translateY(-20px) scale(1.05);} }
        .auth-card { width:100%; max-width:440px; background:rgba(26,29,39,0.85); backdrop-filter:blur(24px); border:1px solid rgba(255,255,255,0.08); border-radius:24px; padding:2.5rem; position:relative; z-index:1; box-shadow:0 40px 80px rgba(0,0,0,0.5); }
        .auth-input { width:100%; background:rgba(255,255,255,0.05); border:1.5px solid rgba(255,255,255,0.1); border-radius:12px; padding:0.75rem 0.75rem 0.75rem 2.75rem; color:#f0f4f8; font-size:0.9rem; font-family:'Inter',sans-serif; transition:all 0.2s; outline:none; }
        .auth-input:focus { border-color:#14a7a0; background:rgba(20,167,160,0.05); box-shadow:0 0 0 3px rgba(20,167,160,0.1); }
        .auth-input::placeholder { color:#5a6478; }
        .auth-btn { width:100%; background:linear-gradient(135deg,#14a7a0,#0e8a84); color:white; font-weight:700; font-size:0.95rem; padding:0.85rem; border-radius:12px; border:none; cursor:pointer; transition:all 0.25s; box-shadow:0 6px 20px rgba(20,167,160,0.4); font-family:'Inter',sans-serif; }
        .auth-btn:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(20,167,160,0.55); }
        .input-group { position:relative; }
        .input-icon { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#5a6478; pointer-events:none; }
        .toggle-pw { position:absolute; right:0.875rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#5a6478; padding:0; transition:color 0.2s; }
        .toggle-pw:hover { color:#8b95a8; }
    </style>
</head>
<body>
<div class="auth-bg">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="auth-card fade-in">
        <div style="text-align:center; margin-bottom:2rem;">
            <div style="width:56px; height:56px; background:linear-gradient(135deg,#14a7a0,#0e8a84); border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; box-shadow:0 8px 24px rgba(20,167,160,0.45);">
                <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 style="font-size:1.4rem; font-weight:800; color:#f0f4f8; margin-bottom:0.25rem;">Buat Password Baru</h1>
            <p style="font-size:0.82rem; color:#8b95a8;">Masukkan password baru untuk akunmu</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#8b95a8; margin-bottom:0.5rem;">Email</label>
                <div class="input-group">
                    <svg class="input-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <input name="email" type="email" class="auth-input" value="{{ old('email', $request->email) }}" required autofocus>
                </div>
                @error('email')<div style="font-size:0.75rem;color:#f87171;margin-top:0.3rem;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#8b95a8; margin-bottom:0.5rem;">Password Baru</label>
                <div class="input-group">
                    <svg class="input-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <input id="pw1" name="password" type="password" class="auth-input" placeholder="Min. 8 karakter" required style="padding-right:3rem;">
                    <button type="button" class="toggle-pw" onclick="togglePw('pw1',this)">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('password')<div style="font-size:0.75rem;color:#f87171;margin-top:0.3rem;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#8b95a8; margin-bottom:0.5rem;">Konfirmasi Password</label>
                <div class="input-group">
                    <svg class="input-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <input id="pw2" name="password_confirmation" type="password" class="auth-input" placeholder="Ulangi password" required style="padding-right:3rem;">
                    <button type="button" class="toggle-pw" onclick="togglePw('pw2',this)">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('password_confirmation')<div style="font-size:0.75rem;color:#f87171;margin-top:0.3rem;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="auth-btn">Simpan Password Baru</button>
        </form>
    </div>
</div>
<script>
function togglePw(id, btn) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
