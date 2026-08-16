<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Manajemen Mahasiswa 👨‍🎓</h1>
            <p class="section-sub">Kelola data mahasiswa, angkatan, dan status aktif.</p>
        </div>
        <button wire:click="openCreate" class="btn btn-primary">
            + Tambah Mahasiswa
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="card card-teal">
            <h3 class="text-sm font-semibold mb-1" style="color: var(--text-secondary)">Total Aktif</h3>
            <p class="text-2xl font-bold" style="color: var(--teal)">{{ $totalActive }}</p>
        </div>
        <div class="card card-orange">
            <h3 class="text-sm font-semibold mb-1" style="color: var(--text-secondary)">Total Nonaktif</h3>
            <p class="text-2xl font-bold" style="color: var(--warning)">{{ $totalInactive }}</p>
        </div>
    </div>

    <div class="card mb-6">
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="flex-1">
                <input wire:model.live.debounce.300ms="search" type="text" class="form-input w-full" placeholder="Cari nama, NIM, atau email...">
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="angkatan" class="form-input w-full">
                    <option value="">Semua Angkatan</option>
                    @for ($i = 2020; $i <= 2026; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="table-wrap">
            <table class="lms-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">NIM</th>
                        <th class="text-left">Nama</th>
                        <th class="text-left">Email</th>
                        <th class="text-center">Angkatan</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswa as $index => $mhs)
                        <tr>
                            <td>{{ $mahasiswa->firstItem() + $index }}</td>
                            <td class="font-semibold">{{ $mhs->nim_nidn }}</td>
                            <td>{{ $mhs->name }}</td>
                            <td>{{ $mhs->email }}</td>
                            <td class="text-center">{{ $mhs->angkatan ?? '-' }}</td>
                            <td class="text-center">
                                <button wire:click="toggleActive({{ $mhs->id }})" class="badge {{ $mhs->is_active ? 'badge-green' : 'badge-red' }} hover:opacity-80">
                                    {{ $mhs->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="openEdit({{ $mhs->id }})" class="btn btn-sm btn-outline">Edit</button>
                                    @if($mhs->is_active)
                                        <button wire:click="delete({{ $mhs->id }})" class="btn btn-sm btn-danger">Nonaktifkan</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">Belum ada data mahasiswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $mahasiswa->links(data: ['scrollTo' => false]) }}
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
            <h2 class="section-title mb-4">{{ $editId ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' }}</h2>
            
            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="form-input w-full" required>
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">NIM</label>
                            <input wire:model="nim" type="text" class="form-input w-full" required>
                            @error('nim') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Angkatan</label>
                            <select wire:model="angkatan_input" class="form-input w-full">
                                <option value="">Pilih Angkatan</option>
                                @for ($i = 2020; $i <= 2026; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('angkatan_input') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Email</label>
                        <input wire:model="email" type="email" class="form-input w-full" required>
                        @error('email') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="form-label">Password {{ $editId ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                        <input wire:model="password" type="password" class="form-input w-full" {{ $editId ? '' : 'required' }}>
                        @error('password') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="form-label">No HP</label>
                        <input wire:model="no_hp" type="text" class="form-input w-full">
                        @error('no_hp') <span class="form-error">{{ $message }}</span> @enderror
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
