<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran via Google — LMS PTI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(153deg, #00478b 0%, #00478b 50%, #00478b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background blobs */
        body::before {
            content: '';
            position: fixed;
            top: -30%;
            left: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
            animation: blobFloat 8s ease-in-out infinite;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -20%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);
            animation: blobFloat 10s ease-in-out infinite reverse;
            pointer-events: none;
        }

        @keyframes blobFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -20px) scale(1.05); }
        }

        .card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
            animation: cardIn 0.5s cubic-bezier(0.34,1.56,0.64,1);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .google-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
            gap: 0.75rem;
        }

        .avatar-ring {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            padding: 3px;
            background: linear-gradient(135deg, #4285f4, #34a853, #fbbc05, #ea4335);
            animation: ringPulse 3s ease-in-out infinite;
        }

        @keyframes ringPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(66,133,244,0.4); }
            50%       { box-shadow: 0 0 0 8px rgba(66,133,244,0); }
        }

        .avatar-ring img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            border: 2px solid #0f172a;
        }

        .avatar-fallback {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #4285f4, #34a853);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            color: white;
            border: 2px solid #0f172a;
        }

        .google-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(66,133,244,0.15);
            border: 1px solid rgba(66,133,244,0.3);
            border-radius: 99px;
            padding: 4px 12px;
            font-size: 0.75rem;
            color: #93c5fd;
            font-weight: 600;
        }

        .user-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: white;
        }

        .user-email {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            text-align: center;
            font-size: 0.875rem;
            color: rgba(255,255,255,0.5);
            margin-bottom: 2rem;
        }

        /* Role cards */
        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .role-card {
            position: relative;
            cursor: pointer;
        }

        .role-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .role-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.625rem;
            padding: 1.25rem 1rem;
            border-radius: 16px;
            border: 2px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.04);
            cursor: pointer;
            transition: all 0.25s ease;
            text-align: center;
        }

        .role-label:hover {
            border-color: rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.08);
        }

        .role-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: transform 0.2s;
        }

        .role-label:hover .role-icon {
            transform: scale(1.1);
        }

        .role-mahasiswa .role-icon { background: rgba(16,185,129,0.2); color: #34d399; }
        .role-dosen     .role-icon { background: rgba(59,130,246,0.2); color: #60a5fa; }

        .role-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: white;
        }

        .role-desc {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.45);
            line-height: 1.4;
        }

        /* Selected state */
        input[type="radio"]:checked + .role-label {
            border-color: #4285f4;
            background: rgba(66,133,244,0.12);
            box-shadow: 0 0 0 3px rgba(66,133,244,0.2), inset 0 0 20px rgba(66,133,244,0.05);
        }

        input[type="radio"]:checked + .role-mahasiswa {
            border-color: #10b981;
            background: rgba(16,185,129,0.12);
            box-shadow: 0 0 0 3px rgba(16,185,129,0.2);
        }

        input[type="radio"]:checked + .role-dosen {
            border-color: #3b82f6;
            background: rgba(59,130,246,0.12);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        }

        /* Input field */
        .input-group {
            margin-bottom: 1.25rem;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .input-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: rgba(255,255,255,0.7);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.3);
            font-size: 0.9rem;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: white;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .input-wrapper input::placeholder { color: rgba(255,255,255,0.25); font-weight: 400; letter-spacing: 0; }
        .input-wrapper input:focus {
            border-color: rgba(255,255,255,0.35);
            box-shadow: 0 0 0 3px rgba(255,255,255,0.05);
        }

        /* Error message */
        .error-msg {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #fca5a5;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #4285f4, #1a73e8);
            color: white;
            border: none;
            border-radius: 14px;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(66,133,244,0.45);
        }

        .btn-submit:active { transform: translateY(0); }

        .divider {
            width: 100%;
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin: 1.5rem 0;
        }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: rgba(255,255,255,0.4);
            font-size: 0.8rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover { color: rgba(255,255,255,0.7); }

        .hint-text {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.35);
            margin-top: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
    </style>
</head>
<body>
    <div class="card">

        {{-- Google Profile Info --}}
        <div class="google-profile">
            <div class="avatar-ring">
                @if(!empty($googleUser['avatar']))
                    <img src="{{ $googleUser['avatar'] }}" alt="{{ $googleUser['name'] }}" referrerpolicy="no-referrer">
                @else
                    <div class="avatar-fallback">{{ strtoupper(substr($googleUser['name'], 0, 1)) }}</div>
                @endif
            </div>
            <div class="google-badge">
                <svg width="12" height="12" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Akun Google Terverifikasi
            </div>
            <div class="user-name">{{ $googleUser['name'] }}</div>
            <div class="user-email">{{ $googleUser['email'] }}</div>
        </div>

        <h1>Lengkapi Pendaftaran</h1>
        <p class="subtitle">Pilih peran Anda dan masukkan NIM / NIP untuk verifikasi</p>

        {{-- Error messages --}}
        @if($errors->any())
            <div class="error-msg">
                <i class="fas fa-exclamation-circle" style="margin-top:2px; flex-shrink:0;"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('google.register.store') }}" id="googleRegisterForm">
            @csrf

            {{-- Role Selection --}}
            <div class="role-grid">
                <div class="role-card role-mahasiswa">
                    <input type="radio" name="role" id="role_mahasiswa" value="mahasiswa"
                           {{ old('role') === 'mahasiswa' ? 'checked' : '' }}
                           onchange="updateLabel(this.value)">
                    <label for="role_mahasiswa" class="role-label role-mahasiswa">
                        <div class="role-icon"><i class="fas fa-user-graduate"></i></div>
                        <div>
                            <div class="role-name">Mahasiswa</div>
                            <div class="role-desc">Ikuti kelas & kumpulkan tugas</div>
                        </div>
                    </label>
                </div>

                <div class="role-card role-dosen">
                    <input type="radio" name="role" id="role_dosen" value="dosen"
                           {{ old('role') === 'dosen' ? 'checked' : '' }}
                           onchange="updateLabel(this.value)">
                    <label for="role_dosen" class="role-label role-dosen">
                        <div class="role-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div>
                            <div class="role-name">Dosen</div>
                            <div class="role-desc">Kelola kelas & nilai mahasiswa</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- NIM / NIP Input --}}
            <div class="input-group" id="nimNipGroup">
                <label for="nim_nip" id="nimNipLabel">NIM / NIP</label>
                <div class="input-wrapper">
                    <i class="fas fa-id-card" id="nimNipIcon"></i>
                    <input type="text"
                           id="nim_nip"
                           name="nim_nip"
                           value="{{ old('nim_nip') }}"
                           placeholder="Masukkan NIM atau NIP Anda"
                           autocomplete="off"
                           required>
                </div>
                <div class="hint-text">
                    <i class="fas fa-info-circle" style="font-size:0.7rem;"></i>
                    <span id="nimNipHint">Pilih peran terlebih dahulu</span>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-check-circle"></i>
                <span id="btnText">Selesaikan Pendaftaran</span>
            </button>
        </form>

        <div class="divider"></div>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Halaman Login
        </a>
    </div>

    <script>
        function updateLabel(role) {
            const label   = document.getElementById('nimNipLabel');
            const hint    = document.getElementById('nimNipHint');
            const icon    = document.getElementById('nimNipIcon');
            const input   = document.getElementById('nim_nip');
            const btnText = document.getElementById('btnText');

            if (role === 'mahasiswa') {
                label.textContent    = 'NIM (Nomor Induk Mahasiswa)';
                hint.textContent     = 'NIM harus sudah didaftarkan oleh Admin terlebih dahulu';
                input.placeholder    = 'Contoh: 20230001';
                icon.className       = 'fas fa-id-badge';
                btnText.textContent  = 'Daftar sebagai Mahasiswa';
            } else {
                label.textContent    = 'NIP / NIDN (Nomor Induk Dosen)';
                hint.textContent     = 'NIP/NIDN harus sudah didaftarkan oleh Admin terlebih dahulu';
                input.placeholder    = 'Contoh: 0012345678';
                icon.className       = 'fas fa-id-card';
                btnText.textContent  = 'Daftar sebagai Dosen';
            }

            input.focus();
        }

        // Init label if old value exists
        const checkedRole = document.querySelector('input[name="role"]:checked');
        if (checkedRole) updateLabel(checkedRole.value);

        // Loading state on submit
        document.getElementById('googleRegisterForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';
            btn.disabled = true;
        });
    </script>
</body>
</html>
