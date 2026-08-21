<div class="fade-in">
    <style>
        .page-header {
            background: linear-gradient(135deg, #004b92 0%, #264d7c 100%);
            border-radius: 20px;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 2.5rem;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.1) 0%, transparent 40%);
            pointer-events: none;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            border-color: var(--teal);
        }
        .icon-wrap {
            width: 54px; height: 54px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .modern-table-wrap {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }
        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }
        .modern-table th {
            background: var(--input-bg);
            padding: 1.25rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
        }
        .modern-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            transition: background 0.2s;
        }
        .modern-table tr:last-child td { border-bottom: none; }
        .modern-table tr:hover td { background: var(--bg-card-hover); }
        .action-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .action-btn:hover {
            transform: scale(1.05);
        }
    </style>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl flex items-center gap-3 slide-in-left" style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3); color:#10B981;">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Premium Header --}}
    <div class="page-header flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div style="position:relative; z-index:1;">
            <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas, 'tab' => 'kuis']) }}" 
               class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-medium transition mb-4 bg-white/10 px-3 py-1.5 rounded-full backdrop-blur">
                <i class="fas fa-arrow-left"></i> Kembali ke Kelas
            </a>
            <h1 class="text-3xl font-extrabold text-white mb-2 tracking-tight">Manajemen Kuis & Ujian</h1>
            <div class="flex items-center gap-3 text-white/80 text-sm">
                <span class="flex items-center gap-1.5"><i class="fas fa-book text-blue-300"></i> {{ $kelas->mataKuliah->nama ?? '' }}</span>
                <span class="w-1 h-1 rounded-full bg-white/30"></span>
                <span class="flex items-center gap-1.5"><i class="fas fa-users text-blue-300"></i> Kelas {{ $kelas->nama_kelas }}</span>
            </div>
        </div>
        <div style="position:relative; z-index:1;">
            <a href="{{ route('dosen.kuis.buat', $kelas) }}" class="btn bg-blue-500 hover:bg-blue-400 text-white border-none shadow-[0_8px_20px_rgba(59,130,246,0.4)] px-6 py-3 rounded-xl font-bold transition-all hover:-translate-y-1">
                <i class="fas fa-plus mr-2"></i> Buat Kuis Baru
            </a>
        </div>
    </div>

    {{-- Modern Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="stat-card">
            <div class="icon-wrap" style="background:rgba(59, 130, 246, 0.1); color:#3b82f6;">
                <i class="fas fa-question-circle"></i>
            </div>
            <div>
                <p class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Total Kuis</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)] leading-none">{{ $totalKuis }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-wrap" style="background:rgba(245, 158, 11, 0.1); color:#f59e0b;">
                <i class="fas fa-list-ol"></i>
            </div>
            <div>
                <p class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Total Soal</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)] leading-none">{{ $totalSoal }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-wrap" style="background:rgba(16, 185, 129, 0.1); color:#10b981;">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Kuis Aktif</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)] leading-none">{{ $kuisAktif }}</p>
            </div>
        </div>
    </div>

    {{-- Modern Table --}}
    <div class="modern-table-wrap">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[var(--border)] bg-[var(--bg-card)]">
            <h2 class="font-bold text-lg text-[var(--text-primary)] flex items-center gap-2">
                <i class="fas fa-list-ul text-[var(--teal)]"></i> Daftar Kuis
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="modern-table min-w-[900px]">
                <thead>
                    <tr>
                        <th style="width:5%;">No</th>
                        <th style="width:25%;">Judul Kuis</th>
                        <th style="width:10%;">Tipe</th>
                        <th style="width:20%;">Jadwal & Durasi</th>
                        <th style="width:10%;" class="text-center">Soal</th>
                        <th style="width:10%;" class="text-center">Peserta</th>
                        <th style="width:10%;">Status</th>
                        <th style="width:10%;" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas->kuis as $index => $kuis)
                        @php
                            $status = $kuis->statusLabel();
                            $statusColor = match($status) {
                                'draft' => ['bg' => 'rgba(156, 163, 175, 0.1)', 'text' => '#6b7280', 'border' => 'rgba(156, 163, 175, 0.2)'],
                                'terjadwal' => ['bg' => 'rgba(59, 130, 246, 0.1)', 'text' => '#3b82f6', 'border' => 'rgba(59, 130, 246, 0.2)'],
                                'selesai' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'text' => '#ef4444', 'border' => 'rgba(239, 68, 68, 0.2)'],
                                default => ['bg' => 'rgba(16, 185, 129, 0.1)', 'text' => '#10b981', 'border' => 'rgba(16, 185, 129, 0.2)'], // Aktif
                            };
                            
                            $tipeColor = match(strtolower($kuis->tipe)) {
                                'kuis' => ['bg' => 'rgba(139, 92, 246, 0.1)', 'text' => '#8b5cf6'],
                                'ujian' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'text' => '#ef4444'],
                                default => ['bg' => 'rgba(156, 163, 175, 0.1)', 'text' => '#6b7280'],
                            };
                        @endphp
                        <tr>
                            <td class="text-center font-medium text-[var(--text-muted)]">{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('dosen.kuis.detail', ['kelas' => $kelas, 'kuis' => $kuis->id]) }}" class="font-bold text-[var(--text-primary)] hover:text-[var(--teal)] transition flex items-center gap-2">
                                    {{ $kuis->judul }}
                                </a>
                                @if($kuis->pertemuan)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full mt-1" style="background:rgba(99,102,241,0.1); color:#4f46e5;">
                                        <i class="fas fa-link"></i> Pertemuan ke-{{ $kuis->pertemuan->nomor }}
                                    </span>
                                @endif
                                <p class="text-xs text-[var(--text-secondary)] mt-1 line-clamp-1">{!! strip_tags($kuis->deskripsi) !!}</p>
                            </td>
                            <td>
                                <span style="background:{{ $tipeColor['bg'] }}; color:{{ $tipeColor['text'] }};" class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider border border-[{{ $tipeColor['text'] }}] border-opacity-20">
                                    {{ $kuis->tipe }}
                                </span>
                            </td>
                            <td>
                                @if($kuis->buka_at && $kuis->tutup_at)
                                    <div class="font-semibold text-sm text-[var(--text-primary)]">
                                        {{ $kuis->buka_at->format('d M') }} · {{ $kuis->buka_at->format('H:i') }} - {{ $kuis->tutup_at->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-[var(--text-secondary)] mt-0.5">
                                        <i class="far fa-clock mr-1"></i> Durasi: <span class="font-bold">{{ $kuis->durasi_menit }} Menit</span>
                                    </div>
                                @else
                                    <span class="text-xs italic text-[var(--text-muted)] px-2.5 py-1 bg-[var(--input-bg)] rounded-md">Belum dijadwalkan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[var(--input-bg)] text-[var(--text-primary)] font-bold text-sm">
                                    {{ $kuis->soal_count ?? 0 }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[var(--input-bg)] text-[var(--text-primary)] font-bold text-sm">
                                    {{ $kuis->sesi_count ?? 0 }}
                                </div>
                            </td>
                            <td>
                                <span style="background:{{ $statusColor['bg'] }}; color:{{ $statusColor['text'] }}; border:1px solid {{ $statusColor['border'] }};" class="px-3 py-1 rounded-full text-xs font-bold capitalize">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-1.5">
                                    <button wire:click="togglePublish({{ $kuis->id }})"
                                            class="action-btn {{ $kuis->is_published ? 'text-orange-500 hover:bg-orange-50 hover:border-orange-200 dark:hover:bg-orange-500/10 dark:hover:border-orange-500/30' : 'text-teal-500 hover:bg-teal-50 hover:border-teal-200 dark:hover:bg-teal-500/10 dark:hover:border-teal-500/30' }}"
                                            title="{{ $kuis->is_published ? 'Sembunyikan Kuis' : 'Publish Kuis' }}">
                                        <i class="fas fa-{{ $kuis->is_published ? 'eye-slash' : 'globe' }}"></i>
                                    </button>

                                    <a href="{{ route('dosen.kuis.detail', ['kelas' => $kelas, 'kuis' => $kuis->id]) }}"
                                       class="action-btn text-blue-500 hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-500/10 dark:hover:border-blue-500/30" title="Kelola Soal & Peserta">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                    
                                    <div x-data="{ open: false }" class="inline-block relative">
                                        <button @click="open = true" class="action-btn text-red-500 hover:bg-red-50 hover:border-red-200 dark:hover:bg-red-500/10 dark:hover:border-red-500/30" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        
                                        <template x-teleport="body">
                                            <div x-show="open" style="display:none;" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                                                <div x-show="open" x-transition.opacity @click="open = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                                                <div x-show="open"
                                                     x-transition:enter="transition ease-out duration-300"
                                                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                     x-transition:leave="transition ease-in duration-200"
                                                     x-transition:leave-start="opacity-100 scale-100"
                                                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                                     class="relative bg-[var(--bg-card)] border border-[var(--border)] rounded-2xl shadow-2xl w-full max-w-md p-6 text-left">
                                                    <div class="flex gap-4 items-start mb-5">
                                                        <div class="w-12 h-12 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center text-xl flex-shrink-0">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-lg font-bold text-[var(--text-primary)] mb-1">Hapus Kuis Ini?</h3>
                                                            <p class="text-[var(--text-secondary)] text-sm leading-relaxed">Yakin hapus kuis '<span class="font-bold text-[var(--text-primary)]">{{ $kuis->judul }}</span>'? Semua data soal dan hasil ujian mahasiswa akan ikut terhapus secara permanen.</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-3 justify-end mt-6 pt-5 border-t border-[var(--border)]">
                                                        <button @click="open = false" class="btn btn-outline border-[var(--border)] text-[var(--text-primary)] hover:bg-[var(--input-bg)] px-5">Batal</button>
                                                        <button wire:click="hapusKuis({{ $kuis->id }})" @click="open = false" class="btn bg-red-500 hover:bg-red-600 text-white border-none px-5 shadow-[0_4px_14px_rgba(239,68,68,0.4)]">
                                                            <span wire:loading.remove wire:target="hapusKuis({{ $kuis->id }})">Ya, Hapus</span>
                                                            <span wire:loading wire:target="hapusKuis({{ $kuis->id }})"><i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="py-16 text-center flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 rounded-full bg-[var(--input-bg)] flex items-center justify-center text-[var(--text-muted)] text-3xl mb-4">
                                        <i class="fas fa-question-circle"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--text-primary)] mb-2">Belum Ada Kuis</h3>
                                    <p class="text-[var(--text-secondary)] max-w-sm mb-6">Anda belum membuat kuis atau ujian untuk kelas ini. Mulai buat kuis pertama Anda sekarang.</p>
                                    <a href="{{ route('dosen.kuis.buat', $kelas) }}" class="btn bg-[var(--teal)] hover:bg-[var(--teal-dark)] text-white shadow-lg shadow-[var(--teal)]/30 rounded-full px-6">
                                        <i class="fas fa-plus mr-2"></i> Buat Kuis Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
