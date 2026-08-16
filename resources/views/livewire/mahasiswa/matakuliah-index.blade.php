<div class="fade-in">

    {{-- Header --}}
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.4rem; font-weight:800; color:#f0f4f8; margin-bottom:0.25rem;">Matakuliah Saya 📚</h1>
        <p style="color:#8b95a8; font-size:0.875rem;">Semua matakuliah yang kamu ikuti semester ini</p>
    </div>

    {{-- Search --}}
    <div style="position:relative; max-width:380px; margin-bottom:1.5rem;">
        <svg style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#5a6478;" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input wire:model.live="search" type="text" placeholder="Cari matakuliah..."
               style="width:100%; background:rgba(255,255,255,0.05); border:1.5px solid rgba(255,255,255,0.1); border-radius:12px; padding:0.65rem 0.875rem 0.65rem 2.75rem; color:#f0f4f8; font-size:0.875rem; font-family:'Inter',sans-serif; outline:none; transition:border-color 0.2s;"
               onfocus="this.style.borderColor='#14a7a0'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
    </div>

    {{-- Course Grid --}}
    @if($kelasList->isEmpty())
        <div class="card" style="text-align:center; padding:4rem 2rem;">
            <div style="font-size:3.5rem; margin-bottom:1rem;">📚</div>
            <div style="font-size:1rem; font-weight:600; color:#f0f4f8; margin-bottom:0.5rem;">Belum ada matakuliah</div>
            <div style="color:#8b95a8; font-size:0.85rem;">Hubungi admin untuk pendaftaran matakuliah.</div>
        </div>
    @else
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:1.25rem;">
        @foreach($kelasList as $item)
        @php $kelas = $item['kelas']; $persen = $item['persen']; @endphp
        <div class="course-card slide-in-left">
            {{-- Thumbnail --}}
            <div style="position:relative;">
                <div class="course-thumbnail-placeholder" style="background:linear-gradient(135deg, {{ ['#0e8a84','#0e6e8a','#5b0e8a','#8a570e','#1b5e20'][($loop->index % 5) ] }}, {{ ['#14a7a0','#148ea7','#7c14a7','#a78a14','#2e7d32'][$loop->index % 5] }});">
                    <span style="font-size:2.5rem;">{{ ['💻','🌐','🗄️','🔐','📱'][$loop->index % 5] }}</span>
                </div>
                {{-- Progress overlay badge --}}
                <div style="position:absolute; top:0.75rem; right:0.75rem;">
                    @if($persen === 100)
                        <span class="badge badge-green">✅ Selesai</span>
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

                <div class="course-title" style="font-size:1rem; margin-bottom:0.35rem;">{{ $kelas->mataKuliah->nama }}</div>
                <div class="course-teacher" style="margin-bottom:0.3rem;">
                    👨‍🏫 {{ $kelas->dosen->name }}
                </div>
                <div style="font-size:0.72rem; color:#5a6478; margin-bottom:0.875rem;">
                    📅 {{ ucfirst($kelas->hari_kuliah ?? '-') }} · {{ $kelas->jam_mulai ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '-' }}–{{ $kelas->jam_selesai ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '-' }}
                    @if($kelas->ruangan) · 🏫 {{ $kelas->ruangan }} @endif
                </div>

                {{-- Progress --}}
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.4rem;">
                    <span style="font-size:0.72rem; color:#8b95a8;">Progress Materi</span>
                    <span style="font-size:0.72rem; font-weight:700; color:#14a7a0;">{{ $persen }}%</span>
                </div>
                <div class="progress-wrap" style="margin-bottom:1rem;">
                    <div class="progress-bar" style="width:{{ $persen }}%;"></div>
                </div>

                <a href="{{ route('mahasiswa.matakuliah.detail', $kelas) }}" class="btn btn-primary btn-sm btn-full">
                    {{ $persen > 0 ? 'Lanjutkan Belajar →' : 'Mulai Belajar →' }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
