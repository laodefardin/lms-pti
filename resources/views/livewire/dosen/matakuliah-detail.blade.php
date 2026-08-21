<div class="fade-in">
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('dosen.matakuliah.index') }}" class="text-sm font-medium hover:underline transition-colors" style="color: var(--teal)">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Matakuliah
        </a>
        <a href="{{ route('dosen.matakuliah.edit', $kelas) }}" wire:navigate class="btn-sm btn-outline text-[var(--text-secondary)] border-[var(--border)] bg-[var(--bg-card)]">
            <i class="fas fa-cog mr-1"></i> Pengaturan Kelas
        </a>
    </div>

    <!-- Hero Card -->
    <div class="card overflow-hidden mb-6 border-0 shadow-lg" style="border-radius: 1rem;">
        <div class="relative px-8 py-10" style="background: linear-gradient(135deg, var(--teal), var(--teal-dark)); color: white;">
            {{-- Background decorative elements --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full" style="transform: translate(30%, -30%);"></div>
            <div class="absolute bottom-0 right-32 w-32 h-32 bg-white opacity-10 rounded-full" style="transform: translate(0, 50%);"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:justify-between md:items-end gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="badge bg-white/20 text-white border border-white/30 backdrop-blur-sm">{{ $kelas->mataKuliah->kode ?? '-' }}</span>
                        <span class="badge bg-white/20 text-white border border-white/30 backdrop-blur-sm">Kelas {{ $kelas->nama_kelas ?? 'A' }}</span>
                        <span class="badge bg-white/20 text-white border border-white/30 backdrop-blur-sm">{{ $kelas->semester->nama ?? '-' }}</span>
                    </div>
                    <h1 class="text-3xl font-extrabold mb-2 drop-shadow-md">{{ $kelas->mataKuliah->nama ?? 'Unknown MK' }}</h1>
                    <p class="text-white/80 font-medium flex items-center gap-4">
                        <span><i class="fas fa-calendar-alt mr-2 opacity-70"></i> {{ $kelas->hari_kuliah ? ucfirst($kelas->hari_kuliah) : '-' }}</span>
                        <span><i class="fas fa-clock mr-2 opacity-70"></i> {{ $kelas->jam_mulai ?? '--:--' }} - {{ $kelas->jam_selesai ?? '--:--' }}</span>
                        @if($kelas->ruangan)
                            <span><i class="fas fa-map-marker-alt mr-2 opacity-70"></i> {{ $kelas->ruangan }}</span>
                        @endif
                    </p>
                </div>
                
                <div class="flex gap-3 bg-black/20 p-3 rounded-xl backdrop-blur-sm border border-white/10 shrink-0">
                    <div class="text-center px-4">
                        <p class="text-xs text-white/70 uppercase tracking-wider font-semibold mb-1">Mhs</p>
                        <p class="text-2xl font-bold">{{ $kelas->mahasiswa->count() }}</p>
                    </div>
                    <div class="w-px bg-white/20"></div>
                    <div class="text-center px-4">
                        <p class="text-xs text-white/70 uppercase tracking-wider font-semibold mb-1">Sesi</p>
                        <p class="text-2xl font-bold">{{ $kelas->pertemuan->count() }}</p>
                    </div>
                    <div class="w-px bg-white/20"></div>
                    <div class="text-center px-4">
                        <p class="text-xs text-white/70 uppercase tracking-wider font-semibold mb-1">Tugas</p>
                        <p class="text-2xl font-bold">{{ $kelas->tugas->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex overflow-x-auto border-b mb-6" style="border-color: var(--border)">
        @foreach(['materi' => 'Materi & Konten', 'tugas' => 'Tugas', 'kuis' => 'Kuis', 'absensi' => 'Absensi', 'nilai' => 'Nilai Akhir', 'mahasiswa' => 'Mahasiswa'] as $key => $label)
            <button 
                wire:click="$set('activeTab', '{{ $key }}')"
                class="px-6 py-3 font-medium text-sm whitespace-nowrap border-b-2 transition-colors {{ $activeTab === $key ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                style="{{ $activeTab === $key ? 'border-color: var(--teal); color: var(--teal-dark);' : '' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        @if($activeTab === 'materi')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Daftar Pertemuan</h2>
                <a href="{{ route('dosen.materi.buat', $kelas) }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-2"></i> Tambah Pertemuan</a>
            </div>
            
            <div class="space-y-4">
                @forelse($kelas->pertemuan as $p)
                    <div class="card p-0 overflow-hidden border border-gray-200 shadow-sm transition-all" x-data="{ expanded: false }">
                        <div class="p-4 sm:p-5 flex flex-wrap md:flex-nowrap justify-between items-center bg-white hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-4 flex-1 cursor-pointer min-w-[250px]" @click="expanded = !expanded">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white shrink-0" style="background-color: #064789;">
                                    <span class="text-xs font-bold uppercase tracking-widest">Sesi</span>
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-1">
                                        <span class="text-sm font-bold text-teal-700">Pertemuan ke-{{ $p->nomor ?? '-' }}</span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-sm text-gray-500"><i class="far fa-calendar-alt mr-1"></i> {{ $p->tanggal ? \Carbon\Carbon::parse($p->tanggal)->format('d M Y') : 'Tanpa Tanggal' }}</span>
                                        <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100 ml-1">
                                            <i class="fas fa-cube mr-1"></i> {{ $p->konten->count() }} Konten
                                        </span>
                                    </div>
                                    @if($p->topik)
                                        <h3 class="font-bold text-gray-900 text-lg line-clamp-1">{{ $p->topik }}</h3>
                                    @else
                                        <h3 class="font-bold text-gray-500 text-lg line-clamp-1 italic">Tanpa Judul</h3>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 mt-4 md:mt-0 w-full md:w-auto justify-end">
                                <a href="{{ route('dosen.materi.edit', ['kelas' => $kelas, 'pertemuan' => $p->id]) }}" class="btn btn-sm bg-blue-50 text-blue-600 hover:bg-blue-100 border-none shadow-none px-3" title="Edit Pertemuan">
                                    <i class="fas fa-edit sm:mr-1"></i> <span class="hidden sm:inline">Edit</span>
                                </a>
                                <button wire:click.stop="konfirmasiHapusPertemuan({{ $p->id }})" class="btn btn-sm bg-red-50 text-red-600 hover:bg-red-100 border-none shadow-none px-3" title="Hapus Pertemuan">
                                    <i class="fas fa-trash-alt sm:mr-1"></i> <span class="hidden sm:inline">Hapus</span>
                                </button>
                                <div class="w-px h-6 bg-gray-200 mx-1 hidden md:block"></div>
                                <button class="w-9 h-9 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition-colors" @click="expanded = !expanded">
                                    <i class="fas fa-chevron-down transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div x-show="expanded" x-collapse>
                            <div class="p-5 border-t border-gray-100 bg-[var(--bg-body)]">
                                @if($p->deskripsi)
                                    <p class="text-sm text-gray-700 mb-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm"><i class="fas fa-info-circle text-gray-400 mr-2"></i> {{ $p->deskripsi }}</p>
                                @endif
                                
                                <div class="divide-y divide-gray-100 rounded-xl border border-gray-200 overflow-hidden">
                                    @forelse($p->konten->sortBy('urutan') as $konten)
                                        @php
                                            $tipeIcon = match($konten->tipe) {
                                                'video'   => 'fas fa-play-circle text-blue-500',
                                                'pdf'     => 'fas fa-file-pdf text-red-500',
                                                'artikel' => 'fas fa-edit text-teal-600',
                                                'kode'    => 'fas fa-laptop-code text-purple-500',
                                                'link'    => 'fas fa-link text-orange-500',
                                                default   => 'fas fa-file text-gray-400',
                                            };
                                            $durasi = $konten->estimasi_menit > 0
                                                ? sprintf('%02d:%02d', intdiv($konten->estimasi_menit, 60), $konten->estimasi_menit % 60)
                                                : null;
                                        @endphp
                                        <div class="flex items-center gap-3 px-4 py-2.5 bg-white hover:bg-gray-50 transition-colors group">
                                            <i class="{{ $tipeIcon }} text-base w-5 text-center shrink-0"></i>
                                            <span class="text-sm text-gray-700 flex-1 truncate">{{ $konten->judul }}</span>
                                            @if(!$konten->is_published)
                                                <span class="text-[10px] font-semibold px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded shrink-0">Draft</span>
                                            @endif
                                            @if($durasi)
                                                <span class="text-xs text-gray-400 shrink-0 tabular-nums">{{ $durasi }}</span>
                                            @endif
                                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                                                <a href="{{ route('dosen.konten.edit', ['kelas' => $kelas, 'konten' => $konten]) }}" 
                                                   class="w-7 h-7 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors" title="Edit Konten">
                                                    <i class="fas fa-pencil-alt text-xs"></i>
                                                </a>
                                                <button wire:click="konfirmasiHapusKonten({{ $konten->id }})" 
                                                        class="w-7 h-7 rounded-md bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-colors" title="Hapus Konten">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="flex items-center gap-3 px-4 py-3 text-gray-400 text-sm italic">
                                            <i class="fas fa-folder-open"></i> Belum ada konten materi.
                                        </div>
                                    @endforelse
                                </div>
                                
                                <div class="mt-4 flex flex-wrap items-center gap-3">
                                    <a href="{{ route('dosen.materi.buat', ['kelas' => $kelas, 'pertemuan' => $p->id]) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-teal-50 text-teal-700 border border-teal-200 hover:bg-teal-100 transition">
                                        <i class="fas fa-plus text-[10px]"></i> Tambah Konten
                                    </a>
                                    <a href="{{ route('dosen.tugas.buat', $kelas) }}?pertemuan_id={{ $p->id }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-orange-50 text-orange-700 border border-orange-200 hover:bg-orange-100 transition">
                                        <i class="fas fa-file-alt text-[10px]"></i> Tambah Tugas
                                    </a>
                                    <a href="{{ route('dosen.kuis.buat', $kelas) }}?pertemuan_id={{ $p->id }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 transition">
                                        <i class="fas fa-bolt text-[10px]"></i> Tambah Kuis
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card p-10 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-2xl">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Belum Ada Pertemuan</h3>
                        <p class="text-gray-500 text-sm mb-6">Mulai buat pertemuan/sesi untuk matakuliah ini.</p>
                        <a href="{{ route('dosen.materi.buat', $kelas) }}" class="btn btn-primary inline-flex"><i class="fas fa-plus mr-2"></i> Tambah Pertemuan Perdana</a>
                    </div>
                @endforelse
            </div>

            {{-- Modal Konfirmasi Hapus Pertemuan (Alpine JS Teleport) --}}
            <div x-data="{ open: @entangle('hapusPertemuanId') }">
                <template x-teleport="body">
                    <div x-show="open" style="display:none;" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0">
                        {{-- Overlay --}}
                        <div x-show="open" x-transition.opacity
                             wire:click="batalHapusPertemuan" 
                             class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                        
                        {{-- Modal Content --}}
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 overflow-hidden">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-3xl mx-auto mb-4">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Pertemuan?</h3>
                                <p class="text-gray-500 text-sm">
                                    Apakah Anda yakin ingin menghapus pertemuan ini? Semua konten materi di dalamnya akan ikut terhapus. Aksi ini tidak dapat dibatalkan.
                                </p>
                            </div>
                            
                            <div class="flex gap-3">
                                <button wire:click="batalHapusPertemuan" class="btn btn-outline flex-1 border-gray-300 text-gray-700 hover:bg-gray-50">
                                    Batal
                                </button>
                                <button wire:click="hapusPertemuan" class="btn btn-primary flex-1 bg-red-500 hover:bg-red-600 border-none shadow-lg shadow-red-500/30">
                                    Ya, Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Modal Konfirmasi Hapus Konten --}}
            <div x-data="{ open: @entangle('hapusKontenId') }">
                <template x-teleport="body">
                    <div x-show="open" style="display:none;" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                        <div x-show="open" x-transition.opacity wire:click="batalHapusKonten" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
                            <div class="text-center mb-5">
                                <div class="w-14 h-14 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-2xl mx-auto mb-3">
                                    <i class="fas fa-trash-alt"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Hapus Konten?</h3>
                                <p class="text-gray-500 text-sm">Konten ini akan dihapus permanen dan tidak dapat dipulihkan.</p>
                            </div>
                            <div class="flex gap-3">
                                <button wire:click="batalHapusKonten" class="btn btn-outline flex-1 border-gray-300 text-gray-700 hover:bg-gray-50">Batal</button>
                                <button wire:click="hapusKonten" class="btn flex-1 bg-red-500 hover:bg-red-600 text-white border-none">
                                    <span wire:loading.remove wire:target="hapusKonten">Ya, Hapus</span>
                                    <span wire:loading wire:target="hapusKonten"><i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
        @elseif($activeTab === 'tugas')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Daftar Tugas</h2>
                <a href="{{ route('dosen.tugas.index', ['kelas' => $kelas]) }}" class="btn btn-primary btn-sm">Kelola Tugas</a>
            </div>
            
            <div class="table-wrap">
                <table class="lms-table w-full">
                    <thead>
                        <tr>
                            <th>Judul Tugas</th>
                            <th>Tipe</th>
                            <th>Deadline</th>
                            <th>Progress</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas->tugas as $tugas)
                            <tr>
                                <td class="font-medium">{{ $tugas->judul }}</td>
                                <td><span class="badge badge-gray">{{ $tugas->tipe ?? 'File' }}</span></td>
                                <td class="{{ \Carbon\Carbon::parse($tugas->deadline)->isPast() ? 'text-red-500' : 'text-green-600' }}">
                                    {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    @php
                                        $total = $kelas->mahasiswa->count();
                                        $submitted = $tugas->pengumpulan->count();
                                        $pct = $total > 0 ? ($submitted / $total) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="progress-wrap flex-1 w-24">
                                            <div class="progress-bar bg-teal-500" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-xs text-muted">{{ $submitted }}/{{ $total }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('dosen.tugas.detail', ['kelas' => $kelas, 'tugas' => $tugas->id]) }}" class="btn btn-sm btn-outline">
                                            Kelola
                                        </a>
                                        <button wire:click="konfirmasiHapusTugas({{ $tugas->id }})"
                                                class="btn btn-sm btn-ghost text-red-500" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Belum ada tugas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Modal Konfirmasi Hapus Tugas --}}
            <div x-data="{ open: @entangle('hapusTugasId') }">
                <template x-teleport="body">
                    <div x-show="open" style="display:none;" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                        <div x-show="open" x-transition.opacity wire:click="batalHapusTugas" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
                            <div class="text-center mb-5">
                                <div class="w-14 h-14 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-2xl mx-auto mb-3">
                                    <i class="fas fa-trash-alt"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Hapus Tugas?</h3>
                                <p class="text-gray-500 text-sm">Semua pengumpulan mahasiswa terkait tugas ini akan ikut terhapus dan tidak dapat dipulihkan.</p>
                            </div>
                            <div class="flex gap-3">
                                <button wire:click="batalHapusTugas" class="btn btn-outline flex-1 border-gray-300 text-gray-700 hover:bg-gray-50">Batal</button>
                                <button wire:click="hapusTugas" class="btn flex-1 bg-red-500 hover:bg-red-600 text-white border-none">
                                    <span wire:loading.remove wire:target="hapusTugas">Ya, Hapus</span>
                                    <span wire:loading wire:target="hapusTugas"><i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
        @elseif($activeTab === 'kuis')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Daftar Kuis</h2>
                <a href="{{ route('dosen.kuis.index', ['kelas' => $kelas]) }}" class="btn btn-primary btn-sm">Kelola Kuis</a>
            </div>
            
            <div class="table-wrap">
                <table class="lms-table w-full">
                    <thead>
                        <tr>
                            <th>Judul Kuis</th>
                            <th>Waktu</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas->kuis as $kuis)
                            <tr>
                                <td class="font-medium text-[var(--text-primary)]">{{ $kuis->judul }}</td>
                                <td>{{ $kuis->buka_at ? $kuis->buka_at->format('d M Y, H:i') : '-' }}</td>
                                <td>{{ $kuis->durasi_menit }} Menit</td>
                                <td>
                                    @php $status = $kuis->statusLabel(); @endphp
                                    <span class="badge {{ match($status) { 'aktif' => 'badge-green', 'terjadwal' => 'badge-blue', 'selesai' => 'badge-red', default => 'badge-gray' } }} capitalize">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dosen.kuis.detail', ['kelas' => $kelas, 'kuis' => $kuis->id]) }}" class="btn btn-sm btn-outline">
                                        Kelola Kuis
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Belum ada kuis</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        @elseif($activeTab === 'absensi')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-[var(--text-primary)]">Ringkasan Absensi</h2>
                <a href="{{ route('dosen.absensi.index', ['kelas' => $kelas]) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-clipboard-list mr-1"></i> Kelola Absensi
                </a>
            </div>
            
            <div class="card p-0 overflow-hidden">
                <div class="table-wrap">
                    <table class="lms-table w-full">
                        <thead>
                            <tr>
                                <th class="w-10">No</th>
                                <th>Pertemuan</th>
                                <th>Tanggal</th>
                                <th>Status / Materi</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelas->pertemuan as $p)
                                <tr>
                                    <td class="text-center text-[var(--text-muted)]">{{ $p->nomor }}</td>
                                    <td class="font-medium text-[var(--text-primary)]">{{ $p->topik ?: 'Pertemuan '.$p->nomor }}</td>
                                    <td class="text-[var(--text-secondary)]">
                                        @if($p->tanggal)
                                            {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                                        @else
                                            <span class="text-xs text-[var(--text-muted)]">Belum dijadwalkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-teal">{{ $p->konten->count() }} Konten</span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('dosen.absensi.index', ['kelas' => $kelas]) }}?pertemuan={{ $p->id }}" class="btn btn-sm btn-outline text-teal-600 border-teal-500 hover:bg-[var(--teal-dim)]">
                                            Isi Kehadiran
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-[var(--text-muted)]">
                                        Belum ada pertemuan. Tambahkan pertemuan di tab Materi & Konten.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        @elseif($activeTab === 'nilai')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-[var(--text-primary)]">Nilai Akhir Mahasiswa</h2>
                <a href="{{ route('dosen.nilai.index', ['kelas' => $kelas]) }}" class="btn btn-sm btn-outline">
                    <i class="fas fa-external-link-alt mr-1"></i> Halaman Nilai Lengkap
                </a>
            </div>
            @if(count($nilaiList) > 0)
                <div class="card p-0 overflow-hidden">
                    <div class="table-wrap">
                        <table class="lms-table w-full">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th class="text-center">Tugas</th>
                                    <th class="text-center">Kuis</th>
                                    <th class="text-center">Kehadiran</th>
                                    <th class="text-center">Nilai Akhir</th>
                                    <th class="text-center">Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nilaiList as $nilai)
                                    <tr>
                                        <td class="font-medium text-[var(--text-primary)]">{{ $nilai->mahasiswa->name ?? '-' }}</td>
                                        <td class="text-center text-[var(--text-secondary)]">{{ round($nilai->nilai_tugas ?? 0, 1) }}</td>
                                        <td class="text-center text-[var(--text-secondary)]">{{ round($nilai->nilai_kuis ?? 0, 1) }}</td>
                                        <td class="text-center text-[var(--text-secondary)]">{{ round($nilai->nilai_kehadiran ?? 0, 1) }}</td>
                                        <td class="text-center font-bold text-[var(--text-primary)]">{{ round($nilai->nilai_akhir ?? 0, 2) }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ match($nilai->grade) { 'A' => 'badge-green', 'B' => 'badge-teal', 'C' => 'badge-orange', default => 'badge-red' } }}">
                                                {{ $nilai->grade ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card p-10 text-center">
                    <div class="text-[var(--text-muted)] mb-3 opacity-40"><i class="fas fa-chart-bar text-5xl"></i></div>
                    <p class="text-[var(--text-secondary)] font-medium">Belum ada data nilai akhir.</p>
                    <p class="text-[var(--text-muted)] text-sm mt-1">Nilai akan otomatis dihitung setelah tugas/kuis dinilai.</p>
                    <a href="{{ route('dosen.nilai.index', ['kelas' => $kelas]) }}" class="btn btn-primary mt-4 inline-flex">
                        <i class="fas fa-external-link-alt mr-2"></i> Buka Manajemen Nilai
                    </a>
                </div>
            @endif
            
        @elseif($activeTab === 'mahasiswa')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Daftar Mahasiswa ({{ $kelas->mahasiswa->count() }})</h2>
                <button class="btn btn-outline btn-sm">Export Excel</button>
            </div>
            
            <div class="table-wrap">
                <table class="lms-table w-full">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Progress Materi</th>
                            <th>Tugas Selesai</th>
                            <th>Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas->mahasiswa as $mhs)
                            <tr>
                                <td class="font-medium text-gray-700">{{ $mhs->nim ?? '-' }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold">
                                            {{ substr($mhs->name, 0, 2) }}
                                        </div>
                                        <span>{{ $mhs->name }}</span>
                                    </div>
                                </td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Belum ada mahasiswa terdaftar</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
