<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Manajemen Semester 📅</h1>
            <p class="section-sub">Kelola data semester dan periode akademik.</p>
        </div>
        <button wire:click="openCreate" class="btn btn-primary">
            + Tambah Semester
        </button>
    </div>

    <div class="card mb-6">
        <div class="table-wrap">
            <table class="lms-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Nama</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Tahun Ajaran</th>
                        <th class="text-center">Mulai</th>
                        <th class="text-center">Selesai</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($semesters as $index => $smt)
                        <tr>
                            <td>{{ $semesters->firstItem() + $index }}</td>
                            <td class="font-semibold">{{ $smt->nama }}</td>
                            <td class="text-center capitalize">{{ $smt->tipe }}</td>
                            <td class="text-center">{{ $smt->tahun_akademik }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($smt->tanggal_mulai)->format('d M Y') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($smt->tanggal_selesai)->format('d M Y') }}</td>
                            <td class="text-center">
                                @if($smt->is_aktif)
                                    <span class="badge" style="background-color: var(--warning); color: #fff;">AKTIF</span>
                                @else
                                    <span class="badge badge-gray">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    @if(!$smt->is_aktif)
                                        <button wire:click="setAktif({{ $smt->id }})" class="btn btn-sm btn-outline">Set Aktif</button>
                                    @endif
                                    <button wire:click="openEdit({{ $smt->id }})" class="btn btn-sm btn-outline">Edit</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">Belum ada data semester.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $semesters->links(data: ['scrollTo' => false]) }}
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
            <h2 class="section-title mb-4">{{ $editId ? 'Edit Semester' : 'Tambah Semester' }}</h2>
            
            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Nama Semester (mis: Ganjil 2024/2025)</label>
                        <input wire:model="nama" type="text" class="form-input w-full" required>
                        @error('nama') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Tahun Ajaran</label>
                            <input wire:model="tahunAjaran" type="text" placeholder="2024/2025" class="form-input w-full" required>
                            @error('tahunAjaran') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Tipe</label>
                            <select wire:model="tipe" class="form-input w-full" required>
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                            @error('tipe') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Mulai</label>
                            <input wire:model="mulaiAt" type="date" class="form-input w-full" required>
                            @error('mulaiAt') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Selesai</label>
                            <input wire:model="selesaiAt" type="date" class="form-input w-full" required>
                            @error('selesaiAt') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model="isAktif" type="checkbox" class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                            <span class="text-sm font-medium" style="color: var(--text-primary)">Set sebagai semester aktif</span>
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
