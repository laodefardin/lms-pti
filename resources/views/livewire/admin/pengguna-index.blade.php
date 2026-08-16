<div class="fade-in">
    <div class="topbar flex justify-between items-center mb-6">
        <h1 class="section-title">Manajemen Pengguna</h1>
    </div>

    @if (session()->has('message'))
        <div class="badge badge-green mb-4 p-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="card mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
            <div class="flex space-x-2">
                <button wire:click="setRole('mahasiswa')" class="btn {{ $role === 'mahasiswa' ? 'btn-primary' : 'btn-outline' }}">Mahasiswa</button>
                <button wire:click="setRole('dosen')" class="btn {{ $role === 'dosen' ? 'btn-primary' : 'btn-outline' }}">Dosen</button>
            </div>
            
            <div class="flex space-x-2 items-center">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari pengguna..." class="form-input text-sm w-64">
                <button wire:click="create" class="btn btn-primary text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah
                </button>
                
                <div class="flex items-center space-x-2 bg-gray-100 p-1 rounded">
                    <input type="file" wire:model="fileUpload" class="text-sm form-input p-1" style="width: 150px">
                    <button wire:click="importUsers" class="btn btn-outline text-sm py-1 px-2">Import</button>
                </div>
            </div>
        </div>

        <div class="table-wrap">
            <table class="lms-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Profil</th>
                        <th class="text-left">Nama</th>
                        <th class="text-left">{{ $role === 'mahasiswa' ? 'NIM' : 'NIDN' }}</th>
                        <th class="text-left">Email</th>
                        @if($role === 'mahasiswa')
                            <th class="text-left">Angkatan</th>
                        @endif
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b" style="border-color: var(--border);">
                            <td class="py-3">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold overflow-hidden" style="background: var(--teal-dim); color: var(--teal-dark);">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($user->name, 0, 2) }}
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 font-medium">{{ $user->name }}</td>
                            <td class="py-3">{{ $user->nim ?? $user->nidn ?? '-' }}</td>
                            <td class="py-3 text-gray-500">{{ $user->email }}</td>
                            @if($role === 'mahasiswa')
                                <td class="py-3">{{ $user->angkatan ?? '-' }}</td>
                            @endif
                            <td class="py-3 text-right">
                                <button wire:click="edit({{ $user->id }})" class="text-blue-500 hover:text-blue-700 mr-2 text-sm font-medium">Edit</button>
                                <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Yakin ingin menghapus pengguna ini?" class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $role === 'mahasiswa' ? 6 : 5 }}" class="text-center py-6 text-gray-500">Tidak ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
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
                                {{ $editId ? 'Edit' : 'Tambah' }} Pengguna ({{ ucfirst($role) }})
                            </h3>
                            <div class="mt-4 w-full">
                                <form wire:submit.prevent="saveUser">
                                    <div class="mb-4">
                                        <label class="form-label block mb-1">Nama Lengkap</label>
                                        <input type="text" wire:model="name" class="form-input w-full">
                                        @error('name') <span class="form-error text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label block mb-1">Email</label>
                                        <input type="email" wire:model="email" class="form-input w-full">
                                        @error('email') <span class="form-error text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label block mb-1">{{ $role === 'mahasiswa' ? 'NIM' : 'NIDN' }}</label>
                                        <input type="text" wire:model="nim" class="form-input w-full">
                                        @error('nim') <span class="form-error text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    @if($role === 'mahasiswa')
                                    <div class="mb-4">
                                        <label class="form-label block mb-1">Angkatan</label>
                                        <input type="text" wire:model="angkatan" class="form-input w-full">
                                        @error('angkatan') <span class="form-error text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse" style="border-top: 1px solid var(--border);">
                    <button type="button" wire:click="saveUser" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm btn btn-primary">
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
