<div class="fade-in">

    {{-- ── Breadcrumb ─────────────────────────────────────────── --}}
    <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; color:#8b95a8; margin-bottom:1.25rem;">
        <a href="{{ route('mahasiswa.matakuliah.index') }}" style="color:#8b95a8; text-decoration:none;" onmouseover="this.style.color='#14a7a0'" onmouseout="this.style.color='#8b95a8'">Matakuliah Saya</a>
        <span>/</span>
        <span style="color:#f0f4f8; font-weight:500;">{{ $kelas->mataKuliah->nama }}</span>
    </div>

    {{-- ── Hero Card ───────────────────────────────────────────── --}}
    <div style="background:linear-gradient(135deg, rgba(20,167,160,0.15), rgba(30,33,48,1)); border:1px solid rgba(20,167,160,0.2); border-radius:16px; padding:1.75rem; margin-bottom:1.5rem; display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1.5rem;">
        <div>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.75rem;">
                <span class="badge badge-teal">{{ $kelas->mataKuliah->kode }}</span>
                <span class="badge badge-teal">{{ $kelas->mataKuliah->sks }} SKS</span>
                <span class="badge badge-gray">Kelas {{ $kelas->nama_kelas }}</span>
            </div>
            <h1 style="font-size:1.5rem; font-weight:800; color:#f0f4f8; margin-bottom:0.5rem;">{{ $kelas->mataKuliah->nama }}</h1>
            <div style="font-size:0.85rem; color:#8b95a8; margin-bottom:0.5rem;">
                👨‍🏫 {{ $kelas->dosen->name }}
                @if($kelas->hari_kuliah)
                · 📅 {{ ucfirst($kelas->hari_kuliah) }}, {{ $kelas->jam_mulai ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '' }}–{{ $kelas->jam_selesai ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '' }}
                @endif
                @if($kelas->ruangan) · 🏫 {{ $kelas->ruangan }} @endif
            </div>
            @if($kelas->deskripsi)
            <p style="font-size:0.82rem; color:#8b95a8; max-width:560px; line-height:1.6;">{{ $kelas->deskripsi }}</p>
            @endif
        </div>

        {{-- Progress Circle --}}
        <div style="text-align:center; min-width:100px;">
            <div style="position:relative; width:80px; height:80px; margin:0 auto 0.5rem;">
                <svg width="80" height="80" viewBox="0 0 80 80">
                    <circle cx="40" cy="40" r="32" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="6"/>
                    <circle cx="40" cy="40" r="32" fill="none" stroke="#14a7a0" stroke-width="6"
                            stroke-dasharray="{{ 2 * 3.14159 * 32 }}"
                            stroke-dashoffset="{{ 2 * 3.14159 * 32 * (1 - $progress/100) }}"
                            stroke-linecap="round"
                            transform="rotate(-90 40 40)"/>
                </svg>
                <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-size:0.9rem; font-weight:800; color:#14a7a0;">{{ $progress }}%</div>
            </div>
            <div style="font-size:0.72rem; color:#8b95a8;">Progress</div>
        </div>
    </div>

    {{-- ── Quick Stats ─────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:0.875rem; margin-bottom:1.75rem;">
        <div class="card" style="padding:1rem;">
            <div style="font-size:1.25rem; margin-bottom:0.25rem;">📖</div>
            <div style="font-size:1.1rem; font-weight:700; color:#f0f4f8;">{{ $kelas->pertemuan->count() }}</div>
            <div style="font-size:0.72rem; color:#8b95a8;">Pertemuan</div>
        </div>
        <div class="card" style="padding:1rem;">
            <div style="font-size:1.25rem; margin-bottom:0.25rem;">📝</div>
            <div style="font-size:1.1rem; font-weight:700; color:{{ $tugasPending > 0 ? '#f59e0b' : '#f0f4f8' }};">{{ $tugasPending }}</div>
            <div style="font-size:0.72rem; color:#8b95a8;">Tugas Pending</div>
        </div>
        <div class="card" style="padding:1rem;">
            <div style="font-size:1.25rem; margin-bottom:0.25rem;">⚡</div>
            <div style="font-size:1.1rem; font-weight:700; color:{{ $kuisAktif > 0 ? '#14a7a0' : '#f0f4f8' }};">{{ $kuisAktif }}</div>
            <div style="font-size:0.72rem; color:#8b95a8;">Kuis Aktif</div>
        </div>
        <div class="card" style="padding:1rem;">
            <div style="font-size:1.25rem; margin-bottom:0.25rem;">⚖️</div>
            <div style="font-size:0.72rem; font-weight:700; color:#f0f4f8;">T:{{ $kelas->bobot_tugas }}% K:{{ $kelas->bobot_kuis }}%</div>
            <div style="font-size:0.65rem; color:#8b95a8;">UTS:{{ $kelas->bobot_uts }}% UAS:{{ $kelas->bobot_uas }}%</div>
            <div style="font-size:0.72rem; color:#8b95a8; margin-top:2px;">Bobot Penilaian</div>
        </div>
    </div>

    {{-- ── Daftar Pertemuan ─────────────────────────────────────── --}}
    <div class="card">
        <div class="section-header" style="margin-bottom:1.25rem;">
            <div>
                <div class="section-title">📋 Daftar Pertemuan & Materi</div>
                <div class="section-sub">Klik pertemuan untuk melihat materi</div>
            </div>
        </div>

        @forelse($kelas->pertemuan as $pertemuan)
        <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }" style="margin-bottom:0.5rem;">

            {{-- Pertemuan Header --}}
            <button @click="open = !open"
                    style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:0.875rem 1rem; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06); border-radius:10px; cursor:pointer; transition:background 0.2s; text-align:left;"
                    :style="open ? 'background:rgba(20,167,160,0.08); border-color:rgba(20,167,160,0.2);' : ''"
                    onmouseover="if(!this.closest('[x-data]').__x.$data.open) this.style.background='rgba(255,255,255,0.05)'"
                    onmouseout="if(!this.closest('[x-data]').__x.$data.open) this.style.background='rgba(255,255,255,0.03)'">
                <div style="display:flex; align-items:center; gap:0.875rem;">
                    <div style="width:32px; height:32px; background:rgba(20,167,160,0.15); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.78rem; font-weight:700; color:#14a7a0; flex-shrink:0;">
                        {{ $pertemuan->nomor }}
                    </div>
                    <div>
                        <div style="font-size:0.875rem; font-weight:600; color:#f0f4f8;">{{ $pertemuan->topik }}</div>
                        <div style="font-size:0.7rem; color:#8b95a8;">
                            {{ $pertemuan->tanggal ? $pertemuan->tanggal->locale('id')->isoFormat('D MMM Y') : '-' }}
                            @if($pertemuan->konten->count() > 0)
                            · {{ $pertemuan->konten->count() }} materi
                            @endif
                        </div>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    @php
                        $kontenSelesai = $pertemuan->konten->filter(fn($k) => in_array($k->id, $selesaiIds))->count();
                        $kontenTotal = $pertemuan->konten->count();
                    @endphp
                    @if($kontenTotal > 0)
                        @if($kontenSelesai === $kontenTotal)
                            <span class="badge badge-green">✅ Selesai</span>
                        @else
                            <span class="badge badge-gray">{{ $kontenSelesai }}/{{ $kontenTotal }}</span>
                        @endif
                    @endif
                    <svg :style="open ? 'transform:rotate(180deg)' : ''" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#8b95a8" stroke-width="2" style="transition:transform 0.2s; flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            {{-- Konten List --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="padding:0.5rem 0 0.25rem 1rem;">
                @forelse($pertemuan->konten->where('is_published', true) as $konten)
                <a href="{{ route('mahasiswa.materi.viewer', [$kelas, $konten]) }}"
                   style="display:flex; align-items:center; justify-content:space-between; padding:0.6rem 0.75rem; border-radius:8px; margin-bottom:0.25rem; text-decoration:none; transition:background 0.15s;"
                   onmouseover="this.style.background='rgba(255,255,255,0.05)'"
                   onmouseout="this.style.background='transparent'">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <span style="font-size:1rem;">{{ $konten->ikon }}</span>
                        <div>
                            <div style="font-size:0.82rem; font-weight:500; color:#f0f4f8;">{{ $konten->judul }}</div>
                            <div style="font-size:0.68rem; color:#8b95a8;">{{ ucfirst($konten->tipe) }} @if($konten->estimasi_menit) · ⏱ {{ $konten->estimasi_menit }} mnt @endif</div>
                        </div>
                    </div>
                    @if(in_array($konten->id, $selesaiIds))
                        <span style="width:20px; height:20px; background:rgba(34,197,94,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.7rem;">✅</span>
                    @else
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#5a6478" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    @endif
                </a>
                @empty
                <div style="padding:0.75rem; font-size:0.8rem; color:#8b95a8;">Belum ada materi pada pertemuan ini.</div>
                @endforelse
            </div>
        </div>
        @empty
        <div style="text-align:center; padding:3rem; color:#8b95a8;">
            <div style="font-size:3rem; margin-bottom:1rem;">📖</div>
            Materi belum tersedia.
        </div>
        @endforelse
    </div>

</div>
