<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Manajemen Dosen 👨‍🏫</h1>
            <p class="section-sub">Kelola data dosen, status aktif, dan profil.</p>
        </div>
        <button wire:click="openCreate" class="btn btn-primary">
            + Tambah Dosen
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
        <div class="mb-4">
            <input wire:model.live.debounce.300ms="search" type="text" class="form-input w-full md:w-1/2" placeholder="Cari nama, NIDN, atau email...">
        </div>

        <div class="table-wrap">
            <table class="lms-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">NIDN</th>
                        <th class="text-left">Nama</th>
                        <th class="text-left">Email</th>
                        <th class="text-center">Kelas Aktif</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dosen as $index => $dsn)
                        <tr>
                            <td>{{ $dosen->firstItem() + $index }}</td>
                            <td class="font-semibold">{{ $dsn->nim_nidn }}</td>
                            <td>{{ $dsn->name }}</td>
                            <td>{{ $dsn->email }}</td>
                            <td class="text-center">
                                <span class="badge badge-purple">{{ $dsn->kelas_yang_diampu_count }} Kelas</span>
                            </td>
                            <td class="text-center">
                                <button wire:click="toggleActive({{ $dsn->id }})" class="badge {{ $dsn->is_active ? 'badge-green' : 'badge-red' }} hover:opacity-80">
                                    {{ $dsn->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="openEdit({{ $dsn->id }})" class="btn btn-sm btn-outline">Edit</button>
                                    @if($dsn->is_active)
                                        <button wire:click="delete({{ $dsn->id }})" class="btn btn-sm btn-danger">Nonaktifkan</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">Belum ada data dosen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $dosen->links(data: ['scrollTo' => false]) }}
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
            <h2 class="section-title mb-4">{{ $editId ? 'Edit Dosen' : 'Tambah Dosen' }}</h2>
            
            <form wire:submit.prevent="save">
                <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">
                    <div>
                        <label class="form-label">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="form-input w-full" required>
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">NIDN</label>
                            <input wire:model="nidn" type="text" class="form-input w-full" required>
                            @error('nidn') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">No HP</label>
                            <input wire:model="no_hp" type="text" class="form-input w-full">
                            @error('no_hp') <span class="form-error">{{ $message }}</span> @enderror
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
                        <label class="form-label">Bio Singkat</label>
                        <textarea wire:model="bio" class="form-input w-full" rows="3"></textarea>
                        @error('bio') <span class="form-error">{{ $message }}</span> @enderror
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
