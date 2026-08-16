<div class="fade-in">
    <div class="topbar flex justify-between items-center mb-6">
        <h1 class="section-title">Manajemen Kelas</h1>
    </div>

    @if (session()->has('message'))
        <div class="badge badge-green mb-4 p-3 rounded">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="badge badge-red mb-4 p-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
            <div class="flex items-center space-x-2">
                <label class="font-medium text-gray-700" style="color: var(--text-primary)">Pilih Semester:</label>
                <select wire:model.live="semester_id" class="form-input w-48 text-sm">
                    <option value="">-- Pilih Semester --</option>
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->nama_semester }} {{ $sem->is_active ? '(Aktif)' : '' }}</option>
                    @endforeach
                </select>
            </div>
            
            <button wire:click="create" class="btn btn-primary text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kelas
            </button>
        </div>

        <div class="table-wrap">
            <table class="lms-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Mata Kuliah</th>
                        <th class="text-left">Dosen Pengampu</th>
                        <th class="text-left">Nama Kelas</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Jml Mahasiswa</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelases as $kelas)
                        <tr class="border-b" style="border-color: var(--border);">
                            <td class="py-3 font-medium">{{ $kelas->mataKuliah->nama_mata_kuliah ?? '-' }}</td>
                            <td class="py-3">{{ $kelas->dosen->name ?? '-' }}</td>
                            <td class="py-3">
                                <span class="badge badge-teal">{{ $kelas->nama_kelas }}</span>
                            </td>
                            <td class="py-3 text-center">{{ $kelas->mataKuliah->sks ?? '-' }}</td>
                            <td class="py-3 text-center">
                                <span class="text-gray-600 bg-gray-100 px-2 py-1 rounded text-xs">{{ $kelas->mahasiswa_count }} / {{ $kelas->kuota ?? '-' }}</span>
                            </td>
                            <td class="py-3 text-right">
                                <button wire:click="edit({{ $kelas->id }})" class="text-blue-500 hover:text-blue-700 mr-2 text-sm font-medium">Edit</button>
                                <button wire:click="deleteKelas({{ $kelas->id }})" wire:confirm="Yakin ingin menghapus kelas ini?" class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">Tidak ada data kelas untuk semester terpilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" style="background: var(--bg-card);">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" style="color: var(--text-primary);" id="modal-title">
                                {{ $editId ? 'Edit' : 'Tambah' }} Kelas
                            </h3>
                            <div class="mt-4 w-full">
                                <form wire:submit.prevent="saveKelas">
                                    <div class="mb-4">
                                        <label class="form-label block mb-1">Mata Kuliah</label>
                                        <select wire:model="mata_kuliah_id" class="form-input w-full">
                                            <option value="">-- Pilih Mata Kuliah --</option>
                                            @foreach($mataKuliahs as $mk)
                                                <option value="{{ $mk->id }}">{{ $mk->kode_mata_kuliah }} - {{ $mk->nama_mata_kuliah }}</option>
                                            @endforeach
                                        </select>
                                        @error('mata_kuliah_id') <span class="form-error text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label block mb-1">Dosen Pengampu</label>
                                        <select wire:model="dosen_id" class="form-input w-full">
                                            <option value="">-- Pilih Dosen --</option>
                                            @foreach($dosens as $dosen)
                                                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('dosen_id') <span class="form-error text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label block mb-1">Nama Kelas</label>
                                        <input type="text" wire:model="nama_kelas" placeholder="Contoh: A, B, Kelas Internasional" class="form-input w-full">
                                        @error('nama_kelas') <span class="form-error text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse" style="border-top: 1px solid var(--border);">
                    <button type="button" wire:click="saveKelas" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm btn btn-primary">
                        Simpan
                    </button>
                    <button type="button" wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm btn btn-outline">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
