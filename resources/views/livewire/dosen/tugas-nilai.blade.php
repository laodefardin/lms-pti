<div class="fade-in">
    <div class="mb-4">
        <a href="{{ route('dosen.tugas.index', ['kelas' => $kelas->id]) }}" class="text-sm font-medium hover:underline" style="color: var(--teal)">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Tugas
        </a>
    </div>

    <!-- Header Card -->
    <div class="card p-6 mb-6" style="border-top: 4px solid var(--teal)">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $tugas->judul }}</h1>
                <p class="text-muted text-sm mb-1">Kelas: {{ $kelas->nama }} • Bobot: {{ $tugas->bobot ?? 100 }} Poin</p>
                <p class="text-sm">
                    Deadline: <span class="{{ \Carbon\Carbon::parse($tugas->tenggat_waktu)->isPast() ? 'text-red-500 font-medium' : 'text-gray-700' }}">{{ \Carbon\Carbon::parse($tugas->tenggat_waktu)->format('d M Y, H:i') }}</span>
                </p>
            </div>
            <div class="flex gap-4">
                <div class="bg-teal-50 px-4 py-2 rounded-lg text-center border border-teal-100">
                    <p class="text-xs text-teal-600 font-medium mb-1">Terkumpul</p>
                    <p class="text-lg font-bold text-teal-800">{{ $pengumpulans->count() }} / {{ $kelas->mahasiswa->count() }}</p>
                </div>
                <div class="bg-orange-50 px-4 py-2 rounded-lg text-center border border-orange-100">
                    <p class="text-xs text-orange-600 font-medium mb-1">Dinilai</p>
                    <p class="text-lg font-bold text-orange-800">{{ $pengumpulans->whereNotNull('nilai')->count() }} / {{ $pengumpulans->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-6">
        <h2 class="text-lg font-bold mb-4 px-1">Mahasiswa yang Mengumpulkan</h2>
        <div class="table-wrap">
            <table class="lms-table w-full">
                <thead>
                    <tr>
                        <th>NIM / Nama</th>
                        <th>Waktu Kumpul</th>
                        <th>File/Link</th>
                        <th>Nilai & Feedback</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengumpulans as $p)
                        <tr>
                            <td>
                                <div class="font-medium text-gray-800">{{ $p->mahasiswa->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-muted">{{ $p->mahasiswa->nim ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="text-sm {{ \Carbon\Carbon::parse($p->waktu_pengumpulan)->gt(\Carbon\Carbon::parse($tugas->tenggat_waktu)) ? 'text-red-500' : 'text-gray-600' }}">
                                    {{ \Carbon\Carbon::parse($p->waktu_pengumpulan)->format('d M Y, H:i') }}
                                    @if(\Carbon\Carbon::parse($p->waktu_pengumpulan)->gt(\Carbon\Carbon::parse($tugas->tenggat_waktu)))
                                        <div class="text-xs font-medium bg-red-100 text-red-700 px-1 py-0.5 rounded inline-block mt-1">Terlambat</div>
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($p->file_path)
                                    <a href="#" class="btn btn-sm btn-outline text-xs"><i class="fas fa-download mr-1"></i> File</a>
                                @endif
                                @if($p->link)
                                    <a href="{{ $p->link }}" target="_blank" class="btn btn-sm btn-outline text-xs mt-1"><i class="fas fa-external-link-alt mr-1"></i> Link</a>
                                @endif
                            </td>
                            <td class="w-1/3">
                                @if($editId === $p->id)
                                    <div class="flex flex-col gap-2">
                                        <input type="number" wire:model="nilai" class="form-input text-sm p-1" placeholder="Nilai (0-100)">
                                        @error('nilai') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        <textarea wire:model="feedback" class="form-input text-sm p-1 h-16" placeholder="Komentar / Feedback"></textarea>
                                        <div class="flex gap-2">
                                            <button wire:click="saveNilai" class="btn btn-sm btn-primary text-xs py-1">Simpan</button>
                                            <button wire:click="cancelEdit" class="btn btn-sm btn-ghost text-xs py-1">Batal</button>
                                        </div>
                                    </div>
                                @else
                                    @if($p->nilai !== null)
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="badge {{ $p->nilai >= 80 ? 'badge-green' : ($p->nilai >= 60 ? 'badge-orange' : 'badge-red') }} text-sm font-bold">
                                                {{ $p->nilai }}
                                            </span>
                                        </div>
                                        @if($p->feedback)
                                            <p class="text-xs text-gray-600 italic line-clamp-2">"{{ $p->feedback }}"</p>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400 italic">Belum dinilai</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($editId !== $p->id)
                                    <button wire:click="openEdit({{ $p->id }})" class="btn btn-sm {{ $p->nilai === null ? 'btn-primary' : 'btn-outline' }}">
                                        {{ $p->nilai === null ? 'Beri Nilai' : 'Edit Nilai' }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">Belum ada mahasiswa yang mengumpulkan tugas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Belum Kumpul -->
    @if($belumMengumpulkan->count() > 0)
        <div class="card bg-gray-50 border border-gray-200">
            <h2 class="text-md font-bold mb-4 px-1 text-gray-700">Belum Mengumpulkan ({{ $belumMengumpulkan->count() }})</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($belumMengumpulkan as $mhs)
                    <span class="px-3 py-1 bg-white border border-gray-300 rounded-full text-xs text-gray-600">
                        {{ $mhs->name }} ({{ $mhs->nim ?? '-' }})
                    </span>
                @endforeach
            </div>
        </div>
    @endif
</div>
