<div class="fade-in">
    <div class="topbar flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Laporan Akademik</h1>
            <p class="section-sub text-sm" style="color: var(--text-muted)">Statistik akademik semester aktif.</p>
        </div>
        <button wire:click="exportPdf" class="btn btn-outline flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export PDF
        </button>
    </div>

    @if (session()->has('message'))
        <div class="badge badge-green mb-4 p-3 rounded block w-full">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Stat Cards -->
        <div class="stat-card p-6 rounded-lg flex items-center" style="background: var(--bg-card); box-shadow: var(--shadow-card);">
            <div class="stat-icon-teal p-4 rounded-full mr-4" style="background: var(--teal-dim); color: var(--teal-dark);">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium" style="color: var(--text-muted)">Total Kelas Aktif</p>
                <p class="text-2xl font-bold" style="color: var(--text-primary)">{{ $totalKelas }}</p>
            </div>
        </div>

        <div class="stat-card p-6 rounded-lg flex items-center" style="background: var(--bg-card); box-shadow: var(--shadow-card);">
            <div class="stat-icon-green p-4 rounded-full mr-4 bg-green-100 text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium" style="color: var(--text-muted)">Rata-rata Kehadiran</p>
                <p class="text-2xl font-bold" style="color: var(--text-primary)">{{ $rataKehadiran }}%</p>
            </div>
        </div>

        <div class="stat-card p-6 rounded-lg flex items-center" style="background: var(--bg-card); box-shadow: var(--shadow-card);">
            <div class="stat-icon-purple p-4 rounded-full mr-4 bg-purple-100 text-purple-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium" style="color: var(--text-muted)">Total Nilai Masuk</p>
                <p class="text-2xl font-bold" style="color: var(--text-primary)">{{ $totalGrades }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Grade Distribution Chart -->
        <div class="card">
            <h3 class="section-title text-lg mb-4">Distribusi Grade</h3>
            <div class="flex flex-col space-y-4">
                @foreach(['A', 'B', 'C', 'D', 'E'] as $grade)
                    @php
                        $count = $gradeDistribution[$grade];
                        $percent = $totalGrades > 0 ? ($count / $totalGrades) * 100 : 0;
                        $colors = [
                            'A' => 'bg-green-500',
                            'B' => 'bg-teal-500',
                            'C' => 'bg-yellow-500',
                            'D' => 'bg-orange-500',
                            'E' => 'bg-red-500',
                        ];
                    @endphp
                    <div class="flex items-center">
                        <span class="w-8 font-bold" style="color: var(--text-primary)">{{ $grade }}</span>
                        <div class="flex-1 ml-2 mr-4 bg-gray-200 rounded-full h-4 overflow-hidden">
                            <div class="{{ $colors[$grade] }} h-4 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="w-12 text-right text-sm" style="color: var(--text-muted)">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Average Grades per Class -->
        <div class="card">
            <h3 class="section-title text-lg mb-4">Rata-rata Nilai per Kelas</h3>
            <div class="table-wrap overflow-y-auto max-h-64">
                <table class="lms-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="text-left">Kelas</th>
                            <th class="text-center">Jml Mhs</th>
                            <th class="text-center">Rata-rata Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelasStats as $stat)
                            <tr class="border-b" style="border-color: var(--border);">
                                <td class="py-2 font-medium" style="color: var(--text-primary)">{{ $stat['nama'] }}</td>
                                <td class="py-2 text-center">{{ $stat['jumlah_mhs'] }}</td>
                                <td class="py-2 text-center">
                                    <span class="badge {{ is_numeric($stat['rata_nilai']) && $stat['rata_nilai'] >= 80 ? 'badge-green' : (is_numeric($stat['rata_nilai']) && $stat['rata_nilai'] >= 60 ? 'badge-teal' : 'badge-orange') }}">
                                        {{ is_numeric($stat['rata_nilai']) ? number_format($stat['rata_nilai'], 2) : '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-500">Belum ada data nilai di kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
