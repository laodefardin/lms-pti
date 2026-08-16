<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Platform belajar digital Prodi Pendidikan Teknologi Informasi, Universitas Sulawesi Barat. Akses materi, kuis, tugas, dan nilai secara online.">
    <title>LMS PTI Unsulbar — Platform Belajar Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #14a7a0, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .feature-card {
            background: rgba(30,33,48,0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 1.75rem;
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            border-color: rgba(20,167,160,0.35);
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(20,167,160,0.12);
        }
        .stat-item { text-align: center; }
        .glow { box-shadow: 0 0 40px rgba(20,167,160,0.2); }
        .dot-grid {
            background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
</head>
<body style="background:#0f1117; color:#f0f4f8; font-family:'Inter',sans-serif;">

    {{-- ── Navbar ───────────────────────────────────────────────── --}}
    <nav class="landing-nav" x-data="{ open: false }">
        <div style="max-width:1200px; margin:0 auto; width:100%; display:flex; align-items:center; justify-content:space-between; gap:2rem;">
            {{-- Logo --}}
            <a href="/" style="display:flex; align-items:center; gap:0.75rem; text-decoration:none;">
                <div style="width:36px; height:36px; background:linear-gradient(135deg,#14a7a0,#0e8a84); border-radius:9px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(20,167,160,0.4);">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size:0.9rem; font-weight:700; color:#f0f4f8; line-height:1.2;">LMS PTI</div>
                    <div style="font-size:0.62rem; color:#8b95a8;">Unsulbar</div>
                </div>
            </a>

            {{-- Nav Links --}}
            <div style="display:flex; align-items:center; gap:2rem;">
                <div style="display:flex; gap:1.5rem;" class="hidden md:flex">
                    <a href="#fitur" style="color:#8b95a8; font-size:0.875rem; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='#f0f4f8'" onmouseout="this.style.color='#8b95a8'">Fitur</a>
                    <a href="#tentang" style="color:#8b95a8; font-size:0.875rem; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='#f0f4f8'" onmouseout="this.style.color='#8b95a8'">Tentang</a>
                    <a href="#kontak" style="color:#8b95a8; font-size:0.875rem; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='#f0f4f8'" onmouseout="this.style.color='#8b95a8'">Kontak</a>
                </div>
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="background:linear-gradient(135deg,#14a7a0,#0e8a84); box-shadow:0 4px 14px rgba(20,167,160,0.4);">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg>
                    Masuk
                </a>
            </div>
        </div>
    </nav>

    {{-- ── Hero Section ─────────────────────────────────────────── --}}
    <section class="landing-hero dot-grid" style="padding-top:64px;">
        <div style="max-width:1200px; margin:0 auto; width:100%; padding:5rem 2rem 4rem; display:grid; grid-template-columns:1fr 1fr; gap:4rem; align-items:center;">
            {{-- Left --}}
            <div class="fade-in">
                <div style="display:inline-flex; align-items:center; gap:0.5rem; background:rgba(20,167,160,0.12); border:1px solid rgba(20,167,160,0.25); border-radius:99px; padding:0.35rem 0.875rem; margin-bottom:1.5rem;">
                    <span style="width:6px; height:6px; background:#14a7a0; border-radius:50%; display:inline-block;"></span>
                    <span style="font-size:0.75rem; color:#14a7a0; font-weight:600;">Platform Belajar Digital</span>
                </div>

                <h1 style="font-size:clamp(2rem, 4vw, 3.2rem); font-weight:900; line-height:1.15; margin-bottom:1.25rem;">
                    Belajar Lebih Cerdas di
                    <span class="gradient-text"> Prodi PTI Unsulbar</span>
                </h1>

                <p style="font-size:1rem; color:#8b95a8; line-height:1.7; margin-bottom:2rem; max-width:480px;">
                    Platform LMS khusus Pendidikan Teknologi Informasi — akses materi, kerjakan kuis, kumpul tugas, dan pantau nilaimu kapan saja, di mana saja.
                </p>

                <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg"
                       style="background:linear-gradient(135deg,#14a7a0,#0e8a84); box-shadow:0 8px 24px rgba(20,167,160,0.4);">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg>
                        Masuk Sekarang
                    </a>
                    <a href="#fitur" class="btn btn-outline btn-lg">
                        Lihat Fitur →
                    </a>
                </div>

                {{-- Mini Stats --}}
                <div style="display:flex; gap:2rem; margin-top:3rem; padding-top:2rem; border-top:1px solid rgba(255,255,255,0.07);">
                    @foreach([['100+','Mahasiswa'],['10+','Matakuliah'],['1','Program Studi']] as $stat)
                    <div>
                        <div style="font-size:1.5rem; font-weight:800; color:#14a7a0;">{{ $stat[0] }}</div>
                        <div style="font-size:0.75rem; color:#8b95a8;">{{ $stat[1] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right — Floating UI Preview --}}
            <div style="position:relative;" class="hidden md:block">
                {{-- Dashboard preview card --}}
                <div style="background:rgba(26,29,39,0.9); border:1px solid rgba(255,255,255,0.08); border-radius:20px; padding:1.5rem; backdrop-filter:blur(20px); box-shadow:0 40px 80px rgba(0,0,0,0.5);" class="glow fade-in">
                    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
                        <div style="width:10px; height:10px; border-radius:50%; background:#ef4444;"></div>
                        <div style="width:10px; height:10px; border-radius:50%; background:#f59e0b;"></div>
                        <div style="width:10px; height:10px; border-radius:50%; background:#22c55e;"></div>
                        <span style="font-size:0.72rem; color:#8b95a8; margin-left:auto;">Dashboard Mahasiswa</span>
                    </div>

                    <div style="font-size:0.85rem; font-weight:600; color:#f0f4f8; margin-bottom:1rem;">Selamat Datang, Ahmad 👋</div>

                    {{-- Stat mini cards --}}
                    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; margin-bottom:1rem;">
                        @foreach([['📚','3','Matakuliah','rgba(20,167,160,0.15)','#14a7a0'],['✅','12','Selesai','rgba(34,197,94,0.15)','#22c55e'],['📝','2','Tugas','rgba(245,158,11,0.15)','#f59e0b']] as $s)
                        <div style="background:{{ $s[3] }}; border-radius:10px; padding:0.6rem; text-align:center;">
                            <div style="font-size:1.2rem;">{{ $s[0] }}</div>
                            <div style="font-size:1rem; font-weight:700; color:{{ $s[4] }};">{{ $s[1] }}</div>
                            <div style="font-size:0.62rem; color:#8b95a8;">{{ $s[2] }}</div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Course progress items --}}
                    <div style="font-size:0.72rem; font-weight:600; color:#8b95a8; margin-bottom:0.6rem;">LANJUTKAN BELAJAR</div>
                    @foreach([['Pemrograman Web Dasar','70%'],['Basis Data','45%'],['Pemrograman OOP','20%']] as $c)
                    <div style="background:rgba(255,255,255,0.04); border-radius:8px; padding:0.6rem 0.75rem; margin-bottom:0.4rem;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.4rem;">
                            <span style="font-size:0.75rem; color:#f0f4f8; font-weight:500;">{{ $c[0] }}</span>
                            <span style="font-size:0.7rem; color:#14a7a0;">{{ $c[1] }}</span>
                        </div>
                        <div style="width:100%; height:4px; background:rgba(255,255,255,0.08); border-radius:99px; overflow:hidden;">
                            <div style="height:100%; width:{{ $c[1] }}; background:linear-gradient(90deg,#14a7a0,#1bbdb5); border-radius:99px;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Floating badge --}}
                <div style="position:absolute; top:-20px; right:-20px; background:linear-gradient(135deg,#14a7a0,#0e8a84); border-radius:12px; padding:0.6rem 1rem; box-shadow:0 8px 24px rgba(20,167,160,0.5); font-size:0.75rem; font-weight:700; color:white; white-space:nowrap;">
                    🎯 TALL Stack
                </div>

                {{-- Floating notification --}}
                <div style="position:absolute; bottom:-15px; left:-15px; background:rgba(26,29,39,0.95); border:1px solid rgba(20,167,160,0.25); border-radius:12px; padding:0.7rem 1rem; backdrop-filter:blur(12px); display:flex; align-items:center; gap:0.75rem; box-shadow:0 8px 24px rgba(0,0,0,0.4);">
                    <div style="width:34px; height:34px; background:rgba(34,197,94,0.2); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1rem;">✅</div>
                    <div>
                        <div style="font-size:0.75rem; font-weight:600; color:#f0f4f8;">Nilai masuk!</div>
                        <div style="font-size:0.65rem; color:#8b95a8;">Kuis Pemrograman Web</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Features Section ─────────────────────────────────────── --}}
    <section id="fitur" style="padding:5rem 2rem; background:radial-gradient(ellipse at center, rgba(20,167,160,0.05) 0%, transparent 70%);">
        <div style="max-width:1200px; margin:0 auto; text-align:center; margin-bottom:3rem;">
            <div style="display:inline-block; background:rgba(20,167,160,0.12); border:1px solid rgba(20,167,160,0.2); border-radius:99px; padding:0.3rem 0.875rem; font-size:0.75rem; color:#14a7a0; font-weight:600; margin-bottom:1rem;">FITUR UNGGULAN</div>
            <h2 style="font-size:clamp(1.75rem,3vw,2.5rem); font-weight:800; margin-bottom:0.75rem;">Semua yang kamu butuhkan <span class="gradient-text">dalam satu platform</span></h2>
            <p style="color:#8b95a8; max-width:520px; margin:0 auto; font-size:0.95rem; line-height:1.7;">Dirancang khusus untuk kebutuhan perkuliahan prodi PTI, bukan platform kursus umum.</p>
        </div>

        <div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.25rem;">
            @foreach([
                ['📹','Materi Interaktif','Video kuliah, PDF, artikel, dan live code editor untuk pemrograman.',['#14a7a0','#0e8a84']],
                ['📝','Tugas Online','Upload tugas, pantau deadline, dan lihat feedback dosen langsung.',['#3b82f6','#1d4ed8']],
                ['⚡','Kuis & Ujian','Kuis dengan timer otomatis, soal diacak, dan nilai langsung muncul.',['#8b5cf6','#6d28d9']],
                ['📊','Pantau Nilai','Nilai semua komponen: tugas, kuis, UTS, UAS, dan kehadiran.',['#f59e0b','#d97706']],
                ['💬','Forum Diskusi','Diskusi per matakuliah dan jalur khusus Tanya Dosen.',['#ec4899','#be185d']],
                ['🏆','Gamifikasi','Poin, badge, dan leaderboard untuk motivasi belajar.',['#14a7a0','#0e8a84']],
            ] as [$icon, $title, $desc, $colors])
            <div class="feature-card">
                <div style="width:48px; height:48px; background:linear-gradient(135deg,{{ $colors[0] }},{{ $colors[1] }}); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-bottom:1rem; box-shadow:0 4px 12px rgba(0,0,0,0.3);">{{ $icon }}</div>
                <h3 style="font-size:1rem; font-weight:700; margin-bottom:0.5rem; color:#f0f4f8;">{{ $title }}</h3>
                <p style="font-size:0.83rem; color:#8b95a8; line-height:1.6;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ── About Section ─────────────────────────────────────────── --}}
    <section id="tentang" style="padding:5rem 2rem; background:rgba(255,255,255,0.01); border-top:1px solid rgba(255,255,255,0.05);">
        <div style="max-width:900px; margin:0 auto; text-align:center;">
            <h2 style="font-size:clamp(1.5rem,2.5vw,2rem); font-weight:800; margin-bottom:1rem;">
                Prodi <span class="gradient-text">Pendidikan Teknologi Informasi</span>
            </h2>
            <p style="color:#8b95a8; line-height:1.8; font-size:0.95rem; margin-bottom:2.5rem;">
                Universitas Sulawesi Barat — mencetak pendidik teknologi informasi yang kompeten, adaptif, dan inovatif untuk menghadapi tantangan era digital.
            </p>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:1.5rem;">
                @foreach([['🎓','Pendidikan Berkualitas'],['💻','Fokus Teknologi'],['📍','Sulawesi Barat'],['🌐','Berbasis Digital']] as [$icon, $label])
                <div style="background:rgba(20,167,160,0.06); border:1px solid rgba(20,167,160,0.15); border-radius:12px; padding:1.25rem; text-align:center;">
                    <div style="font-size:1.75rem; margin-bottom:0.5rem;">{{ $icon }}</div>
                    <div style="font-size:0.8rem; font-weight:600; color:#f0f4f8;">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── CTA Section ─────────────────────────────────────────────── --}}
    <section id="kontak" style="padding:5rem 2rem; text-align:center;">
        <div style="max-width:600px; margin:0 auto; background:linear-gradient(135deg, rgba(20,167,160,0.12), rgba(139,92,246,0.08)); border:1px solid rgba(20,167,160,0.2); border-radius:24px; padding:3rem 2rem;">
            <div style="font-size:2.5rem; margin-bottom:1rem;">🚀</div>
            <h2 style="font-size:1.75rem; font-weight:800; margin-bottom:0.75rem;">Siap Mulai Belajar?</h2>
            <p style="color:#8b95a8; margin-bottom:1.75rem; font-size:0.9rem; line-height:1.7;">Masuk dengan akun yang diberikan oleh prodi dan mulai perjalanan belajarmu hari ini.</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="background:linear-gradient(135deg,#14a7a0,#0e8a84); box-shadow:0 8px 24px rgba(20,167,160,0.45);">
                Masuk ke LMS →
            </a>
        </div>
    </section>

    {{-- ── Footer ─────────────────────────────────────────────────── --}}
    <footer style="padding:1.5rem 2rem; border-top:1px solid rgba(255,255,255,0.06); text-align:center;">
        <p style="font-size:0.78rem; color:#5a6478;">
            &copy; {{ date('Y') }} LMS PTI — Prodi Pendidikan Teknologi Informasi, Universitas Sulawesi Barat.
        </p>
    </footer>

    <script>
        // Smooth reveal on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.feature-card').forEach(el => observer.observe(el));
    </script>
</body>
</html>
