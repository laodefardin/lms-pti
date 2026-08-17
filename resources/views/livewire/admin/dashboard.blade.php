<div class="fade-in">

    {{-- ── Header ──────────────────────────────────────────────────── --}}
    <div style="margin-bottom:1.75rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.4rem; font-weight:800; color:var(--text-primary); margin-bottom:0.25rem;">Admin Dashboard</h1>
            <p style="color:var(--text-secondary); font-size:0.875rem;">
                Semester Aktif:
                <span style="color:var(--purple); font-weight:600;">{{ $semesterAktif?->nama ?? 'Belum diset' }}</span>
            </p>
        </div>
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-primary btn-sm" style="background:linear-gradient(135deg,var(--purple),#6d28d9); box-shadow:0 4px 12px rgba(139,92,246,0.4);">+ Mahasiswa</a>
            <a href="{{ route('admin.dosen.index') }}" class="btn btn-outline btn-sm" style="color:var(--purple); border-color:var(--border-purple);">+ Dosen</a>
        </div>
    </div>

    {{-- ── Stat Cards ──────────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; margin-bottom:2rem;">
        <div class="card card-purple stat-card">
            <div class="stat-icon stat-icon-purple"><i class="fas fa-user-graduate"></i></div>
            <div><div class="stat-value">{{ $totalMahasiswa }}</div><div class="stat-label">Mahasiswa</div></div>
        </div>
        <div class="card card-teal stat-card">
            <div class="stat-icon stat-icon-teal"><i class="fas fa-chalkboard-teacher"></i></div>
            <div><div class="stat-value">{{ $totalDosen }}</div><div class="stat-label">Dosen</div></div>
        </div>
        <div class="card card-green stat-card">
            <div class="stat-icon stat-icon-green"><i class="fas fa-book"></i></div>
            <div><div class="stat-value">{{ $totalKelas }}</div><div class="stat-label">Kelas Aktif</div></div>
        </div>
        <div class="card card-orange stat-card">
            <div class="stat-icon stat-icon-orange"><i class="fas fa-edit"></i></div>
            <div><div class="stat-value">{{ $belumDinilai }}</div><div class="stat-label">Tugas Pending</div></div>
        </div>
        <div class="card card-blue stat-card">
            <div class="stat-icon stat-icon-blue"><i class="fas fa-book"></i></div>
            <div><div class="stat-value">{{ $totalMk }}</div><div class="stat-label">Matakuliah</div></div>
        </div>
        <div class="card card-green stat-card">
            <div class="stat-icon stat-icon-green"><i class="fas fa-user-plus"></i></div>
            <div><div class="stat-value">{{ $mahasiswaBaru }}</div><div class="stat-label">Baru (7 Hari)</div></div>
        </div>
    </div>

    {{-- ── Daftar Kelas Aktif ───────────────────────────────────────── --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <div class="section-header" style="margin-bottom:1rem;">
            <div>
                <div class="section-title"><i class="fas fa-clipboard-list"></i> Kelas Aktif Semester Ini</div>
                <div class="section-sub">Semua kelas yang sedang berjalan</div>
            </div>
            <a href="{{ route('admin.kelas.index') }}" class="btn btn-ghost btn-sm">Kelola Kelas →</a>
        </div>

        <div class="table-wrap">
            <table class="lms-table">
                <thead>
                    <tr>
                        <th>Matakuliah</th>
                        <th>Dosen</th>
                        <th>Kelas</th>
                        <th>Mahasiswa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelasList as $kelas)
                    <tr>
                        <td>
                            <div style="font-weight:600; color:var(--text-primary);">{{ $kelas->mataKuliah->nama }}</div>
                            <div style="font-size:0.72rem; color:var(--text-secondary);">{{ $kelas->mataKuliah->kode }} · {{ $kelas->mataKuliah->sks }} SKS</div>
                        </td>
                        <td style="color:var(--text-secondary); font-size:0.85rem;">{{ Str::limit($kelas->dosen->name, 25) }}</td>
                        <td><span class="badge badge-gray">Kelas {{ $kelas->nama_kelas }}</span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <span style="font-weight:600; color:var(--text-primary);">{{ $kelas->mahasiswa->count() }}</span>
                                <span style="font-size:0.72rem; color:var(--text-secondary);">mahasiswa</span>
                            </div>
                        </td>
                        <td><span class="badge badge-green">Aktif</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center; color:var(--text-secondary); padding:2rem;">Tidak ada kelas aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Quick Actions ────────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem;">
        @foreach([
            ['route'=>'admin.semester.index',   'label'=>'Kelola Semester', 'icon'=>'<i class="fas fa-calendar-alt"></i>', 'color'=>'rgba(245,158,11,0.12)', 'border'=>'rgba(245,158,11,0.25)'],
            ['route'=>'admin.mata-kuliah.index','label'=>'Mata Kuliah',     'icon'=>'<i class="fas fa-book"></i>', 'color'=>'rgba(59,130,246,0.12)',  'border'=>'rgba(59,130,246,0.25)'],
            ['route'=>'admin.laporan.index',    'label'=>'Lihat Laporan',   'icon'=>'<i class="fas fa-chart-bar"></i>', 'color'=>'rgba(34,197,94,0.12)',   'border'=>'rgba(34,197,94,0.25)'],
            ['route'=>'admin.audit-log',        'label'=>'Audit Log',       'icon'=>'<i class="fas fa-search"></i>', 'color'=>'rgba(139,92,246,0.12)',  'border'=>'rgba(139,92,246,0.25)'],
        ] as $action)
        <a href="{{ route($action['route']) }}"
           style="background:{{ $action['color'] }}; border:1px solid {{ $action['border'] }}; border-radius:12px; padding:1rem; display:flex; align-items:center; gap:0.75rem; text-decoration:none; transition:all 0.2s;"
           onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
            <span style="font-size:1.5rem;">{!! $action['icon'] !!}</span>
            <span style="font-size:0.85rem; font-weight:600; color:var(--text-primary);">{{ $action['label'] }}</span>
        </a>
        @endforeach
    </div>

</div>