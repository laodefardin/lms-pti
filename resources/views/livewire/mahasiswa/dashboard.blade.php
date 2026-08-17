<div class="fade-in">

    {{-- ── Greeting ────────────────────────────────────────────── --}}
    <div style="margin-bottom:2rem;">
        <h1 style="font-size:1.5rem; font-weight:800; color:var(--text-primary); margin-bottom:0.25rem;">
            Selamat datang kembali, {{ auth()->user()->name }} 👋
        </h1>
        <p style="color:var(--text-secondary); font-size:0.875rem;">
            Terus semangat belajar dan raih prestasi terbaikmu!
        </p>
    </div>

    {{-- ── Stat Cards ─────────────────────────────────────────── --}}
    <div style="gap:1.5rem; margin-bottom:2rem;" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
        
        {{-- Matakuliah Aktif --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(59,130,246,0.1); color:#3b82f6; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-book-open"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Mata Kuliah Aktif</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $jumlahKelas }}</div>
            </div>
            <a href="{{ route('mahasiswa.matakuliah.index') }}" style="font-size:0.75rem; color:#3b82f6; font-weight:600; text-decoration:none;">Lihat semua &rarr;</a>
        </div>

        {{-- Tugas Belum Selesai --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(16,185,129,0.1); color:#10b981; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Tugas Mendatang</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $jumlahTugas }}</div>
            </div>
            <a href="{{ route('mahasiswa.tugas.index') }}" style="font-size:0.75rem; color:#3b82f6; font-weight:600; text-decoration:none;">Lihat semua &rarr;</a>
        </div>

        {{-- Kuis Tersedia --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(245,158,11,0.1); color:#f59e0b; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-bell"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Kuis Tersedia</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ count($kuisTerbuka) }}</div>
            </div>
            <a href="{{ route('mahasiswa.kuis.index') }}" style="font-size:0.75rem; color:#3b82f6; font-weight:600; text-decoration:none;">Lihat semua &rarr;</a>
        </div>

        {{-- Rata-rata Nilai (Total Poin) --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(99,102,241,0.1); color:#6366f1; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Total Poin</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ auth()->user()->totalPoin() }}</div>
            </div>
            <a href="#" style="font-size:0.75rem; color:#3b82f6; font-weight:600; text-decoration:none;">Lihat detail &rarr;</a>
        </div>

    </div>

    {{-- ── Main Grid Layout ─────────────────────────────────────── --}}
    <div style="gap:1.5rem;" class="grid grid-cols-1 lg:grid-cols-12">
        
        {{-- LEFT COLUMN (8 cols) --}}
        <div class="lg:col-span-8" style="display:flex; flex-direction:column; gap:1.5rem;">
            
            {{-- Mata Kuliah Saya --}}
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Mata Kuliah Saya</h2>
                    <a href="{{ route('mahasiswa.matakuliah.index') }}" style="font-size:0.8rem; color:#3b82f6; font-weight:600; text-decoration:none;">Lihat semua mata kuliah &rarr;</a>
                </div>
                
                @if($kelasList->isEmpty())
                    <div class="card" style="text-align:center; padding:3rem;">
                        <div style="font-size:3rem; margin-bottom:1rem; color:var(--text-muted);"><i class="fas fa-folder-open"></i></div>
                        <div style="color:var(--text-secondary); font-size:0.9rem;">Belum ada matakuliah yang terdaftar.</div>
                    </div>
                @else
                    <div style="gap:1rem;" class="grid grid-cols-1 md:grid-cols-3">
                        @foreach($progress->take(3) as $item)
                        @php $kelas = $item['kelas']; $persen = $item['persen']; @endphp
                        <div class="card" style="padding:0; overflow:hidden; border:none; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                            {{-- Image header (dark blue gradient) --}}
                            <div style="height:100px; background:linear-gradient(135deg, var(--teal), var(--teal-dark)); padding:1rem; position:relative; color:white;">
                                <div style="position:absolute; top:1rem; right:1rem;"><i class="fas fa-ellipsis-v"></i></div>
                                <span style="background:rgba(255,255,255,0.2); backdrop-filter:blur(4px); padding:0.2rem 0.6rem; border-radius:4px; font-size:0.7rem; font-weight:600; position:absolute; bottom:1rem; left:1rem;">Semester {{ $kelas->mataKuliah->semester ?? 1 }}</span>
                            </div>
                            {{-- Content --}}
                            <div style="padding:1.25rem; background:var(--bg-card);">
                                <div style="font-size:1rem; font-weight:700; color:var(--text-primary); margin-bottom:0.25rem;">{{ $kelas->mataKuliah->nama }}</div>
                                <div style="font-size:0.75rem; color:var(--text-secondary); margin-bottom:1rem;">Dosen: {{ $kelas->dosen->name }}</div>
                                
                                <div style="display:flex; justify-content:space-between; font-size:0.75rem; font-weight:700; color:var(--text-primary); margin-bottom:0.4rem;">
                                    <span>{{ $persen }}% Selesai</span>
                                    <span style="color:var(--text-muted);">100%</span>
                                </div>
                                <div style="height:6px; background:var(--bg-body); border-radius:99px; margin-bottom:1rem; overflow:hidden;">
                                    <div style="height:100%; width:{{ $persen }}%; background:#fcb900; border-radius:99px;"></div>
                                </div>
                                
                                <div style="display:flex; gap:0.5rem;">
                                    <a href="#" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center; border:1px solid var(--border); border-radius:8px; color:var(--text-secondary); text-decoration:none;"><i class="far fa-folder-open"></i></a>
                                    <a href="{{ route('mahasiswa.matakuliah.detail', $kelas) }}" style="flex:1; display:flex; align-items:center; justify-content:center; background:var(--teal); color:white; font-size:0.8rem; font-weight:600; border-radius:8px; text-decoration:none; transition:background 0.2s;" onmouseover="this.style.background='var(--teal-dark)'" onmouseout="this.style.background='var(--teal)'">Lanjutkan</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Grid for Aktivitas & Pengumuman --}}
            <div style="gap:1.5rem;" class="grid grid-cols-1 md:grid-cols-12">
                
                {{-- Aktivitas Belajar (Chart Placeholder) --}}
                <div class="card md:col-span-7" style="padding:1.5rem; display:flex; flex-direction:column;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                        <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Aktivitas Belajar</h2>
                        <select style="font-size:0.8rem; padding:0.3rem 0.5rem; border:1px solid var(--border); border-radius:6px; background:var(--bg-card); outline:none;">
                            <option>Minggu Ini</option>
                            <option>Bulan Ini</option>
                        </select>
                    </div>
                    <div style="flex:1; width:100%; min-height:180px; border:1px dashed var(--border); border-radius:12px; display:flex; align-items:center; justify-content:center; background:var(--bg-body); margin-bottom:1.5rem; position:relative; overflow:hidden;">
                        {{-- Dummy Chart using SVG --}}
                        <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="position:absolute; width:100%; height:80%; bottom:0; left:0;">
                            <path d="M0 100 Q 50 20 100 80 T 200 40 T 300 90 T 400 30 T 500 70 L 500 150 L 0 150 Z" fill="rgba(59,130,246,0.1)"></path>
                            <path d="M0 100 Q 50 20 100 80 T 200 40 T 300 90 T 400 30 T 500 70" fill="none" stroke="#3b82f6" stroke-width="2"></path>
                            <path d="M0 120 Q 50 80 100 110 T 200 70 T 300 100 T 400 60 T 500 90" fill="none" stroke="#fcb900" stroke-width="2"></path>
                        </svg>
                        <div style="position:absolute; text-align:center; color:var(--text-muted); background:var(--bg-topbar); padding:0.5rem 1rem; border-radius:8px; font-size:0.75rem;">
                            <i class="fas fa-chart-line" style="margin-right:0.5rem;"></i> Grafik Aktivitas
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1rem; text-align:center; margin-top:auto;">
                        <div>
                            <div style="font-size:1.1rem; font-weight:800; color:var(--text-primary);">28<span style="font-size:0.7rem; color:var(--text-secondary); font-weight:500;">j</span> 15<span style="font-size:0.7rem; color:var(--text-secondary); font-weight:500;">m</span></div>
                            <div style="font-size:0.65rem; color:var(--text-secondary);">Total Belajar</div>
                        </div>
                        <div>
                            <div style="font-size:1.1rem; font-weight:800; color:var(--text-primary);">9</div>
                            <div style="font-size:0.65rem; color:var(--text-secondary);">Tugas Selesai</div>
                        </div>
                        <div>
                            <div style="font-size:1.1rem; font-weight:800; color:var(--text-primary);">3</div>
                            <div style="font-size:0.65rem; color:var(--text-secondary);">Kuis Selesai</div>
                        </div>
                    </div>
                </div>

                {{-- Pengumuman Terbaru --}}
                <div class="md:col-span-5">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                        <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Pengumuman</h2>
                        <a href="#" style="font-size:0.8rem; color:#3b82f6; font-weight:600; text-decoration:none;">Lihat semua &rarr;</a>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:0.75rem;">
                        {{-- Dummy Item 1 --}}
                        <div class="card" style="padding:1rem; display:flex; align-items:center; gap:0.75rem;">
                            <div style="width:36px; height:36px; border-radius:50%; background:rgba(59,130,246,0.1); color:#3b82f6; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><i class="fas fa-bullhorn"></i></div>
                            <div style="flex:1;">
                                <div style="font-size:0.85rem; font-weight:600; color:var(--text-primary); margin-bottom:0.1rem; line-height:1.2;">Jadwal UTS Genap 2025/2026</div>
                                <div style="font-size:0.7rem; color:var(--text-secondary);">16 Mei &bull; Akademik</div>
                            </div>
                        </div>
                        {{-- Dummy Item 2 --}}
                        <div class="card" style="padding:1rem; display:flex; align-items:center; gap:0.75rem;">
                            <div style="width:36px; height:36px; border-radius:50%; background:rgba(16,185,129,0.1); color:#10b981; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><i class="fas fa-graduation-cap"></i></div>
                            <div style="flex:1;">
                                <div style="font-size:0.85rem; font-weight:600; color:var(--text-primary); margin-bottom:0.1rem; line-height:1.2;">Pengumpulan Tugas Akhir</div>
                                <div style="font-size:0.7rem; color:var(--text-secondary);">12 Mei &bull; Akademik</div>
                            </div>
                        </div>
                        {{-- Dummy Item 3 --}}
                        <div class="card" style="padding:1rem; display:flex; align-items:center; gap:0.75rem;">
                            <div style="width:36px; height:36px; border-radius:50%; background:rgba(139,92,246,0.1); color:#8b5cf6; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><i class="fas fa-info-circle"></i></div>
                            <div style="flex:1;">
                                <div style="font-size:0.85rem; font-weight:600; color:var(--text-primary); margin-bottom:0.1rem; line-height:1.2;">Perubahan Jadwal Kuliah</div>
                                <div style="font-size:0.7rem; color:var(--text-secondary);">10 Mei &bull; Akademik</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN (4 cols) --}}
        <div class="lg:col-span-4" style="display:flex; flex-direction:column; gap:1.5rem;">
            
            {{-- Kalender Placeholder --}}
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Kalender</h2>
                    <a href="{{ route('mahasiswa.kalender.index') }}" style="font-size:0.8rem; color:#3b82f6; font-weight:600; text-decoration:none;">Lihat kalender &rarr;</a>
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

            {{-- Tugas Mendatang --}}
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Tugas Mendatang</h2>
                    <a href="{{ route('mahasiswa.tugas.index') }}" style="font-size:0.8rem; color:#3b82f6; font-weight:600; text-decoration:none;">Lihat semua &rarr;</a>
                </div>
                
                <div style="display:flex; flex-direction:column; gap:1.25rem;">
                    @forelse($tugas->take(3) as $t)
                    <div style="display:flex; gap:1rem;">
                        <div style="display:flex; flex-direction:column; align-items:center; min-width:40px;">
                            <span style="font-size:1.1rem; font-weight:800; color:#3b82f6; line-height:1;">{{ $t->deadline->format('d') }}</span>
                            <span style="font-size:0.7rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase;">{{ $t->deadline->locale('id')->shortMonthName }}</span>
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:0.9rem; font-weight:700; color:var(--text-primary); margin-bottom:0.1rem; line-height:1.3;"><a href="{{ route('mahasiswa.tugas.detail', $t) }}" style="color:inherit; text-decoration:none;">{{ Str::limit($t->judul, 25) }}</a></div>
                            <div style="font-size:0.75rem; color:var(--text-secondary); margin-bottom:0.2rem;">{{ $t->kelas->mataKuliah->nama }}</div>
                            <div style="font-size:0.7rem; color:var(--text-muted);"><i class="far fa-clock"></i> {{ $t->deadline->format('H:i') }} WIB</div>
                        </div>
                        @php
                            $diff = now()->diffInHours($t->deadline);
                            $badgeColor = $diff < 24 ? '#ef4444' : '#10b981';
                            $badgeBg = $diff < 24 ? 'rgba(239,68,68,0.1)' : 'rgba(16,185,129,0.1)';
                            $badgeText = $diff < 24 ? 'Segera' : 'Tugas';
                        @endphp
                        <div>
                            <span style="background:{{ $badgeBg }}; color:{{ $badgeColor }}; padding:0.2rem 0.5rem; border-radius:99px; font-size:0.65rem; font-weight:600;">{{ $badgeText }}</span>
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center; padding:1rem 0; color:var(--text-secondary); font-size:0.85rem;">
                        Tidak ada tugas mendatang.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>
