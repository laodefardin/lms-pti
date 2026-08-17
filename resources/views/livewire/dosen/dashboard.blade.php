<div class="fade-in">

    {{-- ── Greeting ──────────────────────────────────────────────── --}}
    <div style="margin-bottom:1.75rem; display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.4rem; font-weight:800; color:var(--text-primary); margin-bottom:0.25rem;">
                Selamat Datang, {{ explode(' ', $dosen->name)[0] }}!
            </h1>
            <p style="color:var(--text-secondary); font-size:0.875rem;">
                {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }} — Semester Aktif
            </p>
        </div>
        <a href="{{ route('dosen.matakuliah.buat') }}" class="btn btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Buat Kelas Baru
        </a>
    </div>

    {{-- ── Stat Cards ─────────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(190px, 1fr)); gap:1rem; margin-bottom:2rem;">
        <div class="card card-teal stat-card">
            <div class="stat-icon stat-icon-teal"><i class="fas fa-book"></i></div>
            <div><div class="stat-value">{{ $kelasList->count() }}</div><div class="stat-label">Kelas Aktif</div></div>
        </div>
        <div class="card card-green stat-card">
            <div class="stat-icon stat-icon-green"><i class="fas fa-user-graduate"></i></div>
            <div><div class="stat-value">{{ $totalMahasiswa }}</div><div class="stat-label">Total Mahasiswa</div></div>
        </div>
        <div class="card card-orange stat-card">
            <div class="stat-icon stat-icon-orange"><i class="fas fa-edit"></i></div>
            <div><div class="stat-value">{{ $tugasBelumDinilai->count() }}</div><div class="stat-label">Perlu Dinilai</div></div>
        </div>
        <div class="card card-purple stat-card">
            <div class="stat-icon stat-icon-purple"><i class="fas fa-bolt text-yellow-500"></i></div>
            <div><div class="stat-value">{{ $kuisAktif }}</div><div class="stat-label">Kuis Berjalan</div></div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">

        {{-- Kelas yang Diampu --}}
        <div class="card">
            <div class="section-header" style="margin-bottom:1rem;">
                <div class="section-title" style="font-size:0.9rem;"><i class="fas fa-book"></i> Kelas Saya</div>
                <a href="{{ route('dosen.matakuliah.index') }}" class="btn btn-ghost btn-sm">Kelola</a>
            </div>
            @forelse($kelasList as $kelas)
            <a href="{{ route('dosen.matakuliah.detail', $kelas) }}"
               style="display:flex; align-items:center; justify-content:space-between; padding:0.75rem; border-radius:10px; background:var(--input-bg); margin-bottom:0.4rem; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='var(--border)'" onmouseout="this.style.background='var(--input-bg)'">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <div style="width:36px; height:36px; background:#14a7a0; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:0.9rem; flex-shrink:0;"><i class="fas fa-laptop-code"></i></div>
                    <div>
                        <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary);">{{ $kelas->mataKuliah->nama }}</div>
                        <div style="font-size:0.7rem; color:var(--text-secondary);">Kelas {{ $kelas->nama_kelas }} · {{ $kelas->mahasiswa->count() }} mahasiswa</div>
                    </div>
                </div>
                <span class="badge badge-teal">{{ $kelas->mataKuliah->sks }} SKS</span>
            </a>
            @empty
            <div style="text-align:center; padding:2rem; color:var(--text-secondary); font-size:0.85rem;">
                Belum ada kelas aktif.
            </div>
            @endforelse
        </div>

        {{-- Tugas Perlu Dinilai --}}
        <div class="card">
            <div class="section-header" style="margin-bottom:1rem;">
                <div class="section-title" style="font-size:0.9rem;"><i class="fas fa-edit"></i> Perlu Dinilai</div>
            </div>
            @forelse($tugasBelumDinilai as $submission)
            <a href="{{ route('dosen.tugas.nilai', [$submission->tugas->kelas, $submission->tugas]) }}"
               style="display:flex; align-items:center; justify-content:space-between; padding:0.7rem; border-radius:10px; background:var(--input-bg); margin-bottom:0.4rem; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='var(--border)'" onmouseout="this.style.background='var(--input-bg)'">
                <div>
                    <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary); margin-bottom:2px;">{{ Str::limit($submission->mahasiswa->name, 22) }}</div>
                    <div style="font-size:0.7rem; color:var(--text-secondary);">{{ Str::limit($submission->tugas->judul, 28) }}</div>
                </div>
                <span class="badge badge-orange">Belum dinilai</span>
            </a>
            @empty
            <div style="text-align:center; padding:2rem; color:var(--text-secondary); font-size:0.85rem;">
                <i class="fas fa-glass-cheers"></i> Semua tugas sudah dinilai!
            </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card">
        <div class="section-title" style="margin-bottom:1rem; font-size:0.9rem;"><i class="fas fa-bolt text-yellow-500"></i> Aksi Cepat</div>
        <div style="display:flex; flex-wrap:wrap; gap:0.75rem;">
            @foreach($kelasList->take(3) as $kelas)
            <a href="{{ route('dosen.materi.buat', $kelas) }}" class="btn btn-outline btn-sm">
                + Materi · {{ Str::limit($kelas->mataKuliah->nama, 18) }}
            </a>
            <a href="{{ route('dosen.absensi.index', $kelas) }}" class="btn btn-ghost btn-sm">
                <i class="fas fa-clipboard-check"></i> Absensi · {{ $kelas->mataKuliah->kode }}
            </a>
            @endforeach
        </div>
    </div>

</div>