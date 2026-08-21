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
        @php 
            $kelas = $item['kelas']; 
            $persen = $item['persen']; 
            $gradients = [
                ['from' => '#f59e0b', 'to' => '#d97706', 'icon' => 'fa-book-open'],
                ['from' => '#06b6d4', 'to' => '#0891b2', 'icon' => 'fa-globe'],
                ['from' => '#ec4899', 'to' => '#db2777', 'icon' => 'fa-brain'],
                ['from' => '#1a75d1', 'to' => '#0d559e', 'icon' => 'fa-laptop-code'],
                ['from' => '#8b5cf6', 'to' => '#6d28d9', 'icon' => 'fa-flask'],
                ['from' => '#10b981', 'to' => '#059669', 'icon' => 'fa-chart-bar'],
                ['from' => '#ef4444', 'to' => '#dc2626', 'icon' => 'fa-pen-nib'],
                ['from' => '#f97316', 'to' => '#ea580c', 'icon' => 'fa-calculator'],
            ];
            $g = $gradients[$kelas->id % count($gradients)];
        @endphp
        <div class="card h-full flex flex-col hover:-translate-y-1 transition-transform" style="padding:0; overflow:hidden; border:none; box-shadow:0 4px 15px rgba(0,0,0,0.05); display:flex; flex-direction:column;">
            {{-- Thumbnail / Banner --}}
            <div style="height:140px; position:relative; overflow:hidden; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, {{ $g['from'] }}, {{ $g['to'] }});">
                {{-- Decorative circles --}}
                <div style="position:absolute; width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.08); top:-30px; right:-30px;"></div>
                <div style="position:absolute; width:80px; height:80px; border-radius:50%; background:rgba(255,255,255,0.08); bottom:-20px; left:-20px;"></div>

                {{-- Floating icon --}}
                <i class="fas {{ $g['icon'] }}" style="position:absolute; font-size:5rem; color:rgba(255,255,255,0.15); bottom:-8px; right:8px; transform:rotate(-10deg);"></i>

                {{-- Badges --}}
                <span style="position:absolute; top:0.75rem; right:0.75rem; background:rgba(255,255,255,0.2); backdrop-filter:blur(4px); padding:0.25rem 0.75rem; border-radius:99px; color:#fff; font-size:0.7rem; font-weight:700; border:1px solid rgba(255,255,255,0.3);">
                    {{ $kelas->mataKuliah->sks ?? 0 }} SKS
                </span>
                
                {{-- Course Info inside Banner --}}
                <div style="position:absolute; bottom:0.75rem; left:1rem; right:1rem;">
                    <h3 style="color:#fff; font-weight:800; font-size:1.1rem; margin-bottom:0.25rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; text-shadow:0 1px 3px rgba(0,0,0,0.3); line-height:1.3;">
                        {{ $kelas->mataKuliah->nama ?? 'Unknown MK' }}
                    </h3>
                    <p style="color:rgba(255,255,255,0.8); font-size:0.75rem; margin:0;">
                        {{ $kelas->semester->nama ?? 'Semester Aktif' }}
                    </p>
                </div>
            </div>

            <div style="padding:1.25rem; flex:1; display:flex; flex-direction:column; background:var(--bg-card);">
                {{-- Kode & Kelas --}}
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.75rem;">
                    <span style="font-size:0.875rem; font-weight:700; color:var(--text-secondary);">{{ $kelas->mataKuliah->kode ?? '-' }}</span>
                    <span style="background:rgba(59,130,246,0.1); color:#3b82f6; padding:0.25rem 0.6rem; border-radius:99px; font-size:0.7rem; font-weight:700;">Kelas {{ $kelas->nama_kelas }}</span>
                </div>

                <div style="font-size:0.8rem; color:var(--text-secondary); margin-bottom:0.35rem;">
                    <i class="fas fa-chalkboard-teacher" style="width:16px; text-align:center; color:var(--teal); margin-right:4px;"></i> {{ $kelas->dosen->name }}
                </div>
                <div style="font-size:0.8rem; color:var(--text-secondary); margin-bottom:1.25rem;">
                    <i class="fas fa-calendar-alt" style="width:16px; text-align:center; color:var(--teal); margin-right:4px;"></i> {{ ucfirst($kelas->hari_kuliah ?? '-') }}, {{ $kelas->jam_mulai ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '-' }} - {{ $kelas->jam_selesai ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '-' }}
                </div>

                <div style="margin-top:auto;">
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.75rem; font-weight:700; margin-bottom:0.4rem;">
                        <span style="color:var(--text-primary);">Progress Belajar</span>
                        <span style="color:{{ $persen == 100 ? '#10b981' : 'var(--teal)' }};">{{ $persen }}%</span>
                    </div>
                    <div style="height:6px; background:var(--input-bg); border-radius:99px; margin-bottom:1rem; overflow:hidden;">
                        <div style="width:{{ $persen }}%; height:100%; background:{{ $persen == 100 ? '#10b981' : 'var(--teal)' }}; border-radius:99px; transition:width 0.5s;"></div>
                    </div>
                    <a href="{{ route('mahasiswa.matakuliah.detail', $kelas->slug) }}" style="display:block; width:100%; text-align:center; padding:0.6rem; border-radius:8px; background:var(--teal); color:#fff; font-size:0.85rem; font-weight:600; text-decoration:none; transition:background 0.2s;" onmouseover="this.style.background='var(--teal-dark)'" onmouseout="this.style.background='var(--teal)'">
                        {{ $persen > 0 ? 'Lanjutkan Belajar →' : 'Mulai Belajar →' }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
