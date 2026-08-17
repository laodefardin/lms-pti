<div class="fade-in">

    {{-- ── Greeting ────────────────────────────────────────────── --}}
    <div style="margin-bottom:2rem; display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:800; color:var(--text-primary); margin-bottom:0.25rem;">
                Selamat datang, {{ explode(' ', $dosen->name)[0] }} 👋
            </h1>
            <p style="color:var(--text-secondary); font-size:0.875rem;">
                Semangat mengajar hari ini!
            </p>
        </div>
        <a href="{{ route('dosen.matakuliah.buat') }}" style="background:var(--teal); color:white; padding:0.6rem 1.25rem; border-radius:8px; font-weight:600; font-size:0.875rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; transition:background 0.2s;" onmouseover="this.style.background='var(--teal-dark)'" onmouseout="this.style.background='var(--teal)'">
            <i class="fas fa-plus"></i> Buat Kelas Baru
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
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $kuisAktif }}</div>
            </div>
        </div>

    </div>

    {{-- ── Main Grid Layout ─────────────────────────────────────── --}}
    <div style="gap:1.5rem;" class="grid grid-cols-1 lg:grid-cols-12">
        
        {{-- LEFT COLUMN (8 cols) --}}
        <div class="lg:col-span-8" style="display:flex; flex-direction:column; gap:1.5rem;">
            
            {{-- Kelas yang Diampu --}}
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Kelas yang Diampu</h2>
                    <a href="{{ route('dosen.matakuliah.index') }}" style="font-size:0.8rem; color:#3b82f6; font-weight:600; text-decoration:none;">Kelola kelas &rarr;</a>
                </div>
                
                @if($kelasList->isEmpty())
                    <div class="card" style="text-align:center; padding:3rem;">
                        <div style="font-size:3rem; margin-bottom:1rem; color:var(--text-muted);"><i class="fas fa-folder-open"></i></div>
                        <div style="color:var(--text-secondary); font-size:0.9rem;">Belum ada kelas aktif.</div>
                    </div>
                @else
                    <div style="gap:1rem;" class="grid grid-cols-1 md:grid-cols-3">
                        @foreach($kelasList->take(3) as $kelas)
                        <div class="card" style="padding:0; overflow:hidden; border:none; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                            {{-- Image header (dark blue gradient) --}}
                            <div style="height:100px; background:linear-gradient(135deg, var(--teal), var(--teal-dark)); padding:1rem; position:relative; color:white;">
                                <div style="position:absolute; top:1rem; right:1rem;"><i class="fas fa-ellipsis-v"></i></div>
                                <span style="background:rgba(255,255,255,0.2); backdrop-filter:blur(4px); padding:0.2rem 0.6rem; border-radius:4px; font-size:0.7rem; font-weight:600; position:absolute; bottom:1rem; left:1rem;">{{ $kelas->mahasiswa->count() }} Mhs</span>
                            </div>
                            {{-- Content --}}
                            <div style="padding:1.25rem; background:var(--bg-card);">
                                <div style="font-size:1rem; font-weight:700; color:var(--text-primary); margin-bottom:0.25rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $kelas->mataKuliah->nama }}">{{ $kelas->mataKuliah->nama }}</div>
                                <div style="font-size:0.75rem; color:var(--text-secondary); margin-bottom:1rem;">Kelas {{ $kelas->nama_kelas }} &bull; {{ $kelas->mataKuliah->sks }} SKS</div>
                                
                                <div style="display:flex; gap:0.5rem;">
                                    <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" style="flex:1; text-align:center; background:var(--teal); color:white; font-size:0.8rem; font-weight:600; padding:0.5rem; border-radius:8px; text-decoration:none;">Kelola</a>
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