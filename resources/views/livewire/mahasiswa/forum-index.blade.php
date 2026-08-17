<div class="fade-in">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="section-title">Forum Diskusi</h1>
            <p class="section-sub" style="color: var(--text-secondary);">Diskusikan materi dan tugas dengan dosen dan teman sekelas.</p>
        </div>
        <div style="width: 300px;">
            <input type="text" wire:model.live="search" class="form-input" placeholder="Cari mata kuliah...">
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
        @forelse($kelasList as $kelas)
            <div class="card" style="display: flex; flex-direction: column; height: 100%;">
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <span class="badge badge-teal">{{ $kelas->mataKuliah->kode ?? 'KODE' }}</span>
                        @if(isset($kelas->unread_threads_count) && $kelas->unread_threads_count > 0)
                            <span class="badge badge-red">{{ $kelas->unread_threads_count }} Baru</span>
                        @endif
                    </div>
                    <h3 class="course-title" style="font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--text-primary);">{{ $kelas->mataKuliah->nama ?? 'Nama Mata Kuliah' }}</h3>
                    <p class="course-teacher" style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                        Dosen: {{ $kelas->dosen->name ?? 'Belum ada dosen' }}
                    </p>
                    
                    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="background: var(--teal-dim); padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-teal); flex: 1; text-align: center;">
                            <div style="font-size: 1.5rem; font-weight: bold; color: var(--teal);">{{ $kelas->forum_threads_count ?? 0 }}</div>
                            <div style="font-size: 0.875rem; color: var(--text-secondary);">Total Thread</div>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('mahasiswa.forum.kelas', $kelas->id) }}" class="btn btn-primary" style="width: 100%; text-align: center;">Lihat Forum</a>
            </div>
        @empty
            <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <p style="color: var(--text-secondary);">Belum ada kelas atau tidak ditemukan mata kuliah.</p>
            </div>
        @endforelse
    </div>
</div>
