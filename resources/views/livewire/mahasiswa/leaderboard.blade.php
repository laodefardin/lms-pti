<div class="fade-in">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="section-title">Leaderboard</h1>
            <p class="section-sub" style="color: var(--text-secondary);">Lihat peringkat poin tertinggi berdasarkan aktivitas.</p>
        </div>
        <select wire:model.live="filter" class="form-input" style="width: 200px;">
            <option value="semua">Semua Mahasiswa</option>
            <option value="kelas_saya">Teman Sekelas</option>
        </select>
    </div>

    @if($myRank)
        <div class="card" style="margin-bottom: 2rem; background: #14a7a0; color: white; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 0.5rem;">Peringkat Saya</h3>
                <p style="opacity: 0.9;">{{ $myRank->name }}</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 2rem; font-weight: bold;">Rank #{{ $myRank->rank }}</div>
                <div style="opacity: 0.9;">{{ $myRank->gamifikasi_poin_sum_poin ?? 0 }} Poin</div>
            </div>
        </div>
    @endif

    @if($top3->count() > 0)
        <!-- Top 3 Podium Visual -->
        <div style="display: flex; justify-content: center; align-items: flex-end; gap: 1rem; margin-bottom: 3rem; padding-top: 2rem;">
            <!-- Rank 2 -->
            @if(isset($top3[1]))
                <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 150px;">
                    <img src="{{ $top3[1]->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($top3[1]->name) }}" style="width: 80px; height: 80px; border-radius: 50%; border: 4px solid #C0C0C0; margin-bottom: 1rem;">
                    <div style="font-weight: bold; color: var(--text-primary); text-align: center; margin-bottom: 0.5rem;">{{ $top3[1]->name }}</div>
                    <div class="badge badge-gray" style="margin-bottom: 0.5rem;">{{ $top3[1]->gamifikasi_poin_sum_poin ?? 0 }} pts</div>
                    <div style="background: #C0C0C0; width: 100%; height: 80px; border-top-left-radius: 8px; border-top-right-radius: 8px; display: flex; justify-content: center; align-items: center; color: white; font-size: 1.5rem; font-weight: bold;">2</div>
                </div>
            @endif

            <!-- Rank 1 -->
            @if(isset($top3[0]))
                <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 150px;">
                    <img src="{{ $top3[0]->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($top3[0]->name) }}" style="width: 100px; height: 100px; border-radius: 50%; border: 4px solid #FFD700; margin-bottom: 1rem;">
                    <div style="font-weight: bold; color: var(--text-primary); text-align: center; margin-bottom: 0.5rem; font-size: 1.125rem;">{{ $top3[0]->name }}</div>
                    <div class="badge badge-orange" style="margin-bottom: 0.5rem;">{{ $top3[0]->gamifikasi_poin_sum_poin ?? 0 }} pts</div>
                    <div style="background: #FFD700; width: 100%; height: 120px; border-top-left-radius: 8px; border-top-right-radius: 8px; display: flex; justify-content: center; align-items: center; color: white; font-size: 2rem; font-weight: bold;">1</div>
                </div>
            @endif

            <!-- Rank 3 -->
            @if(isset($top3[2]))
                <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 150px;">
                    <img src="{{ $top3[2]->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($top3[2]->name) }}" style="width: 70px; height: 70px; border-radius: 50%; border: 4px solid #CD7F32; margin-bottom: 1rem;">
                    <div style="font-weight: bold; color: var(--text-primary); text-align: center; margin-bottom: 0.5rem;">{{ $top3[2]->name }}</div>
                    <div class="badge badge-gray" style="background: #CD7F32; color: white; border:none; margin-bottom: 0.5rem;">{{ $top3[2]->gamifikasi_poin_sum_poin ?? 0 }} pts</div>
                    <div style="background: #CD7F32; width: 100%; height: 60px; border-top-left-radius: 8px; border-top-right-radius: 8px; display: flex; justify-content: center; align-items: center; color: white; font-size: 1.25rem; font-weight: bold;">3</div>
                </div>
            @endif
        </div>

        @if($others->count() > 0)
            <div class="table-wrap">
                <table class="lms-table" style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 1rem; border-bottom: 1px solid var(--border);">Rank</th>
                            <th style="padding: 1rem; border-bottom: 1px solid var(--border);">Mahasiswa</th>
                            <th style="padding: 1rem; border-bottom: 1px solid var(--border);">Poin</th>
                            <th style="padding: 1rem; border-bottom: 1px solid var(--border);">Badge</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($others as $mhs)
                            <tr style="{{ $mhs->id === auth()->id() ? 'background: var(--teal-dim); border-left: 4px solid var(--teal);' : 'border-bottom: 1px solid var(--border);' }}">
                                <td style="padding: 1rem; font-weight: bold; color: var(--text-secondary);">#{{ $mhs->rank }}</td>
                                <td style="padding: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <img src="{{ $mhs->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($mhs->name) }}" style="width: 32px; height: 32px; border-radius: 50%;">
                                        <span style="font-weight: 500; color: var(--text-primary);">{{ $mhs->name }}</span>
                                    </div>
                                </td>
                                <td style="padding: 1rem; font-weight: bold; color: var(--teal);">{{ $mhs->gamifikasi_poin_sum_poin ?? 0 }}</td>
                                <td style="padding: 1rem;">
                                    <span class="badge badge-teal">Tingkat {{ max(1, floor(($mhs->gamifikasi_poin_sum_poin ?? 0) / 100)) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div class="card" style="text-align: center; padding: 3rem;">
            <p style="color: var(--text-secondary);">Belum ada data poin.</p>
        </div>
    @endif
</div>