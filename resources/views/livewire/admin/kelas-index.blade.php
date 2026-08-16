<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Manajemen Kelas 🏛️</h1>
            <p class="section-sub">Kelola kelas, jadwal, dan dosen pengampu.</p>
        </div>
        <button wire:click="openCreate" class="btn btn-primary">
            + Tambah Kelas
        </button>
    </div>

    <div class="card mb-6">
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="flex-1">
                <input wire:model.live.debounce.300ms="search" type="text" class="form-input w-full" placeholder="Cari nama kelas atau mata kuliah...">
            </div>
            <div class="w-full md:w-64">
                <select wire:model.live="semesterId" class="form-input w-full">
                    <option value="">Semua Semester</option>
                    @foreach($semesterList as $smt)
                        <option value="{{ $smt->id }}">{{ $smt->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-wrap">
            <table class="lms-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Kelas</th>
                        <th class="text-left">Mata Kuliah</th>
                        <th class="text-left">Dosen</th>
                        <th class="text-center">Mahasiswa</th>
                        <th class="text-left">Hari/Jam</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelasList as $index => $kelas)
                        <tr>
                            <td>{{ $kelasList->firstItem() + $index }}</td>
                            <td class="font-semibold">{{ $kelas->nama_kelas }}</td>
                            <td>
                                <div>{{ $kelas->mataKuliah->nama ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $kelas->mataKuliah->kode ?? '-' }}</div>
                            </td>
                            <td>{{ $kelas->dosen->name ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge badge-purple">{{ $kelas->mahasiswa_count }}</span>
                            </td>
                            <td>
                                @if($kelas->hari_kuliah)
                                    <div class="capitalize">{{ $kelas->hari_kuliah }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') }}</div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($kelas->status === 'aktif')
                                    <span class="badge badge-green">Aktif</span>
                                @elseif($kelas->status === 'arsip')
                                    <span class="badge badge-gray">Arsip</span>
                                @else
                                    <span class="badge badge-orange capitalize">{{ $kelas->status }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="openEdit({{ $kelas->id }})" class="btn btn-sm btn-outline">Edit</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">Belum ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $kelasList->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- Modal Form -->
    <div x-data="{ open: @entangle('showModal') }" 
         x-show="open" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="card w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col" @click.outside="open = false" style="background-color: var(--bg-card)">
            <h2 class="section-title mb-4">{{ $editId ? 'Edit Kelas' : 'Tambah Kelas' }}</h2>
            
            <form wire:submit.prevent="save" class="flex-1 overflow-y-auto pr-2">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Mata Kuliah</label>
                            <select wire:model="mataKuliahId" class="form-input w-full" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach($mataKuliahList as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->kode }} - {{ $mk->nama }}</option>
                                @endforeach
                            </select>
                            @error('mataKuliahId') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Dosen Pengampu</label>
                            <select wire:model="dosenId" class="form-input w-full" required>
                                <option value="">Pilih Dosen</option>
                                @foreach($dosenList as $dsn)
                                    <option value="{{ $dsn->id }}">{{ $dsn->name }}</option>
                                @endforeach
                            </select>
                            @error('dosenId') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Semester</label>
                            <select wire:model="semesterId_form" class="form-input w-full" required>
                                <option value="">Pilih Semester</option>
                                @foreach($semesterList as $smt)
                                    <option value="{{ $smt->id }}">{{ $smt->nama }}</option>
                                @endforeach
                            </select>
                            @error('semesterId_form') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Nama Kelas (mis: A, B, C)</label>
                            <input wire:model="namaKelas" type="text" class="form-input w-full" required>
                            @error('namaKelas') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Hari Kuliah</label>
                            <select wire:model="hariKuliah" class="form-input w-full">
                                <option value="">Pilih Hari</option>
                                <option value="senin">Senin</option>
                                <option value="selasa">Selasa</option>
                                <option value="rabu">Rabu</option>
                                <option value="kamis">Kamis</option>
                                <option value="jumat">Jumat</option>
                                <option value="sabtu">Sabtu</option>
                            </select>
                            @error('hariKuliah') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Jam Mulai</label>
                            <input wire:model="jamMulai" type="time" class="form-input w-full">
                            @error('jamMulai') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Jam Selesai</label>
                            <input wire:model="jamSelesai" type="time" class="form-input w-full">
                            @error('jamSelesai') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Ruangan</label>
                            <input wire:model="ruangan" type="text" class="form-input w-full" placeholder="Mis: Lab Komputer 1">
                            @error('ruangan') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Batas Kehadiran (%)</label>
                            <input wire:model="batasKehadiran" type="number" min="0" max="100" class="form-input w-full" required>
                            @error('batasKehadiran') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6 pt-4 border-t" style="border-color: var(--border)">
                    <button type="button" wire:click="$set('showModal', false)" class="btn btn-ghost">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
