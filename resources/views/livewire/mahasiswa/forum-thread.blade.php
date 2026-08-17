<div class="fade-in">
    <div style="margin-bottom: 1.5rem; display: flex; gap: 0.5rem; align-items: center; color: var(--text-secondary);">
        <a href="{{ route('mahasiswa.forum.index') }}" style="color: var(--teal); text-decoration: none;">Forum</a> &gt;
        <a href="{{ route('mahasiswa.forum.kelas', $thread->kelas_id) }}" style="color: var(--teal); text-decoration: none;">{{ $thread->kelas->mataKuliah->nama ?? 'Kelas' }}</a> &gt;
        <span>{{ Str::limit($thread->judul, 30) }}</span>
    </div>

    <!-- Main Thread -->
    <div class="card" style="margin-bottom: 2rem; border-top: 4px solid var(--teal);">
        <div style="display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1.5rem;">
            <img src="{{ $thread->user->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($thread->user->name ?? 'User') }}" alt="Avatar" style="width: 50px; height: 50px; border-radius: 50%;">
            <div style="flex: 1;">
                <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.25rem;">
                    <span style="font-weight: bold; font-size: 1.125rem; color: var(--text-primary);">{{ $thread->user->name ?? 'User' }}</span>
                    <span class="badge badge-teal">Pembuat Thread</span>
                </div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">
                    Diposting {{ $thread->created_at->diffForHumans() }}
                </div>
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

        <h1 style="font-size: 1.5rem; font-weight: bold; color: var(--text-primary); margin-bottom: 1rem;">{{ $thread->judul }}</h1>
        <div class="prose" style="color: var(--text-primary); line-height: 1.6; white-space: pre-wrap; max-width: 100%;">{{ $thread->konten }}</div>
    </div>

    <!-- Replies -->
    <h3 class="section-title" style="margin-bottom: 1.5rem;">{{ $replies->count() }} Balasan</h3>

    <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
        @foreach($replies as $reply)
            <div class="card" style="{{ $reply->is_solution ? 'border: 2px solid var(--success);' : '' }}">
                <div style="display: flex; gap: 1rem; align-items: flex-start;">
                    <img src="{{ $reply->user->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name ?? 'User') }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <div>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <span style="font-weight: bold; color: var(--text-primary);">{{ $reply->user->name ?? 'User' }}</span>
                                    @if($reply->user_id === $thread->user_id)
                                        <span class="badge badge-teal">Pembuat Thread</span>
                                    @endif
                                    @if($reply->is_solution)
                                        <span class="badge badge-green"><i class="fas fa-check-circle"></i> Solusi</span>
                                    @endif
                                </div>
                                <div style="color: var(--text-secondary); font-size: 0.875rem;">
                                    {{ $reply->created_at->diffForHumans() }}
                                </div>
                            </div>
                            @if(Auth::id() === $thread->user_id && !$reply->is_solution)
                                <button wire:click="markSolution({{ $reply->id }})" class="btn btn-outline btn-sm">Tandai Solusi</button>
                            @endif
                        </div>
                        <div style="color: var(--text-primary); line-height: 1.5; white-space: pre-wrap;">{{ $reply->konten }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(!$thread->is_closed)
        <div class="card">
            <h3 class="section-title" style="margin-bottom: 1rem;">Kirim Balasan</h3>
            @if (session()->has('success'))
                <div style="background: var(--success); color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif
            <div style="margin-bottom: 1rem;">
                <textarea wire:model="replyKonten" class="form-input" rows="4" placeholder="Tulis balasan Anda di sini..."></textarea>
                @error('replyKonten') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div style="text-align: right;">
                <button wire:click="postReply" class="btn btn-primary">Kirim Balasan</button>
            </div>
        </div>
    @else
        <div class="card" style="text-align: center; background: var(--bg-card); color: var(--text-secondary);">
            Thread ini telah ditutup untuk balasan baru.
        </div>
    @endif
</div>
