<div class="fade-in">
    <div class="mb-4">
        <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas->id, 'tab' => 'absensi']) }}" class="text-sm font-medium hover:underline" style="color: var(--teal)">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Kelas
        </a>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Manajemen Absensi</h1>
            <p class="text-muted">Kelas: {{ $kelas->nama }}</p>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Pertemuan -->
        <div class="w-full lg:w-1/3">
            <div class="card p-0 overflow-hidden sticky top-4">
                <div class="p-4 border-b bg-gray-50 border-gray-200">
                    <h3 class="font-bold text-gray-800">Pilih Pertemuan</h3>
                </div>
                <div class="max-h-[600px] overflow-y-auto">
                    @forelse($pertemuans as $p)
                        <div wire:click="selectPertemuan({{ $p->id }})" 
                             class="p-4 border-b border-gray-100 cursor-pointer transition-colors hover:bg-teal-50 {{ $selectedPertemuanId === $p->id ? 'bg-teal-50 border-l-4 border-l-teal-500' : 'border-l-4 border-l-transparent' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="text-xs font-semibold text-gray-500 mb-1">Pertemuan {{ $p->pertemuan_ke }}</div>
                                    <div class="font-medium text-sm text-gray-800 line-clamp-1">{{ $p->judul }}</div>
                                    <div class="text-xs text-muted mt-1">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</div>
                                </div>
                                <div>
                                    <!-- Status Badge - assume gray if empty for now -->
                                    <span class="w-2 h-2 rounded-full inline-block bg-gray-300"></span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500 text-sm">
                            Belum ada jadwal pertemuan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="w-full lg:w-2/3">
            @if($selectedPertemuan)
                <div class="card mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Pertemuan {{ $selectedPertemuan->pertemuan_ke }}</h2>
                            <p class="text-muted text-sm">{{ $selectedPertemuan->judul }} • {{ \Carbon\Carbon::parse($selectedPertemuan->tanggal)->format('d F Y') }}</p>
                        </div>
                        <button wire:click="autoFillHadir" class="btn btn-outline btn-sm text-teal-600 border-teal-500 hover:bg-teal-50">
                            <i class="fas fa-check-double mr-1"></i> Isi 'Hadir' Semua
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded text-sm border border-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-wrap mb-4">
                        <table class="lms-table w-full">
                            <thead>
                                <tr>
                                    <th class="w-10">No</th>
                                    <th>NIM / Nama</th>
                                    <th>Status Kehadiran</th>
                                    <th>Keterangan (Opsional)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelas->mahasiswa as $index => $mhs)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="font-medium text-sm text-gray-800">{{ $mhs->name }}</div>
                                            <div class="text-xs text-muted">{{ $mhs->nim ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <div class="flex gap-3 text-sm">
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="absensiData.{{ $mhs->id }}.status" value="hadir" class="text-teal-600 focus:ring-teal-500">
                                                    <span class="text-green-600 font-medium">Hadir</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="absensiData.{{ $mhs->id }}.status" value="izin" class="text-blue-600 focus:ring-blue-500">
                                                    <span class="text-blue-600">Izin</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="absensiData.{{ $mhs->id }}.status" value="sakit" class="text-orange-600 focus:ring-orange-500">
                                                    <span class="text-orange-600">Sakit</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="absensiData.{{ $mhs->id }}.status" value="alpha" class="text-red-600 focus:ring-red-500">
                                                    <span class="text-red-600">Alpha</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" wire:model="absensiData.{{ $mhs->id }}.keterangan" class="form-input text-sm p-1" placeholder="Catatan...">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-gray-500">Belum ada mahasiswa terdaftar di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end">
                        <button wire:click="saveAbsensi" class="btn btn-primary px-8">Simpan Absensi</button>
                    </div>
                </div>
            @else
                <div class="card p-12 text-center">
                    <div class="text-gray-300 mb-4"><i class="fas fa-calendar-check text-5xl"></i></div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Pilih Pertemuan</h3>
                    <p class="text-gray-500">Silakan pilih pertemuan di panel sebelah kiri untuk mulai mengisi absensi.</p>
                </div>
            @endif
        </div>
    </div>
</div>
