<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Rekapitulasi Nilai</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau dan unduh rekap nilai akhir mahasiswa per kelas.</p>
        </div>
        <button wire:click="exportExcel" class="btn btn-primary" {{ !$kelasId ? 'disabled' : '' }}>
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export Excel
        </button>
    </div>

    <div class="card mb-6">
        <label for="kelasId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Kelas</label>
        <select wire:model.live="kelasId" id="kelasId" class="form-input w-full md:w-1/3">
            @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}">{{ $kelas->mataKuliah->nama_mk }} - Kelas {{ $kelas->nama_kelas }}</option>
            @endforeach
        </select>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Tugas</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Kuis</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Hadir</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">UTS</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">UAS</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Nilai Akhir</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Grade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($nilaiList as $nilai)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="p-4 text-sm font-medium text-gray-900 dark:text-white">{{ $nilai->mahasiswa->nim ?? '-' }}</td>
                            <td class="p-4 text-sm text-gray-700 dark:text-gray-300">
                                <div class="flex items-center">
                                    <img src="{{ $nilai->mahasiswa->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($nilai->mahasiswa->name).'&background=14a7a0&color=fff' }}" alt="{{ $nilai->mahasiswa->name }}" class="w-8 h-8 rounded-full mr-3">
                                    {{ $nilai->mahasiswa->name }}
                                </div>
                            </td>
                            <td class="p-4 text-sm text-center text-gray-600 dark:text-gray-400">{{ round($nilai->nilai_tugas, 1) }}</td>
                            <td class="p-4 text-sm text-center text-gray-600 dark:text-gray-400">{{ round($nilai->nilai_kuis, 1) }}</td>
                            <td class="p-4 text-sm text-center text-gray-600 dark:text-gray-400">{{ round($nilai->nilai_kehadiran, 1) }}</td>
                            <td class="p-4 text-sm text-center text-gray-600 dark:text-gray-400">{{ round($nilai->nilai_uts, 1) }}</td>
                            <td class="p-4 text-sm text-center text-gray-600 dark:text-gray-400">{{ round($nilai->nilai_uas, 1) }}</td>
                            <td class="p-4 text-sm font-bold text-center text-gray-900 dark:text-white">{{ round($nilai->nilai_akhir, 2) }}</td>
                            <td class="p-4 text-center">
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
                                <span class="badge {{ $gradeClass }}">{{ $nilai->grade }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-gray-500 dark:text-gray-400">Belum ada data nilai untuk kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
