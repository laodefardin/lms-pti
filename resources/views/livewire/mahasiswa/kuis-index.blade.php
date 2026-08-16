<div class="fade-in">

    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.4rem; font-weight:800; color:var(--text-primary); margin-bottom:0.25rem;">Kuis & Ujian ⚡</h1>
        <p style="color:var(--text-secondary); font-size:0.875rem;">Semua kuis dan ujian yang tersedia untuk kamu</p>
    </div>

    @if($kuisList->isEmpty())
        <div class="card" style="text-align:center; padding:4rem 2rem;">
            <div style="font-size:3.5rem; margin-bottom:1rem;">⚡</div>
            <div style="font-size:1rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem;">Belum ada kuis aktif</div>
            <div style="color:var(--text-secondary); font-size:0.85rem;">Kuis akan muncul di sini ketika dosen mengaktifkannya.</div>
        </div>
    @else
    <div style="display:flex; flex-direction:column; gap:0.875rem;">
        @foreach($kuisList as $item)
        @php
            $kuis   = $item['kuis'];
            $sesi   = $item['sesi'];
            $status = $item['status'];

            $statusConfig = [
                'belum_buka'     => ['label'=>'Belum Dibuka',    'class'=>'badge-gray',   'icon'=>'🔒'],
                'bisa_mulai'     => ['label'=>'Bisa Dikerjakan', 'class'=>'badge-teal',   'icon'=>'▶️'],
                'sedang_berjalan'=> ['label'=>'Sedang Dikerjakan','class'=>'badge-orange','icon'=>'⏳'],
                'selesai'        => ['label'=>'Selesai',         'class'=>'badge-green',  'icon'=>'✅'],
                'kadaluarsa'     => ['label'=>'Sudah Tutup',     'class'=>'badge-red',    'icon'=>'⛔'],
            ][$status] ?? ['label'=>$status, 'class'=>'badge-gray', 'icon'=>'❓'];
        @endphp

        <div class="card" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; {{ $status === 'bisa_mulai' ? 'border-color:var(--border-teal); background:linear-gradient(135deg,var(--teal-dim),var(--bg-card));' : '' }}">
            <div style="display:flex; align-items:center; gap:1rem; flex:1; min-width:0;">
                {{-- Icon --}}
                <div style="width:48px; height:48px; background:var(--input-bg); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0;">{{ $statusConfig['icon'] }}</div>

                <div style="min-width:0;">
                    <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.3rem;">
                        <span class="badge badge-teal" style="font-size:0.65rem;">{{ $kuis->kelas->mataKuliah->kode }}</span>
                        <span class="badge {{ $statusConfig['class'] }}">{{ $statusConfig['label'] }}</span>
                        @if($kuis->tipe === 'uts') <span class="badge badge-orange">UTS</span>
                        @elseif($kuis->tipe === 'uas') <span class="badge badge-red">UAS</span>
                        @endif
                    </div>
                    <div style="font-size:0.95rem; font-weight:700; color:var(--text-primary); margin-bottom:0.2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $kuis->judul }}</div>
                    <div style="font-size:0.75rem; color:var(--text-secondary); display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                        <span>📚 {{ $kuis->kelas->mataKuliah->nama }}</span>
                        <span>⏱ {{ $kuis->durasi_menit }} menit</span>
                        <span>📝 {{ $kuis->soal->count() }} soal</span>
                        @if($kuis->passing_grade)
                        <span>🎯 KKM {{ $kuis->passing_grade }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Deadline + Nilai --}}
            <div style="text-align:right; flex-shrink:0; min-width:160px;">
                @if($status === 'selesai' && $sesi)
                    <div style="font-size:1.5rem; font-weight:800; color:{{ $sesi->nilai_akhir >= ($kuis->passing_grade ?? 60) ? 'var(--success)' : 'var(--danger)' }}; margin-bottom:0.2rem;">{{ $sesi->nilai_akhir ?? '-' }}</div>
                    <div style="font-size:0.72rem; color:var(--text-muted);">{{ $sesi->selesai_at?->locale('id')->diffForHumans() }}</div>
                @elseif(in_array($status, ['bisa_mulai','sedang_berjalan']))
                    <div style="font-size:0.75rem; color:var(--warning); font-weight:600; margin-bottom:0.3rem;">
                        ⏰ Tutup: {{ $kuis->tutup_at->locale('id')->isoFormat('D MMM, HH:mm') }}
                    </div>
                    @if($status === 'bisa_mulai')
                    <a href="{{ route('mahasiswa.kuis.engine', $kuis) }}" class="btn btn-primary btn-sm">Kerjakan →</a>
                    @else
                    <a href="{{ route('mahasiswa.kuis.engine', $kuis) }}" class="btn btn-outline btn-sm" style="color:var(--warning); border-color:rgba(245,158,11,0.4);">Lanjutkan →</a>
                    @endif
                @elseif($status === 'belum_buka')
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-bottom:0.3rem;">
                        Buka: {{ $kuis->buka_at->locale('id')->isoFormat('D MMM, HH:mm') }}
                    </div>
                @elseif($status === 'kadaluarsa')
                    <div style="font-size:0.75rem; color:var(--text-muted);">Kuis sudah ditutup</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
