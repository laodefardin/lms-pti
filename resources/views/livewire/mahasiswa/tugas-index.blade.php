<div class="fade-in">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <h1 class="section-title">Tugas & Pengumpulan</h1>
            <p class="section-sub text-muted">Kelola semua tugas kuliah Anda di sini.</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div style="display:flex; gap:0.25rem; margin-bottom:1.5rem; border-bottom:1px solid var(--border);">
        <button wire:click="$set('filter', 'semua')" style="padding:0.6rem 1rem; font-size:0.85rem; background:none; cursor:pointer; font-weight:500; border:none; {{ $filter === 'semua' ? 'color: var(--teal); border-bottom: 2px solid var(--teal);' : 'color: var(--text-muted);' }}">Semua</button>
        <button wire:click="$set('filter', 'pending')" style="padding:0.6rem 1rem; font-size:0.85rem; background:none; cursor:pointer; font-weight:500; border:none; {{ $filter === 'pending' ? 'color: var(--teal); border-bottom: 2px solid var(--teal);' : 'color: var(--text-muted);' }}">Pending</button>
        <button wire:click="$set('filter', 'dikumpulkan')" style="padding:0.6rem 1rem; font-size:0.85rem; background:none; cursor:pointer; font-weight:500; border:none; {{ $filter === 'dikumpulkan' ? 'color: var(--teal); border-bottom: 2px solid var(--teal);' : 'color: var(--text-muted);' }}">Dikumpulkan</button>
        <button wire:click="$set('filter', 'dinilai')" style="padding:0.6rem 1rem; font-size:0.85rem; background:none; cursor:pointer; font-weight:500; border:none; {{ $filter === 'dinilai' ? 'color: var(--teal); border-bottom: 2px solid var(--teal);' : 'color: var(--text-muted);' }}">Dinilai</button>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1.25rem;">
        @forelse($tugas as $t)
            @php
                $pengumpulan = $t->pengumpulanTugas ? $t->pengumpulanTugas->first() : null;
                $isPassed = \Carbon\Carbon::parse($t->deadline)->isPast();
                $diff = \Carbon\Carbon::parse($t->deadline)->diffInHours(now());
                $diffDays = \Carbon\Carbon::parse($t->deadline)->diffInDays(now());
            @endphp
            <div class="card" style="padding: 1.25rem;">
                <div style="margin-bottom: 0.75rem;">
                    <span class="badge badge-teal">{{ $t->kelas->mataKuliah->nama ?? '' }}</span>
                </div>
                <h3 style="font-weight: bold; font-size: 1.125rem; margin-bottom: 0.5rem; color: var(--text-primary);">{{ $t->judul }}</h3>
                
                <div style="display:flex; align-items:center; font-size:0.875rem; margin-bottom:1rem;">
                    <i class="fas fa-clock" style="margin-right: 0.5rem;"></i>
                    <span style="color: {{ $isPassed && !$pengumpulan ? 'var(--danger)' : 'var(--text-secondary)' }}">
                        {{ \Carbon\Carbon::parse($t->deadline)->format('d M Y, H:i') }}
                    </span>
                    @if(!$pengumpulan && !$isPassed)
                        @if($diff < 24)
                            <span class="badge badge-red" style="margin-left:0.5rem; font-size:0.75rem;"> < 24j</span>
                        @elseif($diffDays < 3)
                            <span class="badge badge-orange" style="margin-left:0.5rem; font-size:0.75rem;"> < 3h</span>
                        @endif
                    @endif
                </div>

                <div style="margin-bottom: 1rem;">
                    @if($pengumpulan)
                        @if($pengumpulan->status === 'dinilai')
                            <span class="badge badge-green">Dinilai: {{ $pengumpulan->nilai }}/100</span>
                        @else
                            <span class="badge badge-teal">Dikumpulkan</span>
                        @endif
                    @else
                        @if($isPassed)
                            <span class="badge badge-red">Terlambat</span>
                        @else
                            <span class="badge badge-gray">Belum Dikumpulkan</span>
                        @endif
                    @endif
                </div>

                <a href="{{ route('mahasiswa.tugas.detail', $t->id) }}" class="btn btn-outline btn-full" style="display:block; text-align:center;">
                    {{ $pengumpulan ? 'Lihat Detail' : 'Kumpulkan' }}
                </a>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding-top: 2.5rem; padding-bottom: 2.5rem;">
                <p class="text-muted">Tidak ada tugas ditemukan.</p>
            </div>
        @endforelse
    </div>
</div>
