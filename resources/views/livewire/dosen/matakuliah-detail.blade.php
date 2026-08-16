<div class="fade-in">
    <div class="mb-4">
        <a href="{{ route('dosen.matakuliah.index') }}" class="text-sm font-medium hover:underline" style="color: var(--teal)">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Matakuliah
        </a>
    </div>

    <!-- Hero Card -->
    <div class="card p-6 mb-6" style="border-left: 4px solid var(--teal)">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="badge badge-teal">{{ $kelas->mataKuliah->kode ?? '-' }}</span>
                    <span class="badge badge-gray">{{ $kelas->semester->nama ?? '-' }}</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">{{ $kelas->mataKuliah->nama ?? 'Unknown MK' }}</h1>
                <p class="text-muted">Kelas: {{ $kelas->nama }} • {{ $kelas->hari }}, {{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}</p>
            </div>
            
            <div class="flex gap-4 bg-gray-50 p-4 rounded-lg">
                <div class="text-center px-4 border-r border-gray-200">
                    <p class="text-sm text-gray-500 mb-1">Mahasiswa</p>
                    <p class="text-xl font-bold" style="color: var(--teal)">{{ $kelas->mahasiswa->count() }}</p>
                </div>
                <div class="text-center px-4 border-r border-gray-200">
                    <p class="text-sm text-gray-500 mb-1">Pertemuan</p>
                    <p class="text-xl font-bold" style="color: var(--teal)">{{ $kelas->pertemuan->count() }}</p>
                </div>
                <div class="text-center px-4">
                    <p class="text-sm text-gray-500 mb-1">Tugas</p>
                    <p class="text-xl font-bold" style="color: var(--teal)">{{ $kelas->tugas->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex overflow-x-auto border-b mb-6" style="border-color: var(--border)">
        @foreach(['materi' => 'Materi & Konten', 'tugas' => 'Tugas', 'kuis' => 'Kuis', 'absensi' => 'Absensi', 'nilai' => 'Nilai Akhir', 'mahasiswa' => 'Mahasiswa'] as $key => $label)
            <button 
                wire:click="$set('activeTab', '{{ $key }}')"
                class="px-6 py-3 font-medium text-sm whitespace-nowrap border-b-2 transition-colors {{ $activeTab === $key ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                style="{{ $activeTab === $key ? 'border-color: var(--teal); color: var(--teal-dark);' : '' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        @if($activeTab === 'materi')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Daftar Pertemuan</h2>
                <button class="btn btn-primary btn-sm"><i class="fas fa-plus mr-2"></i> Tambah Pertemuan</button>
            </div>
            
            <div class="space-y-4">
                @forelse($kelas->pertemuan as $p)
                    <div class="card p-0 overflow-hidden" x-data="{ expanded: false }">
                        <div class="p-4 cursor-pointer hover:bg-gray-50 flex justify-between items-center" @click="expanded = !expanded">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white" style="background-color: var(--teal)">
                                    {{ $p->pertemuan_ke }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800">{{ $p->judul }}</h3>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }} • {{ $p->konten->count() }} Konten</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                        </div>
                        
                        <div x-show="expanded" x-collapse>
                            <div class="p-4 border-t border-gray-100 bg-gray-50">
                                <p class="text-sm text-gray-700 mb-4">{{ $p->deskripsi }}</p>
                                
                                <div class="space-y-2 mb-4">
                                    @forelse($p->konten as $konten)
                                        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded">
                                            <div class="flex items-center gap-3">
                                                <i class="fas fa-file-alt text-gray-400"></i>
                                                <span class="text-sm font-medium">{{ $konten->judul }}</span>
                                            </div>
                                            <div class="flex gap-2">
                                                <button class="text-xs text-blue-500 hover:underline">Edit</button>
                                                <button class="text-xs text-red-500 hover:underline">Hapus</button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500 italic">Belum ada konten.</p>
                                    @endforelse
                                </div>
                                
                                <button class="btn btn-outline btn-sm">Tambah Konten</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card p-8 text-center text-gray-500">
                        Belum ada pertemuan.
                    </div>
                @endforelse
            </div>
            
        @elseif($activeTab === 'tugas')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Daftar Tugas</h2>
                <a href="{{ route('dosen.tugas.index', ['kelas' => $kelas->id]) }}" class="btn btn-primary btn-sm">Kelola Tugas</a>
            </div>
            
            <div class="table-wrap">
                <table class="lms-table w-full">
                    <thead>
                        <tr>
                            <th>Judul Tugas</th>
                            <th>Tipe</th>
                            <th>Deadline</th>
                            <th>Progress</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas->tugas as $tugas)
                            <tr>
                                <td class="font-medium">{{ $tugas->judul }}</td>
                                <td><span class="badge badge-gray">{{ $tugas->tipe ?? 'File' }}</span></td>
                                <td class="{{ \Carbon\Carbon::parse($tugas->tenggat_waktu)->isPast() ? 'text-red-500' : 'text-green-600' }}">
                                    {{ \Carbon\Carbon::parse($tugas->tenggat_waktu)->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    @php
                                        $total = $kelas->mahasiswa->count();
                                        $submitted = $tugas->pengumpulan->count();
                                        $pct = $total > 0 ? ($submitted / $total) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="progress-wrap flex-1 w-24">
                                            <div class="progress-bar bg-teal-500" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-xs text-muted">{{ $submitted }}/{{ $total }}</span>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('dosen.tugas.nilai', ['kelas' => $kelas->id, 'tugas' => $tugas->id]) }}" class="text-sm font-medium text-teal-600 hover:underline">Nilai</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Belum ada tugas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        @elseif($activeTab === 'kuis')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Daftar Kuis</h2>
                <button class="btn btn-primary btn-sm">Buat Kuis</button>
            </div>
            
            <div class="table-wrap">
                <table class="lms-table w-full">
                    <thead>
                        <tr>
                            <th>Judul Kuis</th>
                            <th>Waktu</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas->kuis as $kuis)
                            <tr>
                                <td class="font-medium">{{ $kuis->judul }}</td>
                                <td>{{ \Carbon\Carbon::parse($kuis->waktu_mulai)->format('d M Y, H:i') }}</td>
                                <td>{{ $kuis->durasi }} Menit</td>
                                <td>
                                    @if(now()->isBefore($kuis->waktu_mulai))
                                        <span class="badge badge-gray">Draft/Menunggu</span>
                                    @elseif(now()->isBetween($kuis->waktu_mulai, $kuis->waktu_selesai))
                                        <span class="badge badge-teal">Berjalan</span>
                                    @else
                                        <span class="badge badge-orange">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="text-sm text-blue-500 hover:underline mr-2">Edit</button>
                                    <button class="text-sm text-teal-600 hover:underline">Hasil</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Belum ada kuis</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        @elseif($activeTab === 'absensi')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Ringkasan Absensi</h2>
                <a href="{{ route('dosen.absensi.index', ['kelas' => $kelas->id]) }}" class="btn btn-primary btn-sm">Kelola Absensi</a>
            </div>
            <div class="card p-8 text-center text-gray-500">
                Silakan kelola absensi di halaman Manajemen Absensi.
            </div>
            
        @elseif($activeTab === 'nilai')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Nilai Akhir</h2>
                <button class="btn btn-primary btn-sm">Hitung & Publikasi Nilai</button>
            </div>
            <div class="card p-8 text-center text-gray-500">
                Fitur perhitungan nilai akhir dalam pengembangan.
            </div>
            
        @elseif($activeTab === 'mahasiswa')
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Daftar Mahasiswa ({{ $kelas->mahasiswa->count() }})</h2>
                <button class="btn btn-outline btn-sm">Export Excel</button>
            </div>
            
            <div class="table-wrap">
                <table class="lms-table w-full">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Progress Materi</th>
                            <th>Tugas Selesai</th>
                            <th>Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas->mahasiswa as $mhs)
                            <tr>
                                <td class="font-medium text-gray-700">{{ $mhs->nim ?? '-' }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold">
                                            {{ substr($mhs->name, 0, 2) }}
                                        </div>
                                        <span>{{ $mhs->name }}</span>
                                    </div>
                                </td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Belum ada mahasiswa terdaftar</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
