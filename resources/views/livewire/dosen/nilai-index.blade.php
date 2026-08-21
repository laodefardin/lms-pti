<div class="w-full px-2 xl:px-4 space-y-5 pb-12 fade-in">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas, 'tab' => 'nilai']) }}"
               class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm inline-flex items-center gap-1 transition mb-2">
                <i class="fas fa-arrow-left"></i> Kembali ke {{ $kelas->mataKuliah->nama ?? 'Detail Kelas' }}
            </a>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Rekapitulasi Nilai Akhir</h1>
            <p class="text-[var(--text-secondary)] text-sm mt-0.5">Kelas: {{ $kelas->nama_kelas }}</p>
        </div>
        <button wire:click="exportExcel" class="btn btn-primary">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
        </button>
    </div>

    {{-- Stats (opsional, bisa ditambahkan rata-rata nilai kelas) --}}
    <div class="card p-0 overflow-hidden mt-6">
        <div class="flex items-center justify-between px-5 py-4 border-b border-[var(--border)]">
            <h2 class="font-bold text-[var(--text-primary)]">Data Nilai Mahasiswa</h2>
        </div>
        <div class="table-wrap">
            <table class="lms-table w-full">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">Kuis</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilaiList as $nilai)
                        <tr>
                            <td class="font-medium text-[var(--text-primary)]">{{ $nilai->mahasiswa->nim ?? '-' }}</td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-[var(--teal-dim)] flex items-center justify-center text-[var(--teal)] font-bold text-xs mr-3">
                                        {{ substr($nilai->mahasiswa->name ?? 'M', 0, 2) }}
                                    </div>
                                    <span class="text-[var(--text-primary)]">{{ $nilai->mahasiswa->name }}</span>
                                </div>
                            </td>
                            <td class="text-center text-[var(--text-secondary)]">{{ round($nilai->nilai_tugas ?? 0, 1) }}</td>
                            <td class="text-center text-[var(--text-secondary)]">{{ round($nilai->nilai_kuis ?? 0, 1) }}</td>
                            <td class="text-center text-[var(--text-secondary)]">{{ round($nilai->nilai_kehadiran ?? 0, 1) }}</td>
                            <td class="text-center text-[var(--text-secondary)]">{{ round($nilai->nilai_uts ?? 0, 1) }}</td>
                            <td class="text-center text-[var(--text-secondary)]">{{ round($nilai->nilai_uas ?? 0, 1) }}</td>
                            <td class="text-center font-bold text-[var(--text-primary)]">{{ round($nilai->nilai_akhir ?? 0, 2) }}</td>
                            <td class="text-center">
                                @php
                                    $gradeClass = match($nilai->grade) {
                                        'A' => 'badge-green',
                                        'B' => 'badge-teal',
                                        'C' => 'badge-orange',
                                        'D' => 'badge-red',
                                        'E' => 'badge-red',
                                        default => 'badge-gray'
                                    };
                                @endphp
                                <span class="badge {{ $gradeClass }}">{{ $nilai->grade ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center">
                                <div class="text-[var(--text-muted)] mb-3">
                                    <i class="fas fa-chart-bar text-4xl opacity-30"></i>
                                </div>
                                <p class="text-[var(--text-secondary)] font-medium">Belum ada data nilai.</p>
                                <p class="text-[var(--text-muted)] text-sm">Nilai akhir mahasiswa akan muncul di sini setelah tugas/kuis/absensi dinilai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
