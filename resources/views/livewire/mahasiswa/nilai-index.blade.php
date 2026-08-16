<div class="fade-in">
    <div class="mb-6">
        <h1 class="section-title">Nilai Akademik</h1>
        <p class="section-sub text-muted">Rekapitulasi nilai akhir mata kuliah Anda.</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card p-5 flex items-center" style="background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 0.5rem; box-shadow: var(--shadow-card);">
            <div class="mr-4 p-3 rounded-full" style="background-color: #f3f4f6; color: #4b5563;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium" style="color: var(--text-muted);">Total Mata Kuliah Dinilai</p>
                <h3 class="text-2xl font-bold" style="color: var(--text-primary);">{{ $totalMk }}</h3>
            </div>
        </div>
        
        <div class="card p-5 flex items-center" style="background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 0.5rem; box-shadow: var(--shadow-card);">
            <div class="mr-4 p-3 rounded-full" style="background-color: var(--teal-light); color: var(--teal-dark);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium" style="color: var(--text-muted);">Rata-rata Nilai</p>
                <h3 class="text-2xl font-bold" style="color: var(--text-primary);">{{ number_format($rataRata, 2) }}</h3>
            </div>
        </div>
        
        <div class="card p-5 flex items-center" style="background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 0.5rem; box-shadow: var(--shadow-card);">
            <div class="mr-4 p-3 rounded-full" style="background-color: #fef3c7; color: #d97706;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium" style="color: var(--text-muted);">Nilai Terbaik</p>
                <h3 class="text-lg font-bold" style="color: var(--text-primary);">{{ $nilaiTerbaik ?: '-' }}</h3>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card p-0 overflow-hidden" style="background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 0.5rem; box-shadow: var(--shadow-card);">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse lms-table">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 1px solid var(--border);">
                        <th class="p-4 font-semibold text-sm" style="color: var(--text-secondary);">Mata Kuliah / Dosen</th>
                        <th class="p-4 font-semibold text-sm text-center" style="color: var(--text-secondary);">Kehadiran</th>
                        <th class="p-4 font-semibold text-sm text-center" style="color: var(--text-secondary);">Tugas</th>
                        <th class="p-4 font-semibold text-sm text-center" style="color: var(--text-secondary);">Kuis</th>
                        <th class="p-4 font-semibold text-sm text-center" style="color: var(--text-secondary);">UTS</th>
                        <th class="p-4 font-semibold text-sm text-center" style="color: var(--text-secondary);">UAS</th>
                        <th class="p-4 font-semibold text-sm text-center" style="color: var(--text-secondary);">Nilai Akhir</th>
                        <th class="p-4 font-semibold text-sm text-center" style="color: var(--text-secondary);">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilai as $n)
                        @php
                            $gradeColorStyle = match($n->grade) {
                                'A' => 'background-color: #dcfce7; color: var(--success);',
                                'B' => 'background-color: var(--teal-light); color: var(--teal-dark);',
                                'C' => 'background-color: #ffedd5; color: var(--warning);',
                                'D', 'E' => 'background-color: #fee2e2; color: var(--danger);',
                                default => 'background-color: #f3f4f6; color: var(--text-muted);'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 border-b last:border-0" style="border-bottom-color: var(--border);">
                            <td class="p-4">
                                <p class="font-bold text-sm" style="color: var(--text-primary);">{{ $n->kelas->mataKuliah->nama_mk }}</p>
                                <p class="text-xs" style="color: var(--text-muted);">{{ $n->kelas->dosen->name }}</p>
                            </td>
                            <td class="p-4 text-center text-sm" style="color: var(--text-secondary);">{{ number_format($n->nilai_kehadiran, 1) }}</td>
                            <td class="p-4 text-center text-sm" style="color: var(--text-secondary);">{{ number_format($n->nilai_tugas, 1) }}</td>
                            <td class="p-4 text-center text-sm" style="color: var(--text-secondary);">{{ number_format($n->nilai_kuis, 1) }}</td>
                            <td class="p-4 text-center text-sm" style="color: var(--text-secondary);">{{ number_format($n->nilai_uts, 1) }}</td>
                            <td class="p-4 text-center text-sm" style="color: var(--text-secondary);">{{ number_format($n->nilai_uas, 1) }}</td>
                            <td class="p-4 text-center font-bold" style="color: var(--text-primary);">{{ number_format($n->nilai_akhir, 2) }}</td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold" style="{{ $gradeColorStyle }}">
                                    {{ $n->grade }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center" style="color: var(--text-muted);">Belum ada rekapitulasi nilai yang dipublikasikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
