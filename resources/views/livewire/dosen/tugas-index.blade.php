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
            <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas, 'tab' => 'tugas']) }}" 
               class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-medium transition mb-4 bg-white/10 px-3 py-1.5 rounded-full backdrop-blur">
                <i class="fas fa-arrow-left"></i> Kembali ke Kelas
            </a>
            <h1 class="text-3xl font-extrabold text-white mb-2 tracking-tight">Manajemen Tugas</h1>
            <div class="flex items-center gap-3 text-white/80 text-sm">
                <span class="flex items-center gap-1.5"><i class="fas fa-book text-blue-300"></i> {{ $kelas->mataKuliah->nama ?? '' }}</span>
                <span class="w-1 h-1 rounded-full bg-white/30"></span>
                <span class="flex items-center gap-1.5"><i class="fas fa-users text-blue-300"></i> Kelas {{ $kelas->nama_kelas }}</span>
            </div>
        </div>
        <div style="position:relative; z-index:1;">
            <a href="{{ route('dosen.tugas.buat', $kelas) }}" class="btn bg-blue-500 hover:bg-blue-400 text-white border-none shadow-[0_8px_20px_rgba(59,130,246,0.4)] px-6 py-3 rounded-xl font-bold transition-all hover:-translate-y-1">
                <i class="fas fa-plus mr-2"></i> Buat Tugas Baru
            </a>
        </div>
    </div>

    {{-- Modern Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="stat-card">
            <div class="icon-wrap" style="background:rgba(59, 130, 246, 0.1); color:#3b82f6;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Mhs Terdaftar</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)] leading-none">{{ $totalMahasiswa }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-wrap" style="background:rgba(139, 92, 246, 0.1); color:#8b5cf6;">
                <i class="fas fa-tasks"></i>
            </div>
            <div>
                <p class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Total Tugas</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)] leading-none">{{ $totalTugas }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-wrap" style="background:rgba(16, 185, 129, 0.1); color:#10b981;">
                <i class="fas fa-file-upload"></i>
            </div>
            <div>
                <p class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Pengumpulan</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)] leading-none">{{ $totalPengumpulan }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-wrap" style="background:rgba(245, 158, 11, 0.1); color:#f59e0b;">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <p class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Belum Dinilai</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)] leading-none">{{ $belumDinilai }}</p>
            </div>
        </div>
    </div>

    {{-- Modern Table --}}
    <div class="modern-table-wrap">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[var(--border)] bg-[var(--bg-card)]">
            <h2 class="font-bold text-lg text-[var(--text-primary)] flex items-center gap-2">
                <i class="fas fa-list-ul text-[var(--teal)]"></i> Daftar Tugas
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="modern-table min-w-[800px]">
                <thead>
                    <tr>
                        <th style="width:5%;">No</th>
                        <th style="width:30%;">Judul Tugas</th>
                        <th style="width:10%;">Tipe</th>
                        <th style="width:20%;">Deadline</th>
                        <th style="width:15%;" class="text-center">Terkumpul</th>
                        <th style="width:10%;">Status</th>
                        <th style="width:10%;" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas->tugas as $index => $tugas)
                        @php
                            $isPast = \Carbon\Carbon::parse($tugas->deadline)->isPast();
                            $status = $isPast ? 'Selesai' : ($tugas->is_published ? 'Aktif' : 'Draft');
                            
                            $statusColor = match($status) {
                                'Draft' => ['bg' => 'rgba(156, 163, 175, 0.1)', 'text' => '#6b7280', 'border' => 'rgba(156, 163, 175, 0.2)'],
                                'Aktif' => ['bg' => 'rgba(59, 130, 246, 0.1)', 'text' => '#3b82f6', 'border' => 'rgba(59, 130, 246, 0.2)'],
                                'Selesai' => ['bg' => 'rgba(16, 185, 129, 0.1)', 'text' => '#10b981', 'border' => 'rgba(16, 185, 129, 0.2)'],
                                default => ['bg' => 'rgba(156, 163, 175, 0.1)', 'text' => '#6b7280', 'border' => 'rgba(156, 163, 175, 0.2)'],
                            };
                            
                            $tipeColor = match(strtolower($tugas->tipe)) {
                                'individu' => ['bg' => 'rgba(139, 92, 246, 0.1)', 'text' => '#8b5cf6'],
                                'kelompok' => ['bg' => 'rgba(245, 158, 11, 0.1)', 'text' => '#f59e0b'],
                                default => ['bg' => 'rgba(156, 163, 175, 0.1)', 'text' => '#6b7280'],
                            };

                            $jmlKumpul = $tugas->pengumpulan->count();
                            $kumpulPersen = $totalMahasiswa > 0 ? round(($jmlKumpul / $totalMahasiswa) * 100) : 0;
                        @endphp
                        <tr>
                            <td class="text-center font-medium text-[var(--text-muted)]">{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('dosen.tugas.detail', ['kelas' => $kelas, 'tugas' => $tugas->id]) }}" class="font-bold text-[var(--text-primary)] hover:text-[var(--teal)] transition flex items-center gap-2">
                                    {{ $tugas->judul }}
                                </a>
                                @if($tugas->pertemuan)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full mt-1" style="background:rgba(245,158,11,0.1); color:#b45309;">
                                        <i class="fas fa-link"></i> Pertemuan ke-{{ $tugas->pertemuan->nomor }}
                                    </span>
                                @endif
                                <p class="text-xs text-[var(--text-secondary)] mt-1 line-clamp-1">{!! strip_tags($tugas->deskripsi) !!}</p>
                            </td>
                            <td>
                                <span style="background:{{ $tipeColor['bg'] }}; color:{{ $tipeColor['text'] }};" class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider border border-[{{ $tipeColor['text'] }}] border-opacity-20">
                                    {{ $tugas->tipe }}
                                </span>
                            </td>
                            <td>
                                <div class="font-semibold text-sm {{ $isPast ? 'text-red-500' : 'text-[var(--text-primary)]' }}">
                                    {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}
                                </div>
                                <div class="text-xs mt-0.5 {{ $isPast ? 'text-red-400' : 'text-[var(--text-secondary)]' }}">
                                    <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($tugas->deadline)->format('H:i') }} WIB
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex justify-between text-xs font-bold text-[var(--text-secondary)]">
                                        <span>{{ $jmlKumpul }} / {{ $totalMahasiswa }}</span>
                                        <span>{{ $kumpulPersen }}%</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-[var(--input-bg)] rounded-full overflow-hidden">
                                        <div class="h-full rounded-full" style="width: {{ $kumpulPersen }}%; background: {{ $kumpulPersen == 100 ? '#10b981' : '#3b82f6' }};"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="background:{{ $statusColor['bg'] }}; color:{{ $statusColor['text'] }}; border:1px solid {{ $statusColor['border'] }};" class="px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('dosen.tugas.detail', ['kelas' => $kelas, 'tugas' => $tugas->id]) }}"
                                       class="action-btn text-blue-500 hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-500/10 dark:hover:border-blue-500/30" title="Kelola Penilaian">
                                        <i class="fas fa-tasks"></i>
                                    </a>
                                    <a href="{{ route('dosen.tugas.edit', ['kelas' => $kelas, 'tugas' => $tugas->id]) }}"
                                       class="action-btn text-orange-500 hover:bg-orange-50 hover:border-orange-200 dark:hover:bg-orange-500/10 dark:hover:border-orange-500/30" title="Edit Tugas">
                                        <i class="fas fa-edit"></i>
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
                                                     class="relative bg-[var(--bg-card)] border border-[var(--border)] rounded-2xl shadow-2xl w-full max-w-md p-6">
                                                    <div class="flex gap-4 items-start mb-5">
                                                        <div class="w-12 h-12 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center text-xl flex-shrink-0">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-lg font-bold text-[var(--text-primary)] mb-1">Hapus Tugas Ini?</h3>
                                                            <p class="text-[var(--text-secondary)] text-sm leading-relaxed">Semua pengumpulan tugas dari mahasiswa dan nilai yang sudah diberikan akan ikut terhapus secara permanen.</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-3 justify-end mt-6 pt-5 border-t border-[var(--border)]">
                                                        <button @click="open = false" class="btn btn-outline border-[var(--border)] text-[var(--text-primary)] hover:bg-[var(--input-bg)] px-5">Batal</button>
                                                        <button wire:click="hapusTugas({{ $tugas->id }})" @click="open = false" class="btn bg-red-500 hover:bg-red-600 text-white border-none px-5 shadow-[0_4px_14px_rgba(239,68,68,0.4)]">
                                                            <span wire:loading.remove wire:target="hapusTugas({{ $tugas->id }})">Ya, Hapus</span>
                                                            <span wire:loading wire:target="hapusTugas({{ $tugas->id }})"><i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...</span>
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
                            <td colspan="7">
                                <div class="py-16 text-center flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 rounded-full bg-[var(--input-bg)] flex items-center justify-center text-[var(--text-muted)] text-3xl mb-4">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--text-primary)] mb-2">Belum Ada Tugas</h3>
                                    <p class="text-[var(--text-secondary)] max-w-sm mb-6">Anda belum membuat tugas untuk kelas ini. Mulai buat tugas pertama Anda sekarang.</p>
                                    <a href="{{ route('dosen.tugas.buat', $kelas) }}" class="btn bg-[var(--teal)] hover:bg-[var(--teal-dark)] text-white shadow-lg shadow-[var(--teal)]/30 rounded-full px-6">
                                        <i class="fas fa-plus mr-2"></i> Buat Tugas Pertama
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