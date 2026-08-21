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
        .detail-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            margin-bottom: 1.5rem;
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
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px dashed var(--border);
        }
        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
    </style>

    {{-- Premium Header --}}
    <div class="page-header flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div style="position:relative; z-index:1;">
            <a href="{{ route('dosen.tugas.index', $kelas) }}" 
               class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-medium transition mb-4 bg-white/10 px-3 py-1.5 rounded-full backdrop-blur">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tugas
            </a>
            <h1 class="text-3xl font-extrabold text-white mb-2 tracking-tight">{{ $tugas->judul }}</h1>
            <div class="flex items-center gap-3 text-white/80 text-sm">
                <span class="flex items-center gap-1.5"><i class="fas fa-book text-blue-300"></i> {{ $kelas->mataKuliah->nama ?? '' }}</span>
                <span class="w-1 h-1 rounded-full bg-white/30"></span>
                <span class="flex items-center gap-1.5"><i class="fas fa-users text-blue-300"></i> Kelas {{ $kelas->nama_kelas }}</span>
            </div>
        </div>
        <div style="position:relative; z-index:1;" class="flex flex-wrap gap-3">
            <button wire:click="togglePublish"
                    class="btn bg-white/10 text-white hover:bg-white/20 border border-white/30 shadow-lg px-4 rounded-xl font-bold transition-all hover:-translate-y-1">
                @if($tugas->is_published)
                    <i class="fas fa-eye-slash mr-2" style="color: #fb923c;"></i> Sembunyikan
                @else
                    <i class="fas fa-globe mr-2" style="color: #34d399;"></i> Publish
                @endif
            </button>
            <a href="{{ route('dosen.tugas.edit', ['kelas' => $kelas, 'tugas' => $tugas->id]) }}" 
               class="btn bg-white/10 text-white hover:bg-white border border-white/30 hover:text-blue-900 shadow-lg px-4 rounded-xl font-bold transition-all hover:-translate-y-1">
                <i class="fas fa-edit mr-2"></i> Edit Tugas
            </a>
            <a href="{{ route('dosen.tugas.nilai', ['kelas' => $kelas, 'tugas' => $tugas->id]) }}" 
               class="btn bg-blue-500 hover:bg-blue-400 text-white border-none shadow-[0_8px_20px_rgba(59,130,246,0.4)] px-5 rounded-xl font-bold transition-all hover:-translate-y-1">
                <i class="fas fa-star mr-2 text-yellow-300"></i> Nilai Pengumpulan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- Info Tugas --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="detail-card">
                <h2 class="font-bold text-lg text-[var(--text-primary)] mb-4 flex items-center gap-2 border-b border-[var(--border)] pb-3">
                    <div class="w-8 h-8 rounded-lg bg-[var(--teal)]/10 text-[var(--teal)] flex items-center justify-center text-sm">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    Informasi Tugas
                </h2>
                <div class="prose prose-sm max-w-none text-[var(--text-secondary)] leading-relaxed mb-6">
                    {!! $tugas->deskripsi !!}
                </div>

                @if($tugas->file_soal)
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-[var(--bg-body)] border border-[var(--border)] hover:border-[var(--teal)] transition">
                        <div class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-[var(--text-primary)] truncate">File Soal Lampiran</p>
                            <p class="text-xs text-[var(--text-muted)] truncate mt-0.5">{{ $tugas->file_soal }}</p>
                        </div>
                        <a href="{{ asset('storage/'.$tugas->file_soal) }}" target="_blank" class="btn btn-sm bg-white border border-[var(--border)] shadow-sm hover:border-[var(--teal)] hover:text-[var(--teal)] dark:bg-gray-800 dark:border-gray-700">
                            <i class="fas fa-download mr-2"></i> Unduh
                        </a>
                    </div>
                @endif
            </div>

            {{-- Daftar Pengumpulan --}}
            <div class="modern-table-wrap">
                <div class="flex items-center justify-between px-6 py-5 border-b border-[var(--border)] bg-[var(--bg-card)]">
                    <h2 class="font-bold text-lg text-[var(--text-primary)] flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 flex items-center justify-center text-sm">
                            <i class="fas fa-users"></i>
                        </div>
                        Pengumpulan Mahasiswa
                    </h2>
                    <span class="px-3 py-1 rounded-full bg-[var(--input-bg)] text-xs font-bold text-[var(--text-secondary)]">
                        {{ $pengumpulans->count() }} / {{ $totalMahasiswa }} Terkumpul
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Dikumpulkan</th>
                                <th>Lampiran</th>
                                <th>Status</th>
                                <th class="text-right">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengumpulans as $p)
                                @php
                                    $statusColor = match(strtolower($p->status)) {
                                        'dinilai' => ['bg' => 'rgba(16, 185, 129, 0.1)', 'text' => '#10b981', 'border' => 'rgba(16, 185, 129, 0.2)'],
                                        'dikumpulkan' => ['bg' => 'rgba(59, 130, 246, 0.1)', 'text' => '#3b82f6', 'border' => 'rgba(59, 130, 246, 0.2)'],
                                        default => ['bg' => 'rgba(156, 163, 175, 0.1)', 'text' => '#6b7280', 'border' => 'rgba(156, 163, 175, 0.2)'],
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <div class="font-bold text-[var(--text-primary)]">{{ $p->mahasiswa->name }}</div>
                                        <div class="text-xs text-[var(--text-muted)] mt-0.5"><i class="fas fa-id-card mr-1"></i> {{ $p->mahasiswa->nim ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="text-sm font-medium text-[var(--text-secondary)]">
                                            {{ $p->dikumpulkan_at?->format('d M Y') ?? '-' }}
                                        </div>
                                        <div class="text-xs text-[var(--text-muted)] mt-0.5">
                                            <i class="far fa-clock mr-1"></i> {{ $p->dikumpulkan_at?->format('H:i') ?? '-' }} WIB
                                            @if($p->is_terlambat)
                                                <span class="ml-1 text-red-500 font-bold bg-red-500/10 px-1.5 py-0.5 rounded text-[10px] uppercase">Terlambat</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($p->file_path)
                                            <a href="{{ asset('storage/'.$p->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded bg-[var(--teal)]/10 text-[var(--teal)] text-xs font-bold hover:bg-[var(--teal)] hover:text-white transition">
                                                <i class="fas fa-file-download"></i> File
                                            </a>
                                        @elseif($p->link_url)
                                            <a href="{{ $p->link_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded bg-blue-500/10 text-blue-500 text-xs font-bold hover:bg-blue-500 hover:text-white transition">
                                                <i class="fas fa-external-link-alt"></i> Tautan
                                            </a>
                                        @else
                                            <span class="text-[var(--text-muted)] text-xs italic bg-[var(--input-bg)] px-2.5 py-1.5 rounded">Teks Saja</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="background:{{ $statusColor['bg'] }}; color:{{ $statusColor['text'] }}; border:1px solid {{ $statusColor['border'] }};" class="px-2.5 py-1 rounded-full text-xs font-bold capitalize">
                                            {{ $p->status }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <span class="font-extrabold text-[var(--text-primary)] bg-[var(--input-bg)] px-3 py-1.5 rounded-lg border border-[var(--border)]">
                                            {{ $p->nilai !== null ? $p->nilai : '-' }} <span class="text-xs text-[var(--text-muted)] font-normal">/ {{ $tugas->nilai_max }}</span>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="py-12 text-center flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 rounded-full bg-[var(--input-bg)] flex items-center justify-center text-[var(--text-muted)] text-2xl mb-3">
                                                <i class="fas fa-inbox"></i>
                                            </div>
                                            <p class="text-[var(--text-secondary)] font-medium">Belum ada mahasiswa yang mengumpulkan tugas ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">
            <div class="detail-card">
                <h3 class="font-bold text-[var(--text-primary)] mb-5 flex items-center gap-2">
                    <i class="fas fa-sliders-h text-[var(--teal)]"></i> Detail Pengaturan
                </h3>

                <div class="flex flex-col">
                    <div class="info-row">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Status</span>
                        <span style="background: {{ $tugas->is_published ? 'rgba(16,185,129,0.1)' : 'rgba(156,163,175,0.1)' }}; color: {{ $tugas->is_published ? '#10b981' : '#6b7280' }};" class="text-xs px-2.5 py-1 rounded-full font-bold">
                            {{ $tugas->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Deadline</span>
                        <div class="text-right">
                            <div class="font-bold text-[var(--text-primary)] text-sm">
                                {{ $tugas->deadline?->format('d M Y') ?? '-' }}
                            </div>
                            <div class="text-[10px] font-bold text-red-500 mt-0.5 bg-red-500/10 px-1.5 rounded inline-block">
                                {{ $tugas->deadline?->format('H:i') ?? '-' }} WIB
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Poin Maks.</span>
                        <span class="font-extrabold text-[var(--text-primary)] text-sm bg-[var(--input-bg)] px-2 py-0.5 rounded">{{ $tugas->nilai_max }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Tipe Kumpul</span>
                        <span class="font-bold text-blue-500 bg-blue-500/10 uppercase text-xs px-2.5 py-1 rounded">{{ str_replace('_', ' ', $tugas->tipe_pengumpulan) }}</span>
                    </div>
                    
                    @if($tugas->format_file)
                        <div class="info-row flex-col items-start gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Format File</span>
                            <div class="flex flex-wrap gap-1.5 w-full">
                                @foreach((array)$tugas->format_file as $ext)
                                    <span class="bg-[var(--input-bg)] border border-[var(--border)] text-[var(--text-primary)] font-bold text-xs px-2 py-1 rounded-md">.{{ $ext }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="info-row">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Ukuran Maks.</span>
                        <span class="font-bold text-[var(--text-primary)] text-sm">{{ $tugas->maks_ukuran_mb }} MB</span>
                    </div>
                </div>
            </div>

            <div class="detail-card relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <i class="fas fa-chart-pie text-8xl"></i>
                </div>
                <h3 class="font-bold text-[var(--text-primary)] mb-5 flex items-center gap-2 relative z-10">
                    <i class="fas fa-chart-bar text-orange-500"></i> Statistik
                </h3>
                @php
                    $submitted = $pengumpulans->count();
                    $graded = $pengumpulans->whereNotNull('nilai')->count();
                    $progress = $totalMahasiswa > 0 ? ($submitted / $totalMahasiswa) * 100 : 0;
                @endphp
                
                <div class="flex flex-col relative z-10">
                    <div class="info-row flex-col items-stretch gap-2 border-b pb-4 border-[var(--border)]">
                        <div class="flex justify-between items-center w-full">
                            <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Sudah Kumpul</span>
                            <span class="font-bold text-[var(--text-primary)] text-sm bg-[var(--input-bg)] px-2 py-0.5 rounded">{{ $submitted }} / {{ $totalMahasiswa }}</span>
                        </div>
                        <div class="w-full bg-[var(--bg-body)] rounded-full h-2.5 overflow-hidden border border-[var(--border)]">
                            <div class="h-full bg-[var(--teal)] transition-all duration-1000 ease-out relative" style="width: {{ $progress }}%;">
                                <div class="absolute inset-0 bg-white/20 w-full" style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-row {{ $graded > 0 ? 'border-b pb-4 border-[var(--border)] pt-4' : 'pt-4' }}">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Sudah Dinilai</span>
                        <span class="font-bold text-blue-500 bg-blue-500/10 text-sm px-2 py-0.5 rounded">{{ $graded }} / {{ $submitted }}</span>
                    </div>
                    
                    @if($graded > 0)
                        <div class="info-row pt-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Rata-rata Nilai</span>
                            <span class="font-extrabold text-2xl text-[var(--teal)] drop-shadow-sm">
                                {{ round($pengumpulans->whereNotNull('nilai')->avg('nilai'), 1) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>