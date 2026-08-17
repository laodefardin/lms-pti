<div class="fade-in">

    {{-- ── Greeting ────────────────────────────────────────────── --}}
    <div style="margin-bottom:2rem; display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:800; color:var(--text-primary); margin-bottom:0.25rem;">
                Admin Dashboard 👋
            </h1>
            <p style="color:var(--text-secondary); font-size:0.875rem;">
                Semester Aktif: <span style="font-weight:700; color:var(--text-primary);">{{ $semesterAktif?->nama ?? 'Belum diset' }}</span>
            </p>
        </div>
        <div style="display:flex; gap:0.5rem;">
            <a href="{{ route('admin.mahasiswa.index') }}" style="background:var(--teal); color:white; padding:0.6rem 1rem; border-radius:8px; font-weight:600; font-size:0.875rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; transition:background 0.2s;" onmouseover="this.style.background='var(--teal-dark)'" onmouseout="this.style.background='var(--teal)'">
                <i class="fas fa-plus"></i> Mahasiswa
            </a>
            <a href="{{ route('admin.dosen.index') }}" style="background:var(--bg-card); border:1px solid var(--border); color:var(--text-primary); padding:0.6rem 1rem; border-radius:8px; font-weight:600; font-size:0.875rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; transition:background 0.2s;" onmouseover="this.style.background='var(--input-bg)'" onmouseout="this.style.background='transparent'">
                <i class="fas fa-plus"></i> Dosen
            </a>
        </div>
    </div>

    {{-- ── Stat Cards ─────────────────────────────────────────── --}}
    <div style="gap:1.5rem; margin-bottom:1.5rem;" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
        
        {{-- Total Mahasiswa --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(59,130,246,0.1); color:#3b82f6; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Mahasiswa</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $totalMahasiswa }}</div>
            </div>
        </div>

        {{-- Total Dosen --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(16,185,129,0.1); color:#10b981; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Dosen</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $totalDosen }}</div>
            </div>
        </div>

        {{-- Kelas Aktif --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(245,158,11,0.1); color:#f59e0b; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-book"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Kelas Aktif</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $totalKelas }}</div>
            </div>
        </div>

        {{-- Mahasiswa Baru --}}
        <div class="card" style="display:flex; align-items:center; gap:1.25rem; padding:1.25rem;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(99,102,241,0.1); color:#6366f1; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                <i class="fas fa-user-plus"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Baru (7 Hari)</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $mahasiswaBaru }}</div>
            </div>
        </div>
    </div>

    {{-- ── Main Grid Layout ─────────────────────────────────────── --}}
    <div style="gap:1.5rem;" class="grid grid-cols-1 lg:grid-cols-12">
        
        {{-- LEFT COLUMN (8 cols) --}}
        <div class="lg:col-span-8" style="display:flex; flex-direction:column; gap:1.5rem;">
            
            {{-- Daftar Kelas Aktif --}}
            <div class="card" style="padding:0; overflow:hidden;">
                <div style="padding:1.5rem; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                    <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Kelas Aktif Semester Ini</h2>
                    <a href="{{ route('admin.kelas.index') }}" style="font-size:0.8rem; color:#3b82f6; font-weight:600; text-decoration:none;">Kelola Kelas &rarr;</a>
                </div>
                
                <div class="table-wrap" style="padding:0; margin:0;">
                    <table class="lms-table" style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:var(--bg-body); text-align:left; font-size:0.75rem; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.05em;">
                                <th style="padding:1rem 1.5rem; font-weight:600;">Matakuliah</th>
                                <th style="padding:1rem 1.5rem; font-weight:600;">Dosen</th>
                                <th style="padding:1rem 1.5rem; font-weight:600;">Kelas</th>
                                <th style="padding:1rem 1.5rem; font-weight:600;">Mhs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelasList->take(5) as $kelas)
                            <tr style="border-bottom:1px solid var(--border); transition:background 0.2s;" onmouseover="this.style.background='var(--input-bg)'" onmouseout="this.style.background='transparent'">
                                <td style="padding:1rem 1.5rem;">
                                    <div style="font-weight:700; color:var(--text-primary); font-size:0.9rem;">{{ $kelas->mataKuliah->nama }}</div>
                                    <div style="font-size:0.75rem; color:var(--text-secondary);">{{ $kelas->mataKuliah->kode }} &bull; {{ $kelas->mataKuliah->sks }} SKS</div>
                                </td>
                                <td style="padding:1rem 1.5rem; color:var(--text-secondary); font-size:0.85rem;">{{ Str::limit($kelas->dosen->name, 20) }}</td>
                                <td style="padding:1rem 1.5rem;"><span style="background:var(--bg-body); color:var(--text-secondary); padding:0.2rem 0.6rem; border-radius:4px; font-size:0.75rem; font-weight:600;">{{ $kelas->nama_kelas }}</span></td>
                                <td style="padding:1rem 1.5rem; font-weight:700; color:var(--text-primary);">{{ $kelas->mahasiswa->count() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" style="text-align:center; color:var(--text-secondary); padding:2rem;">Tidak ada kelas aktif.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>

        {{-- RIGHT COLUMN (4 cols) --}}
        <div class="lg:col-span-4" style="display:flex; flex-direction:column; gap:1.5rem;">
            
            {{-- Quick Actions --}}
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">Pintas Admin</h2>
                </div>
                
                <div style="display:flex; flex-direction:column; gap:0.75rem;">
                    @foreach([
                        ['route'=>'admin.semester.index',   'label'=>'Kelola Semester', 'icon'=>'fa-calendar-alt', 'color'=>'#f59e0b', 'bg'=>'rgba(245,158,11,0.1)'],
                        ['route'=>'admin.mata-kuliah.index','label'=>'Mata Kuliah',     'icon'=>'fa-book',         'color'=>'#3b82f6', 'bg'=>'rgba(59,130,246,0.1)'],
                        ['route'=>'admin.laporan.index',    'label'=>'Laporan Sistem',  'icon'=>'fa-chart-bar',    'color'=>'#10b981', 'bg'=>'rgba(16,185,129,0.1)'],
                        ['route'=>'admin.audit-log',        'label'=>'Audit Log',       'icon'=>'fa-search',       'color'=>'#8b5cf6', 'bg'=>'rgba(139,92,246,0.1)'],
                    ] as $action)
                    <a href="{{ route($action['route']) }}" style="display:flex; align-items:center; gap:1rem; padding:1rem; border:1px solid var(--border); border-radius:12px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--border-teal)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='var(--border)'; this.style.transform='none';">
                        <div style="width:40px; height:40px; border-radius:10px; background:{{ $action['bg'] }}; color:{{ $action['color'] }}; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                            <i class="fas {{ $action['icon'] }}"></i>
                        </div>
                        <span style="font-size:0.9rem; font-weight:700; color:var(--text-primary);">{{ $action['label'] }}</span>
                        <i class="fas fa-chevron-right" style="margin-left:auto; color:#cbd5e1; font-size:0.8rem;"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            
            {{-- Additional Stats --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="card" style="padding:1.25rem; text-align:center;">
                    <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); margin-bottom:0.25rem;">{{ $totalMk }}</div>
                    <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase;">Matakuliah</div>
                </div>
                <div class="card" style="padding:1.25rem; text-align:center;">
                    <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary); margin-bottom:0.25rem;">{{ $belumDinilai }}</div>
                    <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase;">Tugas Pending</div>
                </div>
            </div>

        </div>
    </div>

</div>