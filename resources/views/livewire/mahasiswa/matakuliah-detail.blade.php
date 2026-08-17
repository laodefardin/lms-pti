<div class="fade-in">

    {{-- ── Breadcrumb ─────────────────────────────────────────── --}}
    <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; color:var(--text-secondary); margin-bottom:1.25rem;">
        <a href="{{ route('mahasiswa.matakuliah.index') }}" style="color:var(--text-secondary); text-decoration:none;" onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='var(--text-secondary)'">Matakuliah Saya</a>
        <i class="fas fa-chevron-right" style="font-size:0.6rem; color:var(--text-muted);"></i>
        <span style="color:var(--text-primary); font-weight:500;">{{ $kelas->mataKuliah->nama }}</span>
    </div>

    {{-- ── Hero Card ───────────────────────────────────────────── --}}
    <div style="background:linear-gradient(135deg, var(--teal-dim), var(--bg-card)); border:1px solid var(--border-teal); border-radius:16px; padding:1.75rem; margin-bottom:1.5rem; display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1.5rem;">
        <div>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.75rem;">
                <span class="badge badge-teal">{{ $kelas->mataKuliah->kode }}</span>
                <span class="badge badge-teal">{{ $kelas->mataKuliah->sks }} SKS</span>
                <span class="badge badge-gray">Kelas {{ $kelas->nama_kelas }}</span>
            </div>
            <h1 style="font-size:1.5rem; font-weight:800; color:var(--text-primary); margin-bottom:0.5rem;">{{ $kelas->mataKuliah->nama }}</h1>
            <div style="font-size:0.85rem; color:var(--text-secondary); margin-bottom:0.5rem;">
                <i class="fas fa-chalkboard-teacher"></i> {{ $kelas->dosen->name }}
                @if($kelas->hari_kuliah)
                · <i class="fas fa-calendar-alt"></i> {{ ucfirst($kelas->hari_kuliah) }}, {{ $kelas->jam_mulai ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '' }}–{{ $kelas->jam_selesai ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '' }}
                @endif
                @if($kelas->ruangan) · <i class="fas fa-building"></i> {{ $kelas->ruangan }} @endif
            </div>
            @if($kelas->deskripsi)
            <p style="font-size:0.82rem; color:var(--text-secondary); max-width:560px; line-height:1.6;">{{ $kelas->deskripsi }}</p>
            @endif
        </div>

        {{-- Progress Circle --}}
        <div style="text-align:center; min-width:100px;">
            <div style="position:relative; width:80px; height:80px; margin:0 auto 0.5rem;">
                <svg width="80" height="80" viewBox="0 0 80 80">
                    <circle cx="40" cy="40" r="32" fill="none" stroke="var(--border)" stroke-width="6"/>
                    <circle cx="40" cy="40" r="32" fill="none" stroke="var(--teal)" stroke-width="6"
                            stroke-dasharray="{{ 2 * 3.14159 * 32 }}"
                            stroke-dashoffset="{{ 2 * 3.14159 * 32 * (1 - $progress/100) }}"
                            stroke-linecap="round"
                            transform="rotate(-90 40 40)"/>
                </svg>
                <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-size:0.9rem; font-weight:800; color:var(--teal);">{{ $progress }}%</div>
            </div>
            <div style="font-size:0.72rem; color:var(--text-secondary);">Progress</div>
        </div>
    </div>

    {{-- ── Quick Stats ─────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:0.875rem; margin-bottom:1.75rem;">
        <div class="card" style="padding:1rem;">
            <div style="font-size:1.25rem; margin-bottom:0.25rem;"><i class="fas fa-book" style="color:var(--teal);"></i></div>
            <div style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">{{ $kelas->pertemuan->count() }}</div>
            <div style="font-size:0.72rem; color:var(--text-secondary);">Pertemuan</div>
        </div>
        <div class="card" style="padding:1rem;">
            <div style="font-size:1.25rem; margin-bottom:0.25rem;"><i class="fas fa-edit" style="color:var(--orange);"></i></div>
            <div style="font-size:1.1rem; font-weight:700; color:{{ $tugasPending > 0 ? 'var(--warning)' : 'var(--text-primary)' }};">{{ $tugasPending }}</div>
            <div style="font-size:0.72rem; color:var(--text-secondary);">Tugas Pending</div>
        </div>
        <div class="card" style="padding:1rem;">
            <div style="font-size:1.25rem; margin-bottom:0.25rem;"><i class="fas fa-bolt" style="color:var(--warning);"></i></div>
            <div style="font-size:1.1rem; font-weight:700; color:{{ $kuisAktif > 0 ? 'var(--teal)' : 'var(--text-primary)' }};">{{ $kuisAktif }}</div>
            <div style="font-size:0.72rem; color:var(--text-secondary);">Kuis Aktif</div>
        </div>
        <div class="card" style="padding:1rem;">
            <div style="font-size:1.25rem; margin-bottom:0.25rem;"><i class="fas fa-balance-scale" style="color:var(--teal);"></i></div>
            <div style="font-size:0.72rem; font-weight:700; color:var(--text-primary);">T:{{ $kelas->bobot_tugas }}% K:{{ $kelas->bobot_kuis }}%</div>
            <div style="font-size:0.65rem; color:var(--text-secondary);">UTS:{{ $kelas->bobot_uts }}% UAS:{{ $kelas->bobot_uas }}%</div>
            <div style="font-size:0.72rem; color:var(--text-secondary); margin-top:2px;">Bobot Penilaian</div>
        </div>
    </div>

    {{-- ── Tentang Kelas ──────────────────────────────────────── --}}
    @if($kelas->mataKuliah->deskripsi || $kelas->deskripsi)
    <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:1.5rem; margin-bottom:1.25rem;">
        <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:1rem;">
            <i class="fas fa-info-circle" style="color:var(--teal); font-size:1.1rem;"></i>
            <h2 style="font-size:1.05rem; font-weight:700; color:var(--text-primary); margin:0;">Tentang Kelas</h2>
        </div>
        <div style="font-size:0.9rem; color:var(--text-secondary); line-height:1.75;">
            {!! nl2br(e($kelas->mataKuliah->deskripsi ?? $kelas->deskripsi)) !!}
        </div>
    </div>
    @endif

    {{-- ── Daftar Materi ─────────────────────────────────────── --}}
    <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; overflow:hidden;">
        
        {{-- Card Title --}}
        <div style="display:flex; align-items:center; gap:0.6rem; padding:1.25rem 1.5rem;">
            <i class="fas fa-grip-horizontal" style="color:var(--teal); font-size:1.05rem;"></i>
            <h2 style="font-size:1.05rem; font-weight:700; color:var(--text-primary); margin:0;">Daftar Materi</h2>
        </div>

        {{-- Accordion List --}}
        <div style="display:flex; flex-direction:column; gap:0.5rem; padding:0 0.75rem 0.75rem;">
            @forelse($kelas->pertemuan as $pertemuan)
            <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }" style="border:1px solid var(--border); border-radius:6px; overflow:hidden;">
                
                {{-- Pertemuan Header --}}
                <button @click="open = !open"
                        style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border:none; cursor:pointer; text-align:left; background:var(--input-bg); transition:background 0.15s;"
                        onmouseover="this.style.background='var(--bg-card-hover)'"
                        onmouseout="this.style.background='var(--input-bg)'">
                    <span style="font-size:0.9rem;"
                          :style="open ? 'color:var(--teal); font-weight:600;' : 'color:var(--text-secondary); font-weight:400;'">
                        {{ $pertemuan->topik }}
                    </span>
                    <i class="fas"
                       :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                       style="font-size:0.7rem; flex-shrink:0;"
                       :style="open ? 'color:var(--teal);' : 'color:var(--text-muted);'"></i>
                </button>

                {{-- Konten Items --}}
                <div x-show="open" x-transition style="background:var(--bg-card); padding:0.25rem 0 0.75rem;">
                    @forelse($pertemuan->konten->where('is_published', true) as $konten)
                    <a href="{{ route('mahasiswa.materi.viewer', [$kelas->slug, $konten]) }}"
                       style="display:flex; align-items:center; justify-content:space-between; padding:0.75rem 1.25rem; border-bottom:1px solid var(--border); text-decoration:none; transition:background 0.15s;"
                       onmouseover="this.style.background='var(--input-bg)'"
                       onmouseout="this.style.background='transparent'">
                        
                        <div style="display:flex; align-items:center; gap:0.9rem; min-width:0;">
                            @php
                                $iconClass = 'fas fa-play';
                                if($konten->tipe == 'dokumen') $iconClass = 'fas fa-file-alt';
                                elseif($konten->tipe == 'kuis') $iconClass = 'fas fa-question';
                                elseif($konten->tipe == 'tugas') $iconClass = 'fas fa-pen';
                                elseif($konten->tipe == 'link') $iconClass = 'fas fa-link';
                            @endphp
                            <div style="width:30px; height:30px; background:var(--teal); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i class="{{ $iconClass }}" style="color:#fff; font-size:0.65rem; {{ ($konten->tipe == 'video' || !$konten->tipe) ? 'margin-left:2px;' : '' }}"></i>
                            </div>
                            <span style="font-size:0.875rem; color:var(--text-secondary); line-height:1.4;">{{ $konten->judul }}</span>
                        </div>

                        <div style="font-size:0.8rem; color:var(--text-muted); flex-shrink:0; margin-left:1rem; white-space:nowrap;">
                            @if($konten->estimasi_menit)
                                {{ str_pad(floor($konten->estimasi_menit / 60), 2, '0', STR_PAD_LEFT) }}:{{ str_pad($konten->estimasi_menit % 60, 2, '0', STR_PAD_LEFT) }}
                            @endif
                        </div>
                    </a>
                    @empty
                    <div style="padding:0.75rem 1.25rem; font-size:0.85rem; color:var(--text-muted);">
                        Belum ada materi.
                    </div>
                    @endforelse
                </div>
            </div>
            @empty
            <div style="text-align:center; padding:3rem; color:var(--text-secondary);">
                <i class="fas fa-layer-group" style="font-size:2.5rem; margin-bottom:1rem; display:block;"></i>
                Materi belum tersedia.
            </div>
            @endforelse
        </div>

    </div>
</div>
