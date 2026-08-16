<div class="fade-in">
    <div class="mb-4">
        <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas->id, 'tab' => 'tugas']) }}" class="text-sm font-medium hover:underline" style="color: var(--teal)">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke {{ $kelas->mataKuliah->nama ?? 'Detail Kelas' }}
        </a>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Manajemen Tugas</h1>
            <p class="text-muted">Kelas: {{ $kelas->nama }}</p>
        </div>
        <button class="btn btn-primary"><i class="fas fa-plus mr-2"></i> Buat Tugas Baru</button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Tugas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTugas }}</p>
                </div>
                <div class="stat-icon stat-icon-teal">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Pengumpulan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPengumpulan }}</p>
                </div>
                <div class="stat-icon stat-icon-green">
                    <i class="fas fa-upload"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Belum Dinilai</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $belumDinilai }}</p>
                </div>
                <div class="stat-icon stat-icon-orange">
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="lms-table w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Tugas</th>
                        <th>Tipe & Bobot</th>
                        <th>Deadline</th>
                        <th>Pengumpulan</th>
                        <th>Dinilai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas->tugas as $index => $tugas)
                        @php
                            $isPast = \Carbon\Carbon::parse($tugas->tenggat_waktu)->isPast();
                            $submittedCount = $tugas->pengumpulan->count();
                            $gradedCount = $tugas->pengumpulan->whereNotNull('nilai')->count();
                            $progress = $totalMahasiswa > 0 ? ($submittedCount / $totalMahasiswa) * 100 : 0;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-medium">{{ $tugas->judul }}</td>
                            <td>
                                <div class="text-xs badge badge-gray mb-1">{{ $tugas->tipe ?? 'File' }}</div>
                                <div class="text-xs text-muted">{{ $tugas->bobot ?? 100 }} Poin</div>
                            </td>
                            <td class="{{ $isPast ? 'text-red-500 font-medium' : 'text-gray-700' }}">
                                {{ \Carbon\Carbon::parse($tugas->tenggat_waktu)->format('d M Y, H:i') }}
                                @if($isPast)
                                    <div class="text-xs mt-1">Berakhir</div>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="progress-wrap flex-1 w-20">
                                        <div class="progress-bar {{ $progress == 100 ? 'bg-green-500' : 'bg-teal-500' }}" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span class="text-xs text-muted">{{ $submittedCount }}/{{ $totalMahasiswa }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $gradedCount == $submittedCount && $submittedCount > 0 ? 'badge-green' : 'badge-orange' }}">
                                    {{ $gradedCount }} / {{ $submittedCount }}
                                </span>
                            </td>
                            <td>
                                @if($isPast)
                                    <span class="badge badge-gray">Ditutup</span>
                                @else
                                    <span class="badge badge-teal">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('dosen.tugas.nilai', ['kelas' => $kelas->id, 'tugas' => $tugas->id]) }}" class="btn btn-sm btn-outline text-teal-600 border-teal-500 hover:bg-teal-50">Nilai</a>
                                    <button class="text-sm text-gray-500 hover:text-blue-500"><i class="fas fa-edit"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8">
                                <div class="text-gray-400 mb-2"><i class="fas fa-clipboard-list text-3xl"></i></div>
                                <p class="text-gray-500">Belum ada tugas di kelas ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
