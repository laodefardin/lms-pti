<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Manajemen Mata Kuliah 📚</h1>
            <p class="section-sub">Kelola data mata kuliah, SKS, dan semester.</p>
        </div>
        <button wire:click="openCreate" class="btn btn-primary">
            + Tambah MK
        </button>
    </div>

    <div class="card mb-6">
        <div class="mb-4">
            <input wire:model.live.debounce.300ms="search" type="text" class="form-input w-full md:w-1/2" placeholder="Cari nama atau kode mata kuliah...">
        </div>

        <div class="table-wrap">
            <table class="lms-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Kode</th>
                        <th class="text-left">Nama</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Semester</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mataKuliah as $index => $mk)
                        <tr>
                            <td>{{ $mataKuliah->firstItem() + $index }}</td>
                            <td class="font-semibold">{{ $mk->kode }}</td>
                            <td>{{ $mk->nama }}</td>
                            <td class="text-center">{{ $mk->sks }}</td>
                            <td class="text-center">{{ $mk->semester ?? '-' }}</td>
                            <td class="text-center">
                                <button wire:click="toggleActive({{ $mk->id }})" class="badge {{ $mk->is_active ? 'badge-green' : 'badge-red' }} hover:opacity-80">
                                    {{ $mk->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="openEdit({{ $mk->id }})" class="btn btn-sm btn-outline">Edit</button>
                                    @if($mk->is_active)
                                        <button wire:click="delete({{ $mk->id }})" class="btn btn-sm btn-danger">Nonaktifkan</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">Belum ada data mata kuliah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $mataKuliah->links(data: ['scrollTo' => false]) }}
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
        
        <div class="card w-full max-w-lg mx-4" @click.outside="open = false" style="background-color: var(--bg-card)">
            <h2 class="section-title mb-4">{{ $editId ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' }}</h2>
            
            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Kode MK</label>
                            <input wire:model="kode" type="text" class="form-input w-full" required>
                            @error('kode') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Nama MK</label>
                            <input wire:model="nama" type="text" class="form-input w-full" required>
                            @error('nama') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">SKS</label>
                            <select wire:model="sks" class="form-input w-full" required>
                                @for ($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}">{{ $i }} SKS</option>
                                @endfor
                            </select>
                            @error('sks') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Semester</label>
                            <select wire:model="semester" class="form-input w-full" required>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">Semester {{ $i }}</option>
                                @endfor
                            </select>
                            @error('semester') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Deskripsi</label>
                        <textarea wire:model="deskripsi" class="form-input w-full" rows="3"></textarea>
                        @error('deskripsi') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model="isActive" type="checkbox" class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                            <span class="text-sm font-medium" style="color: var(--text-primary)">Mata Kuliah Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" wire:click="$set('showModal', false)" class="btn btn-ghost">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
