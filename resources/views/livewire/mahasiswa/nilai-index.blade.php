<div class="fade-in">
    <div style="margin-bottom:1.5rem;">
        <h1 class="section-title">Nilai Akademik</h1>
        <p class="section-sub text-muted">Rekapitulasi nilai akhir mata kuliah Anda.</p>
    </div>

    <!-- Stats -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin-bottom:2rem;">
        <div class="card card-gray stat-card">
            <div class="stat-icon stat-icon-gray"><i class="fas fa-book-open"></i></div>
            <div>
                <div class="stat-value">{{ $totalMk }}</div>
                <div class="stat-label">Total Mata Kuliah Dinilai</div>
            </div>
        </div>
        
        <div class="card card-teal stat-card">
            <div class="stat-icon stat-icon-teal"><i class="fas fa-chart-line"></i></div>
            <div>
                <div class="stat-value">{{ number_format($rataRata, 2) }}</div>
                <div class="stat-label">Rata-rata Nilai</div>
            </div>
        </div>
        
        <div class="card card-orange stat-card">
            <div class="stat-icon stat-icon-orange"><i class="fas fa-medal"></i></div>
            <div>
                <div class="stat-value" style="font-size:1.1rem; line-height: 1.2;">{{ $nilaiTerbaik ?: '-' }}</div>
                <div class="stat-label">Nilai Terbaik</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card" style="padding:0; overflow:hidden;">
        <div class="table-wrap">
            <table class="lms-table">
                <thead>
                    <tr>
                        <th>Mata Kuliah / Dosen</th>
                        <th style="text-align:center;">Kehadiran</th>
                        <th style="text-align:center;">Tugas</th>
                        <th style="text-align:center;">Kuis</th>
                        <th style="text-align:center;">UTS</th>
                        <th style="text-align:center;">UAS</th>
                        <th style="text-align:center;">Nilai Akhir</th>
                        <th style="text-align:center;">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilai as $n)
                        @php
                            $gradeColorClass = match($n->grade) {
                                'A' => 'badge-green',
                                'B' => 'badge-teal',
                                'C' => 'badge-orange',
                                'D', 'E' => 'badge-red',
                                default => 'badge-gray'
                            };
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('mahasiswa.nilai.detail', $n->kelas->id) }}" wire:navigate style="text-decoration:none;">
                                    <p style="font-weight:700; color:var(--teal); margin-bottom:2px; transition: color 0.2s;" onmouseover="this.style.color='var(--teal-dark)'" onmouseout="this.style.color='var(--teal)'">
                                        {{ $n->kelas->mataKuliah->nama }} <i class="fas fa-external-link-alt" style="font-size:0.7rem; margin-left:4px;"></i>
                                    </p>
                                </a>
                                <p style="font-size:0.75rem; color:var(--text-secondary);">{{ $n->kelas->dosen->name }}</p>
                            </td>
                            <td style="text-align:center;">{{ number_format($n->nilai_kehadiran, 1) }}</td>
                            <td style="text-align:center;">{{ number_format($n->nilai_tugas, 1) }}</td>
                            <td style="text-align:center;">{{ number_format($n->nilai_kuis, 1) }}</td>
                            <td style="text-align:center;">{{ number_format($n->nilai_uts, 1) }}</td>
                            <td style="text-align:center;">{{ number_format($n->nilai_uas, 1) }}</td>
                            <td style="text-align:center; font-weight:700; color:var(--text-primary);">{{ number_format($n->nilai_akhir, 2) }}</td>
                            <td style="text-align:center;">
                                <span class="badge {{ $gradeColorClass }}">
                                    {{ $n->grade }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding:2rem; text-align:center; color:var(--text-muted);">Belum ada rekapitulasi nilai yang dipublikasikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
