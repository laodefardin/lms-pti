<div class="fade-in">
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #3b82f6 100%);
            position: relative;
            overflow: hidden;
            border: none;
        }
        .hero-gradient::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: pulse-glow 10s infinite linear;
            pointer-events: none;
        }
        @keyframes pulse-glow {
            0% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(180deg); }
            100% { transform: scale(1) rotate(360deg); }
        }
        .glass-badge {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: var(--teal);
        }
        .stat-icon-wrap {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .module-accordion {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }
        .module-accordion:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-color: var(--border-teal);
        }
        .module-header {
            padding: 1.25rem 1.5rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: space-between;
            background: transparent;
            border: none; width: 100%; text-align: left;
            transition: background 0.2s;
        }
        .module-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }
        .module-item:hover {
            background: var(--input-bg);
            padding-left: 2rem;
        }
        .module-item:hover .play-btn {
            opacity: 1;
            transform: scale(1);
        }
        .play-btn {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.2s ease;
            color: var(--teal);
        }
    </style>

    {{-- ── Breadcrumb ─────────────────────────────────────────── --}}
    <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; color:var(--text-secondary); margin-bottom:1.5rem;">
        <a href="{{ route('mahasiswa.matakuliah.index') }}" style="color:var(--text-secondary); text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='var(--text-secondary)'">
            <i class="fas fa-home" style="margin-right:0.25rem;"></i> Matakuliah Saya
        </a>
        <i class="fas fa-chevron-right" style="font-size:0.6rem; color:var(--text-muted);"></i>
        <span style="color:var(--text-primary); font-weight:600;">{{ $kelas->mataKuliah->nama }}</span>
    </div>

    {{-- ── Premium Hero Card ───────────────────────────────────── --}}
    <div class="hero-gradient" style="border-radius:24px; padding:2.5rem; margin-bottom:2rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:2rem; box-shadow: 0 25px 50px -12px rgba(30, 58, 138, 0.4);">
        <div style="flex:1; min-width:300px; position:relative; z-index:1;">
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:1.25rem;">
                <span class="glass-badge"><i class="fas fa-tag"></i> {{ $kelas->mataKuliah->kode }}</span>
                <span class="glass-badge"><i class="fas fa-star"></i> {{ $kelas->mataKuliah->sks }} SKS</span>
                <span class="glass-badge"><i class="fas fa-users"></i> Kelas {{ $kelas->nama_kelas }}</span>
            </div>
            <h1 style="font-size:2.2rem; font-weight:800; color:#ffffff; margin-bottom:1rem; line-height:1.2; letter-spacing:-0.5px;">
                {{ $kelas->mataKuliah->nama }}
            </h1>
            <div style="font-size:0.95rem; color:rgba(255,255,255,0.8); margin-bottom:1.5rem; display:flex; flex-direction:column; gap:0.5rem;">
                <div><i class="fas fa-chalkboard-teacher" style="width:20px; color:#93c5fd;"></i> {{ $kelas->dosen->name }}</div>
                <div style="display:flex; flex-wrap:wrap; gap:1.5rem;">
                    @if($kelas->hari_kuliah)
                    <div><i class="fas fa-calendar-alt" style="width:20px; color:#93c5fd;"></i> {{ ucfirst($kelas->hari_kuliah) }}, {{ $kelas->jam_mulai ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '' }}–{{ $kelas->jam_selesai ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '' }}</div>
                    @endif
                    @if($kelas->ruangan) 
                    <div><i class="fas fa-building" style="width:20px; color:#93c5fd;"></i> {{ $kelas->ruangan }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Glowing Progress Circle --}}
        <div style="text-align:center; position:relative; z-index:1; background:rgba(0,0,0,0.2); border-radius:24px; padding:1.5rem; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.1);">
            <div style="position:relative; width:100px; height:100px; margin:0 auto 0.75rem;">
                <svg width="100" height="100" viewBox="0 0 100 100" style="filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.4));">
                    <circle cx="50" cy="50" r="42" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="8"/>
                    <circle cx="50" cy="50" r="42" fill="none" stroke="#10b981" stroke-width="8"
                            stroke-dasharray="{{ 2 * 3.14159 * 42 }}"
                            stroke-dashoffset="{{ 2 * 3.14159 * 42 * (1 - $progress/100) }}"
                            stroke-linecap="round"
                            transform="rotate(-90 50 50)"
                            style="transition: stroke-dashoffset 1s cubic-bezier(0.4, 0, 0.2, 1);"/>
                </svg>
                <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-size:1.3rem; font-weight:800; color:#ffffff;">
                    {{ $progress }}<span style="font-size:0.8rem;">%</span>
                </div>
            </div>
            <div style="font-size:0.85rem; font-weight:600; color:rgba(255,255,255,0.9); text-transform:uppercase; letter-spacing:1px;">Progress Belajar</div>
        </div>
    </div>

    {{-- ── Quick Stats Grid ─────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.25rem; margin-bottom:2.5rem;">
        
        {{-- Card 1 --}}
        <div class="stat-card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem 1.5rem;">
            <div class="stat-icon-wrap" style="background:rgba(14, 165, 233, 0.1); color:#0ea5e9; margin-bottom:0; flex-shrink:0;">
                <i class="fas fa-book-open"></i>
            </div>
            <div>
                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.25rem;">Total Pertemuan</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $kelas->pertemuan->count() }}</div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="stat-card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem 1.5rem;">
            <div class="stat-icon-wrap" style="background:rgba(239, 68, 68, 0.1); color:#ef4444; margin-bottom:0; flex-shrink:0;">
                <i class="fas fa-pen-nib"></i>
            </div>
            <div>
                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.25rem;">Tugas Pending</div>
                <div style="font-size:1.5rem; font-weight:800; line-height:1; color:{{ $tugasPending > 0 ? '#ef4444' : 'var(--text-primary)' }};">{{ $tugasPending }}</div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="stat-card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem 1.5rem;">
            <div class="stat-icon-wrap" style="background:rgba(245, 158, 11, 0.1); color:#f59e0b; margin-bottom:0; flex-shrink:0;">
                <i class="fas fa-bolt"></i>
            </div>
            <div>
                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.25rem;">Kuis Aktif</div>
                <div style="font-size:1.5rem; font-weight:800; line-height:1; color:{{ $kuisAktif > 0 ? '#f59e0b' : 'var(--text-primary)' }};">{{ $kuisAktif }}</div>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="stat-card" style="padding:1.25rem 1.5rem;">
            <div style="font-size:0.75rem; font-weight:700; color:var(--text-primary); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
                <div style="width:28px; height:28px; background:rgba(139, 92, 246, 0.1); color:#8b5cf6; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:0.8rem;">
                    <i class="fas fa-balance-scale"></i>
                </div>
                Bobot Penilaian
            </div>
            <div style="display:flex; flex-direction:column; gap:0.6rem;">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <span style="font-size:0.75rem; font-weight:600; color:var(--text-secondary); width:40px;">Tugas</span>
                    <div style="flex:1; height:6px; background:var(--input-bg); border-radius:3px; overflow:hidden;">
                        <div style="width:{{ $kelas->bobot_tugas }}%; height:100%; background:#0ea5e9; border-radius:3px;"></div>
                    </div>
                    <span style="font-size:0.7rem; font-weight:700; color:#0ea5e9; width:28px; text-align:right;">{{ $kelas->bobot_tugas }}%</span>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <span style="font-size:0.75rem; font-weight:600; color:var(--text-secondary); width:40px;">Kuis</span>
                    <div style="flex:1; height:6px; background:var(--input-bg); border-radius:3px; overflow:hidden;">
                        <div style="width:{{ $kelas->bobot_kuis }}%; height:100%; background:#f59e0b; border-radius:3px;"></div>
                    </div>
                    <span style="font-size:0.7rem; font-weight:700; color:#f59e0b; width:28px; text-align:right;">{{ $kelas->bobot_kuis }}%</span>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <span style="font-size:0.75rem; font-weight:600; color:var(--text-secondary); width:40px;">UTS</span>
                    <div style="flex:1; height:6px; background:var(--input-bg); border-radius:3px; overflow:hidden;">
                        <div style="width:{{ $kelas->bobot_uts }}%; height:100%; background:#8b5cf6; border-radius:3px;"></div>
                    </div>
                    <span style="font-size:0.7rem; font-weight:700; color:#8b5cf6; width:28px; text-align:right;">{{ $kelas->bobot_uts }}%</span>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <span style="font-size:0.75rem; font-weight:600; color:var(--text-secondary); width:40px;">UAS</span>
                    <div style="flex:1; height:6px; background:var(--input-bg); border-radius:3px; overflow:hidden;">
                        <div style="width:{{ $kelas->bobot_uas }}%; height:100%; background:#ef4444; border-radius:3px;"></div>
                    </div>
                    <span style="font-size:0.7rem; font-weight:700; color:#ef4444; width:28px; text-align:right;">{{ $kelas->bobot_uas }}%</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tentang Kelas ────────────────────────────────────────── --}}
    @if($kelas->mataKuliah->deskripsi || $kelas->deskripsi)
    <div style="background:var(--bg-card); border-left:4px solid var(--teal); border-radius:12px; padding:1.75rem; margin-bottom:2.5rem; box-shadow:0 4px 6px rgba(0,0,0,0.02);">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
            <div style="width:36px; height:36px; background:rgba(16, 185, 129, 0.1); border-radius:8px; display:flex; align-items:center; justify-content:center; color:var(--teal);">
                <i class="fas fa-info-circle"></i>
            </div>
            <h2 style="font-size:1.15rem; font-weight:800; color:var(--text-primary); margin:0;">Tentang Kelas</h2>
        </div>
        <div style="font-size:0.95rem; color:var(--text-secondary); line-height:1.8;">
            {!! nl2br(e($kelas->mataKuliah->deskripsi ?? $kelas->deskripsi)) !!}
        </div>
    </div>
    @endif

    {{-- ── Daftar Materi (Accordion) ────────────────────────────── --}}
    <div style="margin-bottom:1rem;">
        <h2 style="font-size:1.25rem; font-weight:800; color:var(--text-primary); margin-bottom:1.5rem; display:flex; align-items:center; gap:0.5rem;">
            <i class="fas fa-layer-group" style="color:var(--teal);"></i> Modul Pembelajaran
        </h2>

        <div style="display:flex; flex-direction:column;">
            @forelse($kelas->pertemuan as $pertemuan)
            <div class="module-accordion" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                
                {{-- Accordion Header --}}
                <button class="module-header" @click="open = !open">
                    <div style="display:flex; align-items:center; gap:1rem;">
                        <div style="width:40px; height:40px; background:var(--input-bg); border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:700; color:var(--text-muted); font-size:0.9rem;"
                             :style="open ? 'background:rgba(16, 185, 129, 0.1); color:var(--teal);' : ''">
                            {{ $loop->iteration }}
                        </div>
                        <span style="font-size:1.05rem; font-weight:700; color:var(--text-primary); transition:color 0.2s;"
                              :style="open ? 'color:var(--teal);' : ''">
                            {{ $pertemuan->topik }}
                        </span>
                    </div>
                    <i class="fas fa-chevron-down"
                       style="font-size:0.85rem; color:var(--text-muted); transition:transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);"
                       :style="open ? 'transform: rotate(-180deg); color:var(--teal);' : ''"></i>
                </button>

                {{-- Accordion Content --}}
                <div x-show="open" x-collapse x-cloak>
                    <div style="padding-bottom:0.5rem;">
                        @forelse($pertemuan->konten->where('is_published', true) as $konten)
                        <a href="{{ route('mahasiswa.materi.viewer', [$kelas->slug, $konten]) }}" class="module-item" wire:navigate>
                            <div style="display:flex; align-items:center; gap:1rem; min-width:0;">
                                @php
                                    $iconClass = 'fas fa-play';
                                    $iconColor = '#3b82f6';
                                    $iconBg = 'rgba(59, 130, 246, 0.1)';
                                    
                                    if($konten->tipe == 'dokumen') {
                                        $iconClass = 'fas fa-file-pdf';
                                        $iconColor = '#ef4444';
                                        $iconBg = 'rgba(239, 68, 68, 0.1)';
                                    } elseif($konten->tipe == 'kuis') {
                                        $iconClass = 'fas fa-question';
                                        $iconColor = '#f59e0b';
                                        $iconBg = 'rgba(245, 158, 11, 0.1)';
                                    } elseif($konten->tipe == 'tugas') {
                                        $iconClass = 'fas fa-pen-alt';
                                        $iconColor = '#8b5cf6';
                                        $iconBg = 'rgba(139, 92, 246, 0.1)';
                                    } elseif($konten->tipe == 'link') {
                                        $iconClass = 'fas fa-link';
                                        $iconColor = '#10b981';
                                        $iconBg = 'rgba(16, 185, 129, 0.1)';
                                    }
                                @endphp
                                <div style="width:36px; height:36px; background:{{ $iconBg }}; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="{{ $iconClass }}" style="color:{{ $iconColor }}; font-size:0.8rem; {{ ($konten->tipe == 'video' || !$konten->tipe) ? 'margin-left:2px;' : '' }}"></i>
                                </div>
                                <span style="font-size:0.95rem; font-weight:500; color:var(--text-secondary); line-height:1.4;">{{ $konten->judul }}</span>
                            </div>

                            <div style="display:flex; align-items:center; gap:1rem;">
                                @if($konten->estimasi_menit)
                                <div style="font-size:0.8rem; font-weight:600; color:var(--text-muted); background:var(--input-bg); padding:0.25rem 0.5rem; border-radius:6px;">
                                    {{ str_pad(floor($konten->estimasi_menit / 60), 2, '0', STR_PAD_LEFT) }}:{{ str_pad($konten->estimasi_menit % 60, 2, '0', STR_PAD_LEFT) }}
                                </div>
                                @endif
                                <div class="play-btn">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div style="padding:1.5rem; text-align:center; font-size:0.9rem; color:var(--text-muted);">
                            <i class="far fa-folder-open" style="font-size:1.5rem; margin-bottom:0.5rem; display:block;"></i>
                            Belum ada materi untuk pertemuan ini.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center; padding:4rem 2rem; background:var(--bg-card); border:1px dashed var(--border); border-radius:16px;">
                <i class="fas fa-box-open" style="font-size:3rem; color:var(--text-muted); margin-bottom:1rem; display:block;"></i>
                <h3 style="font-size:1.1rem; font-weight:700; color:var(--text-primary); margin-bottom:0.5rem;">Materi Belum Tersedia</h3>
                <p style="font-size:0.9rem; color:var(--text-secondary);">Dosen belum menambahkan materi untuk matakuliah ini.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
