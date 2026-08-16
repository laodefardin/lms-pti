<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Matakuliah Saya</h1>
            <p class="text-muted">Kelola kelas dan materi perkuliahan Anda</p>
        </div>
        <button class="btn btn-primary">Buat Kelas Baru</button>
    </div>

    <div class="mb-6 max-w-md">
        <input type="text" wire:model.live="search" class="form-input" placeholder="Cari matakuliah atau kode...">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kelasList as $index => $kelas)
            @php
                $colors = ['var(--teal)', 'var(--orange)', 'var(--green)', 'var(--purple)'];
                $bgColor = $colors[$index % count($colors)];
            @endphp
            <div class="card h-full flex flex-col hover:-translate-y-1 transition-transform" style="--shadow-hover: 0 10px 15px -3px rgba(0,0,0,0.1);">
                <div class="h-32 rounded-t-lg relative flex items-center justify-center p-4" style="background: linear-gradient(135deg, {{ $bgColor }}, color-mix(in srgb, {{ $bgColor }} 80%, black));">
                    <span class="badge bg-white text-gray-800 absolute top-3 right-3">{{ $kelas->mataKuliah->sks ?? 0 }} SKS</span>
                    <h3 class="text-white font-bold text-xl text-center line-clamp-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);">{{ $kelas->mataKuliah->nama ?? 'Unknown MK' }}</h3>
                </div>
                
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-sm font-semibold text-gray-500">{{ $kelas->mataKuliah->kode ?? '-' }}</span>
                        <span class="badge badge-teal">{{ $kelas->nama }}</span>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-calendar-alt w-5 text-center mr-1" style="color: var(--teal)"></i> {{ $kelas->hari }}, {{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}
                    </p>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-users w-5 text-center mr-1" style="color: var(--teal)"></i> {{ $kelas->mahasiswa_count }} Mahasiswa
                    </p>

                    <div class="mt-auto space-y-3">
                        <div class="grid grid-cols-3 gap-2">
                            <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas->id, 'tab' => 'materi']) }}" class="btn btn-outline btn-sm text-center px-1" style="font-size: 0.75rem;">Materi</a>
                            <a href="{{ route('dosen.tugas.index', ['kelas' => $kelas->id]) }}" class="btn btn-outline btn-sm text-center px-1" style="font-size: 0.75rem;">Tugas</a>
                            <a href="{{ route('dosen.absensi.index', ['kelas' => $kelas->id]) }}" class="btn btn-outline btn-sm text-center px-1" style="font-size: 0.75rem;">Absensi</a>
                        </div>
                        <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas->id]) }}" class="btn btn-primary btn-full justify-center">Kelola Kelas</a>
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
</div>
