<div class="fade-in">

    {{-- Header --}}
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.4rem; font-weight:800; color:var(--text-primary); margin-bottom:0.25rem;">Matakuliah Saya</h1>
        <p style="color:var(--text-secondary); font-size:0.875rem;">Semua matakuliah yang kamu ikuti semester ini</p>
    </div>

    {{-- Search --}}
    <div style="position:relative; max-width:380px; margin-bottom:1.5rem;">
        <svg style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:var(--text-muted);" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input wire:model.live="search" type="text" placeholder="Cari matakuliah..."
               style="width:100%; background:var(--input-bg); border:1.5px solid var(--input-border); border-radius:12px; padding:0.65rem 0.875rem 0.65rem 2.75rem; color:var(--text-primary); font-size:0.875rem; font-family:'Geist',sans-serif; outline:none; transition:border-color 0.2s;"
               onfocus="this.style.borderColor='var(--teal)'" onblur="this.style.borderColor='var(--input-border)'">
    </div>

    {{-- Course Grid --}}
    @if($kelasList->isEmpty())
        <div class="card" style="text-align:center; padding:4rem 2rem;">
            <div style="font-size:3.5rem; margin-bottom:1rem; color:var(--text-muted);"><i class="fas fa-book"></i></div>
            <div style="font-size:1rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Belum ada matakuliah</div>
            <div style="color:var(--text-secondary); font-size:0.85rem;">Hubungi admin untuk pendaftaran matakuliah.</div>
        </div>
    @else
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:1.25rem;">
        @foreach($kelasList as $item)
        @php $kelas = $item['kelas']; $persen = $item['persen']; @endphp
        <div class="course-card slide-in-left">
            {{-- Thumbnail --}}
            <div style="position:relative;">
                <div class="course-thumbnail-placeholder" style="background:linear-gradient(135deg, {{ ['#0e8a84','#0e6e8a','#5b0e8a','#8a570e','#1b5e20'][($loop->index % 5) ] }}, {{ ['#14a7a0','#148ea7','#7c14a7','#a78a14','#2e7d32'][$loop->index % 5] }});">
                    <span style="font-size:2.5rem;">{!! ['<i class="fas fa-laptop-code"></i>','<i class="fas fa-globe"></i>','<i class="fas fa-server"></i>','<i class="fas fa-lock"></i>','<i class="fas fa-mobile-alt"></i>'][$loop->index % 5] !!}</span>
                </div>
                {{-- Progress overlay badge --}}
                <div style="position:absolute; top:0.75rem; right:0.75rem;">
                    @if($persen === 100)
                        <span class="badge badge-green"><i class="fas fa-check-circle"></i> Selesai</span>
                    @elseif($persen > 0)
                        <span class="badge badge-teal">{{ $persen }}% selesai</span>
                    @else
                        <span class="badge badge-gray">Belum dimulai</span>
                    @endif
                </div>
            </div>

            <div class="course-body">
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.6rem;">
                    <span class="badge badge-teal">{{ $kelas->mataKuliah->sks }} SKS</span>
                    <span class="badge badge-gray">{{ $kelas->mataKuliah->kode }}</span>
                </div>

                <div class="course-title" style="font-size:1rem; color:var(--text-primary); margin-bottom:0.35rem;">{{ $kelas->mataKuliah->nama }}</div>
                <div class="course-teacher" style="color:var(--text-secondary); margin-bottom:0.3rem;">
                    <i class="fas fa-chalkboard-teacher"></i> {{ $kelas->dosen->name }}
                </div>
                <div style="font-size:0.72rem; color:var(--text-muted); margin-bottom:0.875rem;">
                    <i class="fas fa-calendar-alt"></i> {{ ucfirst($kelas->hari_kuliah ?? '-') }} · {{ $kelas->jam_mulai ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '-' }}–{{ $kelas->jam_selesai ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '-' }}
                    @if($kelas->ruangan) · <i class="fas fa-building"></i> {{ $kelas->ruangan }} @endif
                </div>

                {{-- Progress --}}
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.4rem;">
                    <span style="font-size:0.72rem; color:var(--text-secondary);">Progress Materi</span>
                    <span style="font-size:0.72rem; font-weight:700; color:var(--teal);">{{ $persen }}%</span>
                </div>
                <div class="progress-wrap" style="margin-bottom:1rem;">
                    <div class="progress-bar" style="width:{{ $persen }}%;"></div>
                </div>

                <a href="{{ route('mahasiswa.matakuliah.detail', $kelas->slug) }}" class="btn btn-primary btn-sm btn-full">
                    {{ $persen > 0 ? 'Lanjutkan Belajar →' : 'Mulai Belajar →' }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
