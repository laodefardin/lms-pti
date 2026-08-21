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
            margin-bottom: 2rem;
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
        .grading-box {
            background: var(--input-bg);
            border: 1px solid var(--teal);
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(20, 184, 166, 0.1);
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
            <a href="{{ route('dosen.tugas.detail', ['kelas' => $kelas, 'tugas' => $tugas->id]) }}" 
               class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-medium transition mb-4 bg-white/10 px-3 py-1.5 rounded-full backdrop-blur">
                <i class="fas fa-arrow-left"></i> Kembali ke Detail Tugas
            </a>
            <h1 class="text-3xl font-extrabold text-white mb-2 tracking-tight">Penilaian Tugas</h1>
            <div class="flex items-center gap-3 text-white/80 text-sm">
                <span class="flex items-center gap-1.5"><i class="fas fa-file-alt text-yellow-300"></i> {{ $tugas->judul }}</span>
                <span class="w-1 h-1 rounded-full bg-white/30"></span>
                <span class="flex items-center gap-1.5"><i class="fas fa-users text-blue-300"></i> Kelas {{ $kelas->nama_kelas }}</span>
            </div>
        </div>
    </div>

    @php
        $totalMahasiswa = $kelas->mahasiswa->count();
        $submitted = $pengumpulans->count();
        $graded = $pengumpulans->whereNotNull('nilai')->count();
        $avgScore = $graded > 0 ? round($pengumpulans->whereNotNull('nilai')->avg('nilai'), 1) : 0;
    @endphp

    {{-- Modern Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="stat-card">
            <div class="icon-wrap" style="background:rgba(59, 130, 246, 0.1); color:#3b82f6;">
                <i class="fas fa-inbox"></i>
            </div>
            <div>
                <p class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Sudah Kumpul</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)] leading-none">{{ $submitted }} <span class="text-sm font-normal text-[var(--text-muted)]">/ {{ $totalMahasiswa }}</span></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-wrap" style="background:rgba(16, 185, 129, 0.1); color:#10b981;">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <p class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Sudah Dinilai</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)] leading-none">{{ $graded }} <span class="text-sm font-normal text-[var(--text-muted)]">/ {{ $submitted }}</span></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-wrap" style="background:rgba(245, 158, 11, 0.1); color:#f59e0b;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <p class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Rata-rata Nilai</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)] leading-none">{{ $avgScore }} <span class="text-sm font-normal text-[var(--text-muted)]">/ 100</span></p>
            </div>
        </div>
    </div>

    {{-- Modern Table --}}
    <div class="modern-table-wrap">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[var(--border)] bg-[var(--bg-card)]">
            <h2 class="font-bold text-lg text-[var(--text-primary)] flex items-center gap-2">
                <i class="fas fa-clipboard-check text-[var(--teal)]"></i> Daftar Pengumpulan Mahasiswa
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="modern-table min-w-[900px]">
                <thead>
                    <tr>
                        <th style="width:25%;">Mahasiswa</th>
                        <th style="width:20%;">Waktu Pengumpulan</th>
                        <th style="width:20%;">Lampiran</th>
                        <th style="width:15%;">Status</th>
                        <th style="width:20%;" class="text-right">Aksi & Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengumpulans as $p)
                        <tr class="{{ $editId === $p->id ? 'bg-[var(--teal)]/5' : '' }}">
                            <td>
                                <div class="font-bold text-[var(--text-primary)]">{{ $p->mahasiswa->name }}</div>
                                <div class="text-xs text-[var(--text-muted)] mt-0.5"><i class="fas fa-id-card mr-1"></i> {{ $p->mahasiswa->nim ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="text-sm font-medium text-[var(--text-secondary)]">
                                    {{ $p->dikumpulkan_at?->format('d M Y, H:i') ?? '-' }}
                                </div>
                                @if($p->is_terlambat)
                                    <span class="inline-block mt-1 text-red-500 font-bold bg-red-500/10 px-2 py-0.5 rounded text-[10px] uppercase">Terlambat</span>
                                @endif
                            </td>
                            <td>
                                @if($p->file_path)
                                    <a href="{{ asset('storage/'.$p->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[var(--teal)]/10 text-[var(--teal)] text-xs font-bold hover:bg-[var(--teal)] hover:text-white transition">
                                        <i class="fas fa-file-download"></i> Unduh File
                                    </a>
                                @elseif($p->link_url)
                                    <a href="{{ $p->link_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-500/10 text-blue-500 text-xs font-bold hover:bg-blue-500 hover:text-white transition">
                                        <i class="fas fa-external-link-alt"></i> Buka Tautan
                                    </a>
                                @else
                                    <span class="text-[var(--text-muted)] text-xs italic bg-[var(--input-bg)] px-2.5 py-1.5 rounded">Teks Saja</span>
                                @endif
                                @if($p->keterangan)
                                    <div class="mt-2 text-xs text-[var(--text-secondary)] border-l-2 border-[var(--teal)] pl-2 italic">
                                        "{!! strip_tags($p->keterangan) !!}"
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($p->status === 'dinilai')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-500 border border-green-500/20"><i class="fas fa-check mr-1"></i> Dinilai</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-500 border border-blue-500/20">Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($editId === $p->id)
                                    <!-- Grading Mode -->
                                    <div class="text-left w-full min-w-[250px] grading-box slide-in-top">
                                        <div class="mb-3">
                                            <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1">Nilai (0-100)</label>
                                            <div class="flex items-center gap-2">
                                                <input type="number" wire:model="nilai" min="0" max="100" class="input input-bordered w-24 text-center font-bold text-lg p-2 rounded-lg bg-white dark:bg-gray-800" placeholder="0">
                                                <span class="text-[var(--text-muted)] font-bold text-lg">/ 100</span>
                                            </div>
                                            @error('nilai') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1">Feedback (Opsional)</label>
                                            <textarea wire:model="feedback" rows="2" class="input input-bordered w-full text-sm p-2 rounded-lg bg-white dark:bg-gray-800" placeholder="Beri catatan untuk mahasiswa ini..."></textarea>
                                        </div>
                                        <div class="flex flex-wrap gap-2 justify-end mt-2">
                                            <button wire:click="cancelEdit" style="border: 1px solid #94a3b8; color: #475569; background-color: #f8fafc;" class="btn btn-sm text-xs px-3 rounded-lg hover:bg-gray-100 transition">
                                                Batal
                                            </button>
                                            <button wire:click="saveNilai" style="background-color: #0d9488; color: white;" class="btn btn-sm border-none shadow-[0_4px_10px_rgba(20,184,166,0.3)] hover:opacity-90 transition text-xs px-4 rounded-lg">
                                                <span wire:loading.remove wire:target="saveNilai">Simpan</span>
                                                <span wire:loading wire:target="saveNilai"><i class="fas fa-spinner fa-spin"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <!-- View Mode -->
                                    <div class="flex flex-col items-end justify-center h-full">
                                        @if($p->nilai !== null)
                                            <div class="font-extrabold text-2xl text-[var(--text-primary)] mb-1">
                                                {{ $p->nilai }}<span class="text-sm font-normal text-[var(--text-muted)]">/100</span>
                                            </div>
                                            <button wire:click="openEdit({{ $p->id }})" class="text-[var(--teal)] hover:text-[var(--teal-dark)] text-xs font-bold hover:underline">
                                                <i class="fas fa-edit mr-1"></i> Ubah Nilai
                                            </button>
                                        @else
                                            <button wire:click="openEdit({{ $p->id }})" style="background-color: #3b82f6; color: white;" class="btn border-none shadow-[0_4px_12px_rgba(59,130,246,0.3)] rounded-lg font-bold text-sm px-4 py-2 hover:opacity-90 hover:-translate-y-0.5 transition">
                                                Beri Nilai
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @if($p->feedback && $editId !== $p->id)
                            <tr class="bg-[var(--bg-body)]">
                                <td colspan="5" class="py-3 px-6">
                                    <div class="text-xs font-medium text-[var(--text-secondary)] bg-[var(--bg-card)] border border-[var(--border)] p-3 rounded-lg shadow-sm">
                                        <i class="fas fa-comment-dots text-[var(--teal)] mr-2"></i> <span class="font-bold">Feedback Anda:</span> {{ $p->feedback }}
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="py-12 text-center flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 rounded-full bg-[var(--input-bg)] flex items-center justify-center text-[var(--text-muted)] text-2xl mb-3">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--text-primary)] mb-1">Belum Ada Pengumpulan</h3>
                                    <p class="text-[var(--text-secondary)] font-medium">Mahasiswa belum ada yang mengumpulkan tugas ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Belum Mengumpulkan Section --}}
    @if($belumMengumpulkan->count() > 0)
        <div class="modern-table-wrap mt-8 opacity-80 hover:opacity-100 transition">
            <div class="flex items-center justify-between px-6 py-4 border-b border-[var(--border)] bg-red-500/5">
                <h2 class="font-bold text-sm text-[var(--text-primary)] flex items-center gap-2">
                    <i class="fas fa-times-circle text-red-500"></i> Belum Mengumpulkan ({{ $belumMengumpulkan->count() }})
                </h2>
            </div>
            <div class="p-5">
                <div class="flex flex-wrap gap-2">
                    @foreach($belumMengumpulkan as $mhs)
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[var(--input-bg)] border border-[var(--border)] text-xs font-medium text-[var(--text-secondary)]">
                            <i class="fas fa-user-circle text-[var(--text-muted)]"></i> {{ $mhs->name }}
                            <div class="w-px h-4 bg-[var(--border)] mx-1"></div>
                            <button wire:click="createAndGrade({{ $mhs->id }})" class="text-[var(--teal)] hover:text-[var(--teal-dark)] hover:scale-110 transition font-bold" title="Beri Nilai Kosong/0">
                                <i class="fas fa-star mr-1"></i> Nilai
                            </button>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>