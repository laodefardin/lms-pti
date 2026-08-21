<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Matakuliah Saya</h1>
            <p class="text-muted">Kelola kelas dan materi perkuliahan Anda</p>
        </div>
        <a href="{{ route('dosen.matakuliah.buat') }}" wire:navigate class="btn btn-primary"><i class="fas fa-plus mr-2"></i> Buka Kelas Baru</a>
    </div>

    <div class="mb-6 max-w-md">
        <input type="text" wire:model.live="search" class="form-input" placeholder="Cari matakuliah atau kode...">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($kelasList as $index => $kelas)
            @php
                $gradients = [
                    ['from' => '#1a75d1', 'to' => '#0d559e', 'icon' => 'fa-laptop-code'],
                    ['from' => '#f59e0b', 'to' => '#d97706', 'icon' => 'fa-book-open'],
                    ['from' => '#8b5cf6', 'to' => '#6d28d9', 'icon' => 'fa-flask'],
                    ['from' => '#10b981', 'to' => '#059669', 'icon' => 'fa-chart-bar'],
                    ['from' => '#ef4444', 'to' => '#dc2626', 'icon' => 'fa-pen-nib'],
                    ['from' => '#06b6d4', 'to' => '#0891b2', 'icon' => 'fa-globe'],
                    ['from' => '#ec4899', 'to' => '#db2777', 'icon' => 'fa-brain'],
                    ['from' => '#f97316', 'to' => '#ea580c', 'icon' => 'fa-calculator'],
                ];
                $g = $gradients[$kelas->id % count($gradients)];
            @endphp
            <div class="card h-full flex flex-col hover:-translate-y-1 transition-transform" style="--shadow-hover: 0 10px 15px -3px rgba(0,0,0,0.1);">
                <div class="h-36 rounded-t-lg relative overflow-hidden flex items-center justify-center"
                    style="background: linear-gradient(135deg, {{ $g['from'] }}, {{ $g['to'] }});">

                    @if($kelas->thumbnail)
                        {{-- Real thumbnail --}}
                        <img src="{{ asset('storage/' . $kelas->thumbnail) }}"
                             alt="{{ $kelas->mataKuliah->nama ?? '' }}"
                             class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 60%);"></div>
                        <span class="badge bg-white/20 backdrop-blur text-white absolute top-3 right-3 border border-white/30">{{ $kelas->mataKuliah->sks ?? 0 }} SKS</span>
                        <h3 class="text-white font-bold text-lg text-center absolute bottom-3 left-3 right-3 line-clamp-2 drop-shadow-lg">{{ $kelas->mataKuliah->nama ?? 'Unknown MK' }}</h3>
                    @else
                        {{-- Default gradient with decorative elements --}}
                        {{-- Decorative circles --}}
                        <div class="absolute" style="width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.08); top:-30px; right:-30px;"></div>
                        <div class="absolute" style="width:80px; height:80px; border-radius:50%; background:rgba(255,255,255,0.08); bottom:-20px; left:-20px;"></div>

                        {{-- Floating icon (bottom right, large) --}}
                        <i class="fas {{ $g['icon'] }} absolute"
                           style="font-size: 5rem; color: rgba(255,255,255,0.15); bottom: -8px; right: 8px; transform: rotate(-10deg);"></i>

                        {{-- Course name & badge --}}
                        <span class="badge bg-white/20 backdrop-blur text-white absolute top-3 right-3 border border-white/30">{{ $kelas->mataKuliah->sks ?? 0 }} SKS</span>
                        <div class="absolute bottom-3 left-4 right-4">
                            <h3 class="text-white font-bold text-base line-clamp-2 drop-shadow" style="text-shadow: 0 1px 3px rgba(0,0,0,0.3);">{{ $kelas->mataKuliah->nama ?? 'Unknown MK' }}</h3>
                            <p class="text-white/70 text-xs mt-0.5">{{ $kelas->semester->nama ?? '' }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-sm font-semibold text-gray-500">{{ $kelas->mataKuliah->kode ?? '-' }}</span>
                        <span class="badge badge-teal">Kelas {{ $kelas->nama_kelas }}</span>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-calendar-alt w-5 text-center mr-1" style="color: var(--teal)"></i>
                        {{ $kelas->hari_kuliah ? ucfirst($kelas->hari_kuliah) : '-' }}, {{ $kelas->jam_mulai ?? '--:--' }} - {{ $kelas->jam_selesai ?? '--:--' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-users w-5 text-center mr-1" style="color: var(--teal)"></i> {{ $kelas->mahasiswa_count }} Mahasiswa
                    </p>

                    <div class="mt-auto space-y-3">
                        <div class="grid grid-cols-3 gap-2">
                            <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas, 'tab' => 'materi']) }}" class="btn btn-outline btn-sm text-center px-1" style="font-size: 0.75rem;">Materi</a>
                            <a href="{{ route('dosen.tugas.index', ['kelas' => $kelas]) }}" class="btn btn-outline btn-sm text-center px-1" style="font-size: 0.75rem;">Tugas</a>
                            <a href="{{ route('dosen.absensi.index', ['kelas' => $kelas]) }}" class="btn btn-outline btn-sm text-center px-1" style="font-size: 0.75rem;">Absensi</a>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas]) }}" class="btn btn-primary flex-1 justify-center">Kelola Kelas</a>
                            <button wire:click="konfirmasiHapus({{ $kelas->id }})" class="btn btn-sm px-3" style="background:rgba(239,68,68,0.1); color:#ef4444; border:1px solid rgba(239,68,68,0.3); border-radius:8px;" title="Hapus Kelas">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full card p-8 text-center">
                <div class="stat-icon stat-icon-teal mx-auto mb-4">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">Tidak Ada Kelas</h3>
                <p class="text-muted mb-4">Anda belum memiliki kelas atau pencarian tidak ditemukan.</p>
            </div>
        @endforelse
    </div>


    {{-- Modal Konfirmasi Hapus (Alpine JS Teleport) --}}
    <div x-data="{ open: @entangle('hapusId') }">
        <template x-teleport="body">
            <div x-show="open" style="display:none;" class="fixed inset-0 z-[9999] flex items-center justify-center">
                {{-- Overlay --}}
                <div x-show="open" x-transition.opacity
                     wire:click="batalHapus"
                     class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                
                {{-- Modal Card --}}
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden bg-[var(--bg-card)] border border-[var(--border)]">
                    
                    {{-- Header --}}
                    <div class="flex items-center gap-4 p-6 border-b border-[var(--border)]">
                        <div class="flex-shrink-0 w-14 h-14 rounded-full flex items-center justify-center bg-red-500/10">
                            <i class="fas fa-trash-alt text-2xl text-red-500"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl text-[var(--text-primary)]">Hapus Kelas?</h3>
                            <p class="text-sm mt-0.5 text-[var(--text-secondary)]">Tindakan ini <strong>tidak dapat dibatalkan</strong></p>
                        </div>
                    </div>
                    
                    {{-- Body --}}
                    <div class="px-6 py-5">
                        <div class="flex items-start gap-3 p-4 rounded-xl bg-red-500/5 border border-red-500/20">
                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                            <p class="text-sm text-[var(--text-secondary)]">
                                Semua data kelas termasuk <strong class="text-[var(--text-primary)]">materi, tugas, absensi, dan nilai</strong> akan ikut terhapus secara permanen.
                            </p>
                        </div>
                    </div>
                    
                    {{-- Footer --}}
                    <div class="flex gap-3 justify-end px-6 pb-6">
                        <button wire:click="batalHapus" class="btn btn-outline px-5 border-[var(--border)] text-[var(--text-primary)]">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                        <button wire:click="hapusKelas" class="btn px-5 bg-red-500 text-white shadow-[0_4px_14px_rgba(239,68,68,0.4)]" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="hapusKelas">
                                <i class="fas fa-trash mr-2"></i> Ya, Hapus Kelas
                            </span>
                            <span wire:loading wire:target="hapusKelas">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

