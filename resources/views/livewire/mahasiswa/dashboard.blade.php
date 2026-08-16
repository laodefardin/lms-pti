<div class="fade-in">

    {{-- ── Greeting ────────────────────────────────────────────── --}}
    <div style="margin-bottom:1.75rem;">
        <h1 style="font-size:1.4rem; font-weight:800; color:#f0f4f8; margin-bottom:0.25rem;">
            Selamat Datang Kembali, {{ explode(' ', $user->name)[0] }}! 👋
        </h1>
        <p style="color:#8b95a8; font-size:0.875rem;">
            {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }} —
            Semangat belajar hari ini!
        </p>
    </div>

    {{-- ── Stat Cards ─────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin-bottom:2rem;">

        {{-- Matakuliah --}}
        <div class="card card-teal stat-card">
            <div class="stat-icon stat-icon-teal">📚</div>
            <div>
                <div class="stat-value">{{ $jumlahKelas }}</div>
                <div class="stat-label">Matakuliah Aktif</div>
            </div>
        </div>

        {{-- Materi Selesai --}}
        <div class="card card-green stat-card">
            <div class="stat-icon stat-icon-green">✅</div>
            <div>
                <div class="stat-value">{{ $totalSelesai }}</div>
                <div class="stat-label">Materi Dipelajari</div>
            </div>
        </div>

        {{-- Tugas Pending --}}
        <div class="card card-orange stat-card">
            <div class="stat-icon stat-icon-orange">📝</div>
            <div>
                <div class="stat-value">{{ $jumlahTugas }}</div>
                <div class="stat-label">Tugas Mendatang</div>
            </div>
        </div>

        {{-- Poin --}}
        <div class="card card-purple stat-card">
            <div class="stat-icon stat-icon-purple">⭐</div>
            <div>
                <div class="stat-value">{{ $user->totalPoin() }}</div>
                <div class="stat-label">Total Poin</div>
            </div>
        </div>
    </div>

    {{-- ── Lanjutkan Belajar ─────────────────────────────────── --}}
    <div class="section-header" style="margin-bottom:1rem;">
        <div>
            <div class="section-title">Lanjutkan Belajar</div>
            <div class="section-sub">Matakuliah yang sedang kamu ikuti</div>
        </div>
        <a href="{{ route('mahasiswa.matakuliah.index') }}" class="btn btn-ghost btn-sm">Lihat Semua →</a>
    </div>

    @if($kelasList->isEmpty())
        <div class="card" style="text-align:center; padding:3rem;">
            <div style="font-size:3rem; margin-bottom:1rem;">📚</div>
            <div style="color:#8b95a8; font-size:0.9rem;">Kamu belum terdaftar di matakuliah apapun.</div>
        </div>
    @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1rem; margin-bottom:2rem;">
            @foreach($progress->take(3) as $item)
            @php $kelas = $item['kelas']; $persen = $item['persen']; @endphp
            <div class="course-card">
                {{-- Thumbnail --}}
                <div class="course-thumbnail-placeholder">
                    💻
                </div>

                <div class="course-body">
                    {{-- Badge tipe --}}
                    <span class="badge badge-teal" style="margin-bottom:0.5rem;">{{ $kelas->mataKuliah->sks }} SKS</span>

                    <div class="course-title">{{ $kelas->mataKuliah->nama }}</div>
                    <div class="course-teacher" style="margin-bottom:0.875rem;">
                        👨‍🏫 {{ $kelas->dosen->name }}
                    </div>

                    {{-- Progress --}}
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.4rem;">
                        <span style="font-size:0.72rem; color:#8b95a8;">Progress Materi</span>
                        <span style="font-size:0.72rem; color:#14a7a0; font-weight:600;">{{ $persen }}%</span>
                    </div>
                    <div class="progress-wrap" style="margin-bottom:0.875rem;">
                        <div class="progress-bar" style="width:{{ $persen }}%;"></div>
                    </div>

                    <a href="{{ route('mahasiswa.matakuliah.detail', $kelas) }}"
                       class="btn btn-primary btn-sm btn-full">
                        Lanjutkan →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- ── Bottom Row ─────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

        {{-- Tugas Mendekati Deadline --}}
        <div class="card">
            <div class="section-header" style="margin-bottom:1rem;">
                <div class="section-title" style="font-size:0.9rem;">⏰ Tugas Mendekati Deadline</div>
                <a href="{{ route('mahasiswa.tugas.index') }}" class="btn btn-ghost btn-sm">Semua</a>
            </div>

            @forelse($tugas as $t)
            <a href="{{ route('mahasiswa.tugas.detail', $t) }}"
               style="display:flex; align-items:center; justify-content:space-between; padding:0.7rem; border-radius:10px; margin-bottom:0.4rem; text-decoration:none; background:rgba(255,255,255,0.03); transition:background 0.2s;"
               onmouseover="this.style.background='rgba(255,255,255,0.07)'"
               onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                <div>
                    <div style="font-size:0.82rem; font-weight:600; color:#f0f4f8; margin-bottom:2px;">{{ Str::limit($t->judul, 30) }}</div>
                    <div style="font-size:0.7rem; color:#8b95a8;">{{ $t->kelas->mataKuliah->nama }}</div>
                </div>
                @php
                    $diff   = now()->diffInHours($t->deadline);
                    $urgent = $diff < 24;
                @endphp
                <span class="badge {{ $urgent ? 'badge-red' : 'badge-orange' }}">
                    {{ $urgent ? $diff.'j lagi' : $t->deadline->diffForHumans() }}
                </span>
            </a>
            @empty
            <div style="text-align:center; padding:1.5rem 0; color:#8b95a8; font-size:0.85rem;">
                🎉 Tidak ada tugas mendekat!
            </div>
            @endforelse
        </div>

        {{-- Kuis Terbuka --}}
        <div class="card">
            <div class="section-header" style="margin-bottom:1rem;">
                <div class="section-title" style="font-size:0.9rem;">⚡ Kuis Tersedia</div>
                <a href="{{ route('mahasiswa.kuis.index') }}" class="btn btn-ghost btn-sm">Semua</a>
            </div>

            @forelse($kuisTerbuka as $k)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:0.7rem; border-radius:10px; background:rgba(255,255,255,0.03); margin-bottom:0.4rem;">
                <div>
                    <div style="font-size:0.82rem; font-weight:600; color:#f0f4f8; margin-bottom:2px;">{{ Str::limit($k->judul, 28) }}</div>
                    <div style="font-size:0.7rem; color:#8b95a8;">⏱ {{ $k->durasi_menit }} menit</div>
                </div>
                <a href="{{ route('mahasiswa.kuis.engine', $k) }}" class="btn btn-primary btn-sm">Mulai</a>
            </div>
            @empty
            <div style="text-align:center; padding:1.5rem 0; color:#8b95a8; font-size:0.85rem;">
                Tidak ada kuis aktif saat ini.
            </div>
            @endforelse
        </div>
    </div>

</div>
