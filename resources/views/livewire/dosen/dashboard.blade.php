<div class="fade-in">

    {{-- ── Greeting ────────────────────────────────────────────── --}}
    <div style="margin-bottom:2rem; display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:800; color:var(--text-primary); margin-bottom:0.25rem;">
                Selamat datang, {{ $dosen->name }} 👋
            </h1>
            <p style="color:var(--text-secondary); font-size:0.875rem;">
                Semangat mengajar hari ini!
            </p>
        </div>
        <a href="{{ route('dosen.matakuliah.buat') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i> Buat Kelas Baru
        </a>
    </div>

    {{-- ── Stat Cards ─────────────────────────────────────────── --}}
    <div style="gap:1.5rem; margin-bottom:2rem;" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
        
        {{-- Kelas Aktif --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(59,130,246,0.1); color:#3b82f6; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-book"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Kelas Aktif</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $kelasList->count() }}</div>
            </div>
        </div>

        {{-- Total Mahasiswa --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(16,185,129,0.1); color:#10b981; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Total Mahasiswa</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $totalMahasiswa }}</div>
            </div>
        </div>

        {{-- Perlu Dinilai --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(245,158,11,0.1); color:#f59e0b; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-edit"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Perlu Dinilai</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $tugasBelumDinilai->count() }}</div>
            </div>
        </div>

        {{-- Kuis Berjalan --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(99,102,241,0.1); color:#6366f1; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-bolt"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Kuis Berjalan</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">0</div>
            </div>
        </div>

    </div>

    {{-- ── Main Grid ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- LEFT COLUMN (8 cols) --}}
        <div class="lg:col-span-8" style="display:flex; flex-direction:column; gap:1.5rem;">
            
            {{-- Kelas yang Diampu --}}
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Kelas yang Diampu</h2>
                    <a href="{{ route('dosen.matakuliah.index') }}" style="font-size:0.8rem; font-weight:600; color:var(--teal); text-decoration:none;">Lihat Semua</a>
                </div>
                
                @if($kelasList->isEmpty())
                    <div class="card" style="padding:2.5rem; text-align:center;">
                        <div style="width:64px; height:64px; border-radius:50%; background:rgba(139,149,168,0.1); color:var(--text-muted); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin:0 auto 1rem;">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 style="font-weight:700; color:var(--text-primary); margin-bottom:0.25rem;">Tidak Ada Kelas</h3>
                        <p style="font-size:0.875rem; color:var(--text-secondary); margin-bottom:1.5rem;">Anda belum memiliki kelas yang aktif.</p>
                        <a href="{{ route('dosen.matakuliah.buat') }}" class="btn btn-primary" style="display:inline-flex;">Buat Kelas Sekarang</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        @foreach($kelasList->take(3) as $kelas)
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
                                <div class="h-32 rounded-t-lg relative overflow-hidden flex items-center justify-center"
                                    style="background: linear-gradient(135deg, {{ $g['from'] }}, {{ $g['to'] }});">
                
                                    @if($kelas->thumbnail)
                                        <img src="{{ asset('storage/' . $kelas->thumbnail) }}"
                                             alt="{{ $kelas->mataKuliah->nama ?? '' }}"
                                             class="absolute inset-0 w-full h-full object-cover">
                                        <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 60%);"></div>
                                        <span class="badge bg-white/20 backdrop-blur text-white absolute top-3 right-3 border border-white/30">{{ $kelas->mataKuliah->sks ?? 0 }} SKS</span>
                                        <h3 class="text-white font-bold text-lg text-center absolute bottom-3 left-3 right-3 line-clamp-2 drop-shadow-lg">{{ $kelas->mataKuliah->nama ?? 'Unknown MK' }}</h3>
                                    @else
                                        <div class="absolute" style="width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.08); top:-30px; right:-30px;"></div>
                                        <div class="absolute" style="width:80px; height:80px; border-radius:50%; background:rgba(255,255,255,0.08); bottom:-20px; left:-20px;"></div>
                                        
                                        <i class="fas {{ $g['icon'] }} absolute"
                                           style="font-size: 5rem; color: rgba(255,255,255,0.15); bottom: -8px; right: 8px; transform: rotate(-10deg);"></i>
                                           
                                        <span class="badge bg-white/20 backdrop-blur text-white absolute top-3 right-3 border border-white/30">{{ $kelas->mataKuliah->sks ?? 0 }} SKS</span>
                                        <div class="absolute bottom-3 left-4 right-4">
                                            <h3 class="text-white font-bold text-base line-clamp-2 drop-shadow" style="text-shadow: 0 1px 3px rgba(0,0,0,0.3);">{{ $kelas->mataKuliah->nama ?? 'Unknown MK' }}</h3>
                                            <p class="text-white/70 text-xs mt-0.5">{{ $kelas->semester->nama ?? '' }}</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="p-4 flex-1 flex flex-col">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="text-xs font-semibold text-gray-500">{{ $kelas->mataKuliah->kode ?? '-' }}</span>
                                        <span class="badge badge-teal text-xs">Kelas {{ $kelas->nama_kelas }}</span>
                                    </div>
                                    
                                    <p class="text-xs text-gray-600 mb-2">
                                        <i class="fas fa-calendar-alt w-4 text-center mr-1" style="color: var(--teal)"></i>
                                        {{ $kelas->hari_kuliah ? ucfirst($kelas->hari_kuliah) : '-' }}, {{ $kelas->jam_mulai ?? '--:--' }} - {{ $kelas->jam_selesai ?? '--:--' }}
                                    </p>
                                    <p class="text-xs text-gray-600 mb-4">
                                        <i class="fas fa-users w-4 text-center mr-1" style="color: var(--teal)"></i> {{ $kelas->mahasiswa_count }} Mahasiswa
                                    </p>
                
                                    <div class="mt-auto">
                                        <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas]) }}" class="btn btn-primary w-full justify-center btn-sm">Kelola Kelas</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Aksi Cepat --}}
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Aksi Cepat</h2>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:0.75rem;">
                    @foreach($kelasList->take(3) as $kelas)
                    <a href="{{ route('dosen.materi.buat', $kelas) }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; border:1px solid var(--border); border-radius:8px; font-size:0.8rem; font-weight:600; color:var(--text-secondary); text-decoration:none; transition:all 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6';" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-secondary)';">
                        <i class="fas fa-plus"></i> Materi: {{ Str::limit($kelas->mataKuliah->nama, 15) }}
                    </a>
                    <a href="{{ route('dosen.absensi.index', $kelas) }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; border:1px solid var(--border); border-radius:8px; font-size:0.8rem; font-weight:600; color:var(--text-secondary); text-decoration:none; transition:all 0.2s;" onmouseover="this.style.borderColor='#10b981'; this.style.color='#10b981';" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-secondary)';">
                        <i class="fas fa-clipboard-check"></i> Absensi: {{ $kelas->mataKuliah->kode }}
                    </a>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN (4 cols) --}}
        <div class="lg:col-span-4" style="display:flex; flex-direction:column; gap:1.5rem;">
            
            {{-- Kalender Placeholder --}}
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Kalender</h2>
                </div>
                <div style="text-align:center;">
                    <div style="font-weight:700; color:var(--text-primary); margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center;">
                        <i class="fas fa-chevron-left" style="color:var(--text-muted); cursor:pointer;"></i>
                        <span>{{ now()->locale('id')->isoFormat('MMMM YYYY') }}</span>
                        <i class="fas fa-chevron-right" style="color:var(--text-muted); cursor:pointer;"></i>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(7, 1fr); gap:0.5rem; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.5rem;">
                        <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(7, 1fr); gap:0.5rem; font-size:0.85rem; color:var(--text-primary);">
                        {{-- Dummy Calendar Grid --}}
                        @for($i=1; $i<=31; $i++)
                            @if($i == 21)
                                <div style="background:var(--teal); color:white; width:30px; height:30px; display:flex; align-items:center; justify-content:center; border-radius:50%; margin:auto;">{{ $i }}</div>
                            @elseif($i == 16)
                                <div style="background:#fcb900; color:white; width:30px; height:30px; display:flex; align-items:center; justify-content:center; border-radius:50%; margin:auto;">{{ $i }}</div>
                            @else
                                <div style="width:30px; height:30px; display:flex; align-items:center; justify-content:center; margin:auto;">{{ $i }}</div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>

            {{-- Perlu Dinilai --}}
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Perlu Dinilai</h2>
                </div>
                
                <div style="display:flex; flex-direction:column; gap:1.25rem;">
                    @forelse($tugasBelumDinilai->take(4) as $submission)
                    <div style="display:flex; gap:1rem;">
                        <div style="display:flex; flex-direction:column; align-items:center; min-width:40px;">
                            <span style="font-size:1.1rem; font-weight:800; color:#f59e0b; line-height:1;"><i class="fas fa-exclamation-circle"></i></span>
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:0.9rem; font-weight:700; color:var(--text-primary); margin-bottom:0.1rem; line-height:1.3;"><a href="{{ route('dosen.tugas.nilai', [$submission->tugas->kelas, $submission->tugas]) }}" style="color:inherit; text-decoration:none;">{{ Str::limit($submission->mahasiswa->name, 22) }}</a></div>
                            <div style="font-size:0.75rem; color:var(--text-secondary); margin-bottom:0.2rem;">{{ Str::limit($submission->tugas->judul, 28) }}</div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center; padding:1rem 0; color:var(--text-secondary); font-size:0.85rem;">
                        Semua tugas sudah dinilai.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>