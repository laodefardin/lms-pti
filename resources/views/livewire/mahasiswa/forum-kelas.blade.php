<div class="fade-in">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('mahasiswa.forum.index') }}" style="color: var(--teal); text-decoration: none;">&larr; Kembali ke Forum</a>
    </div>

    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="section-title">Forum: {{ $kelas->mataKuliah->nama ?? 'Mata Kuliah' }}</h1>
            <p class="section-sub" style="color: var(--text-secondary);">Diskusikan materi dan tugas dengan dosen dan teman sekelas.</p>
        </div>
        <button wire:click="buatThread" class="btn btn-primary">+ Buat Thread</button>
    </div>

    @if (session()->has('success'))
        <div style="background: var(--success); color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; flex-wrap: wrap;">
        <input type="text" wire:model.live="search" class="form-input" placeholder="Cari diskusi..." style="width: 250px;">
        <div style="display: flex; gap: 0.5rem;">
            <button wire:click="$set('sort', 'terbaru')" class="btn {{ $sort === 'terbaru' ? 'btn-primary' : 'btn-outline' }} btn-sm">Terbaru</button>
            <button wire:click="$set('sort', 'terpopuler')" class="btn {{ $sort === 'terpopuler' ? 'btn-primary' : 'btn-outline' }} btn-sm">Terpopuler</button>
            <button wire:click="$set('sort', 'belum_dijawab')" class="btn {{ $sort === 'belum_dijawab' ? 'btn-primary' : 'btn-outline' }} btn-sm">Belum Dijawab</button>
        </div>
    </div>

    <!-- Modal Buat Thread (Alpine.js) -->
    <div x-data="{ show: @entangle('showForm') }" x-show="show" style="display: none;" class="card" style="margin-bottom: 2rem; border: 1px solid var(--border-teal);">
        <h3 class="section-title" style="margin-bottom: 1rem;">Buat Thread Baru</h3>
        <div style="margin-bottom: 1rem;">
            <label class="form-label">Judul Diskusi</label>
            <input type="text" wire:model="judulThread" class="form-input" placeholder="Masukkan judul diskusi...">
            @error('judulThread') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div style="margin-bottom: 1rem;">
            <label class="form-label">Konten</label>
            <textarea wire:model="kontenThread" class="form-input" rows="4" placeholder="Jelaskan pertanyaan atau topik diskusi Anda..."></textarea>
            @error('kontenThread') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 1rem;">
            <button @click="show = false" type="button" class="btn btn-ghost">Batal</button>
            <button wire:click="submitThread" class="btn btn-primary">Posting</button>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @forelse($threads as $thread)
            <div class="card" style="transition: transform 0.2s; cursor: pointer;">
                <div style="display: flex; gap: 1rem; align-items: flex-start;">
                    <img src="{{ $thread->user->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($thread->user->name ?? 'User') }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <span style="font-weight: bold; color: var(--text-primary);">{{ $thread->user->name ?? 'User' }}</span>
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">• {{ $thread->created_at->diffForHumans() }}</span>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                @if($thread->is_pinned)
                                    <span class="badge badge-orange">PINNED</span>
                                @endif
                                @if($thread->is_closed)
                                    <span class="badge badge-gray">CLOSED</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('mahasiswa.forum.thread', $thread->id) }}" style="text-decoration: none;">
                            <h3 style="font-size: 1.125rem; font-weight: bold; color: var(--teal); margin-bottom: 0.5rem;">{{ $thread->judul }}</h3>
                        </a>
                        <p style="color: var(--text-secondary); margin-bottom: 1rem;">{{ Str::limit($thread->konten, 120) }}</p>
                        
                        <div style="display: flex; gap: 1.5rem; align-items: center; border-top: 1px solid var(--border); padding-top: 1rem;">
                            <span style="color: var(--text-secondary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem;">
                                💬 {{ $thread->replies_count }} Balasan
                            </span>
                            <span style="color: var(--text-secondary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem;">
                                👁 {{ $thread->views }} Dilihat
                            </span>
                            <div style="margin-left: auto;">
                                <a href="{{ route('mahasiswa.forum.thread', $thread->id) }}" class="btn btn-outline btn-sm">Lihat Diskusi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card" style="text-align: center; padding: 3rem;">
                <p style="color: var(--text-secondary);">Belum ada diskusi. Jadilah yang pertama memulai diskusi!</p>
            </div>
        @endforelse
    </div>
</div>
