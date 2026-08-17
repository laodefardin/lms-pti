<div class="fade-in">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <h1 class="section-title">Riwayat Kehadiran</h1>
            <p class="section-sub text-muted">Pantau persentase kehadiran Anda. Batas minimal 75%.</p>
        </div>
    </div>

    <div style="display: grid; gap: 1.5rem;">
        @forelse($dataKehadiran as $index => $data)
            <div class="card" x-data="{ open: false }" style="padding: 1.5rem; border: 1px solid var(--border);">
                {{-- Header Kelas --}}
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem;">{{ $data['kelas']->mataKuliah->nama ?? '' }}</h2>
                        <div style="display:flex; align-items:center; gap:0.5rem; font-size: 0.85rem; color: var(--text-secondary);">
                            <i class="fas fa-user-tie"></i> {{ $data['kelas']->dosen->name }} 
                            <span style="color:var(--border);">|</span>
                            <i class="fas fa-list-ol"></i> Total: {{ $data['total'] }} Pertemuan
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 1.8rem; font-weight: 800; color: {{ $data['persentase'] >= 75 ? 'var(--teal)' : 'var(--danger)' }}; line-height:1;">
                            {{ number_format($data['persentase'], 1) }}%
                        </div>
                        <div style="font-size: 0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color: var(--text-muted); margin-top:0.25rem;">Tingkat Kehadiran</div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress-wrap" style="height: 0.5rem; margin-bottom: 1.5rem; background: var(--input-bg);">
                    <div class="progress-bar" style="width: {{ $data['persentase'] }}%; background-color: {{ $data['persentase'] >= 75 ? 'var(--teal)' : 'var(--danger)' }};"></div>
                </div>
                
                @if($data['persentase'] < 75 && $data['total'] > 0)
                    <div style="margin-bottom: 1.5rem; padding: 0.85rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; background-color: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); display:flex; align-items:center; gap:0.5rem;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> Kehadiran Anda di bawah batas minimal kelulusan (75%).
                    </div>
                @endif

                {{-- Summary Badges --}}
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                    <div class="badge badge-green" style="padding:0.4rem 0.8rem; font-size:0.8rem;"><i class="fas fa-check-circle" style="margin-right:0.3rem;"></i> Hadir: {{ $data['hadir'] }}</div>
                    <div class="badge badge-orange" style="padding:0.4rem 0.8rem; font-size:0.8rem;"><i class="fas fa-envelope" style="margin-right:0.3rem;"></i> Izin: {{ $data['izin'] }}</div>
                    <div class="badge badge-teal" style="padding:0.4rem 0.8rem; font-size:0.8rem;"><i class="fas fa-briefcase-medical" style="margin-right:0.3rem;"></i> Sakit: {{ $data['sakit'] }}</div>
                    <div class="badge badge-red" style="padding:0.4rem 0.8rem; font-size:0.8rem;"><i class="fas fa-times-circle" style="margin-right:0.3rem;"></i> Alpha: {{ $data['alpha'] }}</div>
                </div>

                {{-- Accordion Toggle --}}
                <button @click="open = !open" class="btn btn-outline btn-full" style="justify-content:center; padding:0.6rem;">
                    <span x-text="open ? 'Sembunyikan Detail Pertemuan' : 'Lihat Detail Pertemuan'"></span>
                    <i class="fas fa-chevron-down" style="transition: transform 0.3s ease;" :style="open ? 'transform: rotate(180deg);' : ''"></i>
                </button>

                {{-- Detail Table --}}
                <div x-show="open" x-transition.opacity style="display: none; margin-top: 1.5rem;">
                    <div class="table-wrap" style="border: 1px solid var(--border); border-radius: 0.5rem; overflow: hidden;">
                        <table class="lms-table" style="width: 100%; text-align: left;">
                            <thead style="background: var(--input-bg);">
                                <tr>
                                    <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform:uppercase;">Pertemuan</th>
                                    <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform:uppercase;">Tanggal</th>
                                    <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform:uppercase;">Materi Pokok</th>
                                    <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform:uppercase; text-align: right;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['detail'] as $det)
                                    <tr style="border-bottom: 1px solid var(--border);">
                                        <td style="padding: 0.85rem 1rem; font-size: 0.85rem; font-weight:600; color: var(--text-primary);">Ke-{{ $det['pertemuan_ke'] }}</td>
                                        <td style="padding: 0.85rem 1rem; font-size: 0.85rem; color: var(--text-secondary);">
                                            @if($det['tanggal'])
                                                <i class="far fa-calendar-alt" style="margin-right:0.25rem;"></i> {{ \Carbon\Carbon::parse($det['tanggal'])->format('d M Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td style="padding: 0.85rem 1rem; font-size: 0.85rem; color: var(--text-secondary);">{{ $det['materi'] ?: '-' }}</td>
                                        <td style="padding: 0.85rem 1rem; text-align: right;">
                                            @if($det['status'] === 'hadir')
                                                <span class="badge badge-green">Hadir</span>
                                            @elseif($det['status'] === 'sakit')
                                                <span class="badge badge-teal">Sakit</span>
                                            @elseif($det['status'] === 'izin')
                                                <span class="badge badge-orange">Izin</span>
                                            @elseif($det['status'] === 'alpha')
                                                <span class="badge badge-red">Alpha</span>
                                            @else
                                                <span class="badge badge-gray">Belum Ada</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="card" style="text-align: center; padding: 4rem 2rem; border: 1px dashed var(--border);">
                <div style="font-size:3rem; margin-bottom:1rem; color:var(--text-muted);"><i class="fas fa-calendar-times"></i></div>
                <h3 style="font-size:1.2rem; font-weight:700; color:var(--text-primary); margin-bottom:0.5rem;">Tidak Ada Data Kehadiran</h3>
                <p style="color:var(--text-secondary); font-size:0.9rem;">Anda belum terdaftar di kelas manapun semester ini.</p>
            </div>
        @endforelse
    </div>
</div>
