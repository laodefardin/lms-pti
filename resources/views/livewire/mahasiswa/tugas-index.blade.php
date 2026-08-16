<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Tugas & Pengumpulan</h1>
            <p class="section-sub text-muted">Kelola semua tugas kuliah Anda di sini.</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex space-x-2 mb-6 border-b border-gray-200" style="border-color: var(--border);">
        <button wire:click="$set('filter', 'semua')" class="px-4 py-2 text-sm font-medium {{ $filter === 'semua' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500 hover:text-gray-700' }}" style="{{ $filter === 'semua' ? 'color: var(--teal); border-bottom: 2px solid var(--teal);' : 'color: var(--text-muted);' }}">Semua</button>
        <button wire:click="$set('filter', 'pending')" class="px-4 py-2 text-sm font-medium {{ $filter === 'pending' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500 hover:text-gray-700' }}" style="{{ $filter === 'pending' ? 'color: var(--teal); border-bottom: 2px solid var(--teal);' : 'color: var(--text-muted);' }}">Pending</button>
        <button wire:click="$set('filter', 'dikumpulkan')" class="px-4 py-2 text-sm font-medium {{ $filter === 'dikumpulkan' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500 hover:text-gray-700' }}" style="{{ $filter === 'dikumpulkan' ? 'color: var(--teal); border-bottom: 2px solid var(--teal);' : 'color: var(--text-muted);' }}">Dikumpulkan</button>
        <button wire:click="$set('filter', 'dinilai')" class="px-4 py-2 text-sm font-medium {{ $filter === 'dinilai' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500 hover:text-gray-700' }}" style="{{ $filter === 'dinilai' ? 'color: var(--teal); border-bottom: 2px solid var(--teal);' : 'color: var(--text-muted);' }}">Dinilai</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tugas as $t)
            @php
                $pengumpulan = $t->pengumpulanTugas->first();
                $isPassed = \Carbon\Carbon::parse($t->batas_waktu)->isPast();
                $diff = \Carbon\Carbon::parse($t->batas_waktu)->diffInHours(now());
                $diffDays = \Carbon\Carbon::parse($t->batas_waktu)->diffInDays(now());
            @endphp
            <div class="card p-5" style="background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 0.5rem; box-shadow: var(--shadow-card);">
                <div class="mb-3">
                    <span class="badge badge-teal" style="background-color: var(--teal-light); color: var(--teal-dark); padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem;">{{ $t->kelas->mataKuliah->nama_mk }}</span>
                </div>
                <h3 class="font-bold text-lg mb-2" style="color: var(--text-primary);">{{ $t->judul }}</h3>
                
                <div class="flex items-center text-sm mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span style="color: {{ $isPassed && !$pengumpulan ? 'var(--danger)' : 'var(--text-secondary)' }}">
                        {{ \Carbon\Carbon::parse($t->batas_waktu)->format('d M Y, H:i') }}
                    </span>
                    @if(!$pengumpulan && !$isPassed)
                        @if($diff < 24)
                            <span class="ml-2 badge badge-red text-xs px-2 py-0.5 rounded-full" style="background-color: #fee2e2; color: var(--danger);"> < 24j</span>
                        @elseif($diffDays < 3)
                            <span class="ml-2 badge badge-orange text-xs px-2 py-0.5 rounded-full" style="background-color: #ffedd5; color: var(--warning);"> < 3h</span>
                        @endif
                    @endif
                </div>

                <div class="mb-4">
                    @if($pengumpulan)
                        @if($pengumpulan->status === 'dinilai')
                            <span class="badge badge-green" style="background-color: #dcfce7; color: var(--success); padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Dinilai: {{ $pengumpulan->nilai }}/100</span>
                        @else
                            <span class="badge badge-teal" style="background-color: var(--teal-light); color: var(--teal-dark); padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Dikumpulkan</span>
                        @endif
                    @else
                        @if($isPassed)
                            <span class="badge badge-red" style="background-color: #fee2e2; color: var(--danger); padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Terlambat</span>
                        @else
                            <span class="badge badge-gray" style="background-color: #f3f4f6; color: var(--text-muted); padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Belum Dikumpulkan</span>
                        @endif
                    @endif
                </div>

                <a href="{{ route('mahasiswa.tugas.detail', $t->id) }}" class="btn btn-outline btn-full block text-center py-2 rounded-md" style="border: 1px solid var(--teal); color: var(--teal); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.backgroundColor='var(--teal)'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--teal)';">
                    {{ $pengumpulan ? 'Lihat Detail' : 'Kumpulkan' }}
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <p style="color: var(--text-muted);">Tidak ada tugas ditemukan.</p>
            </div>
        @endforelse
    </div>
</div>
