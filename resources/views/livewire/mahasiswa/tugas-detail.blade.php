<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="text-sm mb-4" style="color: var(--text-muted);">
        <a href="{{ route('mahasiswa.tugas') ?? '#' }}" class="hover:underline" style="color: var(--teal);">Tugas</a> > {{ $tugas->judul }}
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Task Description (LEFT) -->
        <div class="lg:col-span-2">
            <div class="card p-6" style="background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 0.5rem; box-shadow: var(--shadow-card);">
                <div class="mb-4">
                    <span class="badge badge-teal" style="background-color: var(--teal-light); color: var(--teal-dark); padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem;">{{ $tugas->kelas->mataKuliah->nama_mk }}</span>
                </div>
                <h1 class="text-2xl font-bold mb-4" style="color: var(--text-primary);">{{ $tugas->judul }}</h1>
                
                <div class="flex items-center text-sm mb-6 p-4 rounded-md" style="background-color: #f8fafc; border: 1px solid var(--border);">
                    <div class="flex-1">
                        <p style="color: var(--text-muted);">Batas Waktu</p>
                        <p class="font-semibold" style="color: var(--text-primary);">{{ \Carbon\Carbon::parse($tugas->batas_waktu)->format('d M Y, H:i') }}</p>
                    </div>
                    @if($tugas->file_soal)
                    <div>
                        <a href="{{ Storage::url($tugas->file_soal) }}" target="_blank" class="btn btn-outline btn-sm rounded-md px-3 py-1" style="border: 1px solid var(--teal); color: var(--teal); text-decoration: none;">Download Soal</a>
                    </div>
                    @endif
                </div>

                <div class="prose max-w-none" style="color: var(--text-secondary);">
                    {!! $tugas->deskripsi !!}
                </div>
            </div>
        </div>

        <!-- Submission Form (RIGHT) -->
        <div class="lg:col-span-1">
            <div class="card p-6" style="background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 0.5rem; box-shadow: var(--shadow-card);">
                <h2 class="text-xl font-bold mb-4 border-b pb-2" style="color: var(--text-primary); border-color: var(--border);">Status Pengumpulan</h2>

                @if($showSuccess)
                    <div class="mb-4 p-3 rounded-md text-sm" style="background-color: #dcfce7; color: var(--success); border: 1px solid #bbf7d0;">
                        Tugas berhasil dikumpulkan!
                    </div>
                @endif

                @php
                    $isPassed = \Carbon\Carbon::parse($tugas->batas_waktu)->isPast();
                @endphp

                @if($pengumpulan)
                    <div class="mb-4">
                        <p class="text-sm" style="color: var(--text-muted);">Status</p>
                        @if($pengumpulan->status === 'dinilai')
                            <span class="badge badge-green" style="background-color: #dcfce7; color: var(--success); padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Dinilai ({{ $pengumpulan->nilai }}/100)</span>
                        @else
                            <span class="badge badge-teal" style="background-color: var(--teal-light); color: var(--teal-dark); padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Dikumpulkan</span>
                        @endif
                    </div>
                    <div class="mb-4">
                        <p class="text-sm" style="color: var(--text-muted);">Waktu Pengumpulan</p>
                        <p class="font-medium" style="color: var(--text-primary);">{{ \Carbon\Carbon::parse($pengumpulan->waktu_pengumpulan)->format('d M Y, H:i') }}</p>
                    </div>
                    
                    @if($pengumpulan->file_path)
                        <div class="mb-4">
                            <p class="text-sm" style="color: var(--text-muted);">File Terlampir</p>
                            <a href="{{ Storage::url($pengumpulan->file_path) }}" target="_blank" class="text-sm hover:underline" style="color: var(--teal);">Lihat File</a>
                        </div>
                    @endif

                    @if($pengumpulan->link_url)
                        <div class="mb-4">
                            <p class="text-sm" style="color: var(--text-muted);">Link Tautan</p>
                            <a href="{{ $pengumpulan->link_url }}" target="_blank" class="text-sm hover:underline" style="color: var(--teal);">{{ $pengumpulan->link_url }}</a>
                        </div>
                    @endif
                    
                    @if($pengumpulan->catatan_dosen)
                        <div class="mt-6 p-4 rounded-md" style="background-color: #f0fdfa; border: 1px solid var(--teal-light);">
                            <p class="text-sm font-semibold mb-1" style="color: var(--teal-dark);">Feedback Dosen:</p>
                            <p class="text-sm" style="color: var(--text-secondary);">{{ $pengumpulan->catatan_dosen }}</p>
                        </div>
                    @endif
                    
                    @if($pengumpulan->status !== 'dinilai' && !$isPassed)
                        <hr class="my-6" style="border-color: var(--border);">
                        <p class="text-sm mb-3 font-semibold" style="color: var(--text-primary);">Update Pengumpulan</p>
                    @endif
                @endif

                @if(!$pengumpulan && $isPassed)
                    <div class="p-4 rounded-md text-center" style="background-color: #fee2e2; border: 1px solid #fecaca;">
                        <p class="font-semibold" style="color: var(--danger);">Batas Waktu Telah Lewat</p>
                        <p class="text-sm mt-1" style="color: var(--danger);">Anda tidak dapat lagi mengumpulkan tugas ini.</p>
                    </div>
                @elseif(!$pengumpulan || ($pengumpulan && $pengumpulan->status !== 'dinilai' && !$isPassed))
                    <form wire:submit.prevent="kumpulkan" class="space-y-4">
                        @error('general') <span class="text-xs" style="color: var(--danger);">{{ $message }}</span> @enderror
                        
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Upload File</label>
                            <input type="file" wire:model="fileUpload" class="w-full text-sm p-2 rounded-md" style="border: 1px solid var(--border); background-color: var(--bg-main);">
                            @error('fileUpload') <span class="text-xs" style="color: var(--danger);">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Tautan (Link)</label>
                            <input type="url" wire:model="linkUrl" placeholder="https://..." class="w-full text-sm p-2 rounded-md" style="border: 1px solid var(--border); background-color: var(--bg-main); color: var(--text-primary);">
                            @error('linkUrl') <span class="text-xs" style="color: var(--danger);">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Catatan</label>
                            <textarea wire:model="catatan" rows="3" class="w-full text-sm p-2 rounded-md" style="border: 1px solid var(--border); background-color: var(--bg-main); color: var(--text-primary);"></textarea>
                            @error('catatan') <span class="text-xs" style="color: var(--danger);">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-full w-full py-2 rounded-md text-white font-medium flex justify-center items-center" style="background-color: var(--teal); transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                            <span wire:loading.remove wire:target="kumpulkan">Kumpulkan Tugas</span>
                            <span wire:loading wire:target="kumpulkan">Mengirim...</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
