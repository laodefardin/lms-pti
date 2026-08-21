<div class="space-y-6">
    {{-- Header / Hero --}}
    <div class="card p-6 border-l-4 border-l-[var(--teal)] flex flex-col md:flex-row justify-between md:items-center gap-6">
        <div>
            <a href="{{ route('mahasiswa.nilai.index') }}" class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm mb-2 inline-block transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Transkrip
            </a>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">
                {{ $kelas->mataKuliah->nama }}
            </h1>
            <p class="text-[var(--text-secondary)] mt-1">
                {{ $kelas->nama_kelas }} • Dosen: {{ $kelas->dosen->name }}
            </p>
        </div>

        @if($nilaiAkhir)
            <div class="flex items-center gap-4 bg-[var(--bg-card-hover)] p-4 rounded-xl border border-[var(--border)]">
                <div class="text-center px-4 border-r border-[var(--border)]">
                    <p class="text-xs text-[var(--text-muted)] font-bold uppercase tracking-wider mb-1">Skor Akhir</p>
                    <p class="text-3xl font-bold text-[var(--text-primary)]">{{ number_format($nilaiAkhir->nilai_akhir, 1) }}</p>
                </div>
                <div class="text-center px-4">
                    <p class="text-xs text-[var(--text-muted)] font-bold uppercase tracking-wider mb-1">Grade</p>
                    <span class="text-2xl font-bold px-3 py-1 rounded-lg 
                        {{ $nilaiAkhir->grade === 'A' ? 'bg-[var(--teal-dim)] text-[var(--teal)]' : 
                          ($nilaiAkhir->grade === 'E' ? 'bg-red-500/10 text-red-500' : 'bg-orange-500/10 text-orange-500') }}">
                        {{ $nilaiAkhir->grade }}
                    </span>
                </div>
            </div>
        @else
            <div class="bg-[var(--input-bg)] px-4 py-3 rounded-lg border border-[var(--border)]">
                <p class="text-[var(--text-secondary)] text-sm"><i class="fas fa-info-circle mr-2 text-[var(--teal)]"></i> Nilai Akhir belum diterbitkan.</p>
            </div>
        @endif
    </div>

    {{-- Bobot Penilaian --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="card p-4 text-center">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1">Bobot Kehadiran</p>
            <p class="text-xl font-bold text-[var(--text-primary)]">{{ $kelas->bobot_kehadiran }}%</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1">Bobot Tugas</p>
            <p class="text-xl font-bold text-[var(--text-primary)]">{{ $kelas->bobot_tugas }}%</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1">Bobot Kuis</p>
            <p class="text-xl font-bold text-[var(--text-primary)]">{{ $kelas->bobot_kuis }}%</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1">Bobot UTS</p>
            <p class="text-xl font-bold text-[var(--text-primary)]">{{ $kelas->bobot_uts }}%</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1">Bobot UAS</p>
            <p class="text-xl font-bold text-[var(--text-primary)]">{{ $kelas->bobot_uas }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kehadiran & UTS/UAS --}}
        <div class="space-y-6 lg:col-span-1">
            <div class="card p-6">
                <h3 class="font-bold text-[var(--text-primary)] mb-4 flex items-center gap-2">
                    <i class="fas fa-user-check text-[var(--teal)]"></i> Statistik Kehadiran
                </h3>
                
                <div class="flex items-center justify-center mb-6 relative">
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle cx="64" cy="64" r="54" stroke="var(--border)" stroke-width="12" fill="none" />
                        <circle cx="64" cy="64" r="54" stroke="var(--teal)" stroke-width="12" fill="none" stroke-dasharray="339.292" stroke-dashoffset="{{ 339.292 - (339.292 * $persenHadir / 100) }}" class="transition-all duration-1000" />
                    </svg>
                    <div class="absolute flex flex-col items-center justify-center text-center">
                        <span class="text-2xl font-bold text-[var(--text-primary)]">{{ $persenHadir }}%</span>
                    </div>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center p-2 rounded bg-[var(--input-bg)]">
                        <span class="text-[var(--text-secondary)]">Total Pertemuan</span>
                        <span class="font-bold text-[var(--text-primary)]">{{ $totalPertemuan }}</span>
                    </div>
                    <div class="flex justify-between items-center p-2 rounded bg-[var(--teal-dim)] text-[var(--teal)]">
                        <span>Hadir</span>
                        <span class="font-bold">{{ $hadir }}</span>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-bold text-[var(--text-primary)] mb-4 flex items-center gap-2">
                    <i class="fas fa-file-signature text-purple-500"></i> Nilai Ujian
                </h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1 text-sm">
                            <span class="text-[var(--text-secondary)] font-medium">Ujian Tengah Semester (UTS)</span>
                            <span class="font-bold text-[var(--text-primary)]">{{ $nilaiAkhir->nilai_uts ?? '-' }}</span>
                        </div>
                        <div class="w-full bg-[var(--border)] rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $nilaiAkhir->nilai_uts ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1 text-sm">
                            <span class="text-[var(--text-secondary)] font-medium">Ujian Akhir Semester (UAS)</span>
                            <span class="font-bold text-[var(--text-primary)]">{{ $nilaiAkhir->nilai_uas ?? '-' }}</span>
                        </div>
                        <div class="w-full bg-[var(--border)] rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $nilaiAkhir->nilai_uas ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rincian Tugas & Kuis --}}
        <div class="space-y-6 lg:col-span-2">
            
            {{-- Rincian Tugas --}}
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-[var(--border)] bg-[var(--bg-card-hover)] flex justify-between items-center">
                    <h3 class="font-bold text-[var(--text-primary)] flex items-center gap-2">
                        <i class="fas fa-tasks text-orange-500"></i> Rincian Tugas
                    </h3>
                    <span class="badge badge-teal">{{ $tugas->count() }} Tugas</span>
                </div>
                
                @if($tugas->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left lms-table">
                            <thead>
                                <tr>
                                    <th>Judul Tugas</th>
                                    <th>Status</th>
                                    <th class="text-right">Nilai</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tugas as $t)
                                    @php
                                        $pengumpulan = $pengumpulanTugas->get($t->id);
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="font-semibold text-[var(--text-primary)]">{{ $t->judul }}</div>
                                            <div class="text-xs text-[var(--text-muted)] mt-1">Tenggat: {{ $t->deadline->format('d M Y, H:i') }}</div>
                                        </td>
                                        <td>
                                            @if($pengumpulan)
                                                @if($pengumpulan->status === 'dinilai')
                                                    <span class="badge bg-green-500/10 text-green-500 text-xs">Dinilai</span>
                                                @else
                                                    <span class="badge bg-blue-500/10 text-blue-500 text-xs">Menunggu Penilaian</span>
                                                @endif
                                            @else
                                                <span class="badge bg-red-500/10 text-red-500 text-xs">Belum Kumpul</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($pengumpulan && $pengumpulan->nilai !== null)
                                                <span class="font-bold text-[var(--text-primary)]">{{ $pengumpulan->nilai }}</span>/100
                                            @else
                                                <span class="text-[var(--text-muted)]">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('mahasiswa.tugas.detail', $t->id) }}" class="btn-sm btn-ghost inline-flex items-center justify-center">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-[var(--text-muted)]">
                        <i class="fas fa-folder-open text-4xl mb-3 opacity-50"></i>
                        <p>Belum ada tugas pada mata kuliah ini.</p>
                    </div>
                @endif
            </div>

            {{-- Rincian Kuis --}}
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-[var(--border)] bg-[var(--bg-card-hover)] flex justify-between items-center">
                    <h3 class="font-bold text-[var(--text-primary)] flex items-center gap-2">
                        <i class="fas fa-question-circle text-blue-500"></i> Rincian Kuis
                    </h3>
                    <span class="badge badge-teal">{{ $kuis->count() }} Kuis</span>
                </div>
                
                @if($kuis->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left lms-table">
                            <thead>
                                <tr>
                                    <th>Judul Kuis</th>
                                    <th>Percobaan</th>
                                    <th class="text-right">Nilai Tertinggi</th>
                                    <th class="text-center">Riwayat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kuis as $k)
                                    @php
                                        $sesiList = $kuisSesi->get($k->id, collect());
                                        $sesiSelesai = $sesiList->where('status', 'selesai');
                                        $nilaiTertinggi = $sesiSelesai->max('nilai');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="font-semibold text-[var(--text-primary)]">{{ $k->judul }}</div>
                                            <div class="text-xs text-[var(--text-muted)] mt-1">Nilai Max: {{ $k->nilai_max }}</div>
                                        </td>
                                        <td>
                                            <span class="text-[var(--text-secondary)]">{{ $sesiList->count() }} / {{ $k->maks_percobaan ?: '∞' }}</span>
                                        </td>
                                        <td class="text-right">
                                            @if($sesiSelesai->count() > 0)
                                                <span class="font-bold text-[var(--text-primary)]">{{ number_format($nilaiTertinggi, 1) }}</span>/100
                                            @else
                                                <span class="text-[var(--text-muted)]">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($sesiSelesai->count() > 0)
                                                <div class="flex gap-1 justify-center flex-wrap max-w-[120px] mx-auto">
                                                    @foreach($sesiSelesai as $sesi)
                                                        <a href="{{ route('mahasiswa.kuis.hasil', $sesi->id) }}" title="Percobaan ke-{{ $sesi->percobaan_ke }}" class="w-6 h-6 rounded bg-[var(--teal-dim)] text-[var(--teal)] flex items-center justify-center text-xs font-bold hover:bg-[var(--teal)] hover:text-white transition">
                                                            {{ $sesi->percobaan_ke }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-xs text-[var(--text-muted)]">Belum dicoba</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-[var(--text-muted)]">
                        <i class="fas fa-list-alt text-4xl mb-3 opacity-50"></i>
                        <p>Belum ada kuis pada mata kuliah ini.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
