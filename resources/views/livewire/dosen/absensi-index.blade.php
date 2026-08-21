<div class="w-full px-2 xl:px-4 space-y-5 pb-12 fade-in">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas, 'tab' => 'absensi']) }}"
               class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm inline-flex items-center gap-1 transition mb-2">
                <i class="fas fa-arrow-left"></i> Kembali ke {{ $kelas->mataKuliah->nama ?? 'Detail Kelas' }}
            </a>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Manajemen Absensi</h1>
            <p class="text-[var(--text-secondary)] text-sm mt-0.5">Kelas: {{ $kelas->nama_kelas }}</p>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Pertemuan -->
        <div class="w-full lg:w-1/3">
            <div class="card p-0 overflow-hidden sticky top-4">
                <div class="p-4 border-b border-[var(--border)]" style="background: var(--bg-body)">
                    <h3 class="font-bold text-[var(--text-primary)]">Pilih Pertemuan</h3>
                </div>
                <div class="max-h-[600px] overflow-y-auto">
                    @forelse($pertemuans as $p)
                        <div wire:click="selectPertemuan({{ $p->id }})"
                             class="p-4 border-b border-[var(--border)] cursor-pointer transition-colors
                                    hover:bg-[var(--teal-dim)]
                                    {{ $selectedPertemuanId === $p->id ? 'bg-[var(--teal-dim)] border-l-4 border-l-[var(--teal)]' : 'border-l-4 border-l-transparent' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="text-xs font-semibold text-[var(--text-muted)] mb-1">Pertemuan {{ $p->nomor }}</div>
                                    <div class="font-medium text-sm text-[var(--text-primary)] line-clamp-1">{{ $p->topik ?: 'Pertemuan '.$p->nomor }}</div>
                                    @if($p->tanggal)
                                        <div class="text-xs text-[var(--text-muted)] mt-1">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</div>
                                    @endif
                                </div>
                                <div>
                                    <!-- Status Badge - assume gray if empty for now -->
                                    <span class="w-2 h-2 rounded-full inline-block" style="background: var(--border)"></span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-[var(--text-muted)] text-sm">
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
                            <h2 class="text-xl font-bold text-[var(--text-primary)]">Pertemuan {{ $selectedPertemuan->nomor }}</h2>
                            <p class="text-[var(--text-muted)] text-sm">{{ $selectedPertemuan->topik ?: 'Pertemuan '.$selectedPertemuan->nomor }} @if($selectedPertemuan->tanggal) • {{ \Carbon\Carbon::parse($selectedPertemuan->tanggal)->format('d F Y') }} @endif</p>
                        </div>
                        <button wire:click="autoFillHadir" class="btn btn-outline btn-sm">
                            <i class="fas fa-check-double mr-1"></i> Isi 'Hadir' Semua
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-3 rounded text-sm border font-medium"
                             style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3); color: #10B981;">
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
                                        <td class="text-center text-[var(--text-muted)]">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="font-medium text-sm text-[var(--text-primary)]">{{ $mhs->name }}</div>
                                            <div class="text-xs text-[var(--text-muted)]">{{ $mhs->nim ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <div class="flex gap-3 text-sm">
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="absensiData.{{ $mhs->id }}.status" value="hadir" style="accent-color: var(--success)">
                                                    <span style="color: var(--success)" class="font-medium">Hadir</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="absensiData.{{ $mhs->id }}.status" value="izin" style="accent-color: #3b82f6">
                                                    <span style="color: #3b82f6">Izin</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="absensiData.{{ $mhs->id }}.status" value="sakit" style="accent-color: var(--warning)">
                                                    <span style="color: var(--warning)">Sakit</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="absensiData.{{ $mhs->id }}.status" value="alpha" style="accent-color: var(--danger)">
                                                    <span style="color: var(--danger)">Alpha</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" wire:model="absensiData.{{ $mhs->id }}.keterangan" class="form-input text-sm py-1.5 px-2" placeholder="Catatan...">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-8 text-[var(--text-muted)]">Belum ada mahasiswa terdaftar di kelas ini.</td>
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
                    <div class="text-[var(--text-muted)] mb-4 opacity-50"><i class="fas fa-calendar-check text-5xl"></i></div>
                    <h3 class="text-lg font-bold text-[var(--text-primary)] mb-2">Pilih Pertemuan</h3>
                    <p class="text-[var(--text-secondary)]">Silakan pilih pertemuan di panel sebelah kiri untuk mulai mengisi absensi.</p>
                </div>
            @endif
        </div>
    </div>
</div>
