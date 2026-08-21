<div class="max-w-5xl mx-auto space-y-6 pb-12">
    <div class="flex items-center justify-between mb-2">
        <div>
            <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" wire:navigate class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm mb-2 inline-block transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Kelas
            </a>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Edit Pengaturan Kelas</h1>
            <p class="text-[var(--text-secondary)] text-sm mt-1">Perbarui jadwal, deskripsi, atau proporsi nilai kelas {{ $kelas->nama_kelas }}.</p>
        </div>
    </div>

    <div class="space-y-6" wire:keydown.enter="simpan">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Kolom Utama --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Informasi Dasar --}}
                <div class="card p-6">
                    <h2 class="text-lg font-bold text-[var(--text-primary)] border-b border-[var(--border)] pb-3 mb-4">
                        Informasi Dasar
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Pilih Mata Kuliah <span class="text-red-500">*</span></label>
                            <select wire:model="mata_kuliah_id" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2.5 text-[var(--text-primary)] disabled:opacity-50" disabled>
                                @foreach($daftarMk as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->kode }} - {{ $mk->nama }} ({{ $mk->sks }} SKS)</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-[var(--text-muted)] mt-1">Mata kuliah tidak bisa diubah setelah kelas dibuat.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Nama Kelas <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="nama_kelas" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2.5 text-[var(--text-primary)]">
                                @error('nama_kelas') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Semester <span class="text-red-500">*</span></label>
                                <select wire:model="semester_id" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2.5 text-[var(--text-primary)]">
                                    @foreach($daftarSemester as $sem)
                                        <option value="{{ $sem->id }}">{{ $sem->nama }} {{ $sem->is_aktif ? '(Aktif)' : '' }}</option>
                                    @endforeach
                                </select>
                                @error('semester_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Deskripsi Tambahan / Pengumuman Awal</label>
                            <textarea wire:model="deskripsi" rows="3" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2.5 text-[var(--text-primary)]"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Pengaturan Nilai & Absensi --}}
                <div class="card p-6">
                    <h2 class="text-lg font-bold text-[var(--text-primary)] border-b border-[var(--border)] pb-3 mb-4">
                        Proporsi Nilai & Kehadiran
                    </h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase">Tugas (%)</label>
                            <input type="number" wire:model.live="bobot_tugas" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-center text-[var(--text-primary)]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase">Kuis (%)</label>
                            <input type="number" wire:model.live="bobot_kuis" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-center text-[var(--text-primary)]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase">UTS (%)</label>
                            <input type="number" wire:model.live="bobot_uts" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-center text-[var(--text-primary)]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase">UAS (%)</label>
                            <input type="number" wire:model.live="bobot_uas" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-center text-[var(--text-primary)]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase">Absen (%)</label>
                            <input type="number" wire:model.live="bobot_kehadiran" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-center text-[var(--text-primary)]">
                        </div>
                    </div>
                    
                    @php $total = (int)$bobot_tugas + (int)$bobot_kuis + (int)$bobot_uts + (int)$bobot_uas + (int)$bobot_kehadiran; @endphp
                    <div class="flex items-center justify-between p-3 rounded-lg {{ $total === 100 ? 'bg-green-500/10 border-green-500/30' : 'bg-red-500/10 border-red-500/30' }} border mb-6">
                        <span class="font-bold {{ $total === 100 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">Total Persentase:</span>
                        <span class="font-black text-xl {{ $total === 100 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $total }}%</span>
                    </div>
                    @error('bobot_tugas') <span class="text-red-500 text-xs -mt-4 mb-4 block">{{ $message }}</span> @enderror

                    <div>
                        <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Batas Minimal Kehadiran (%) <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="batas_kehadiran" class="w-32 bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-[var(--text-primary)] text-center">
                    </div>
                </div>

            </div>

            {{-- Kolom Sidebar Kanan --}}
            <div class="space-y-6">
                
                {{-- Jadwal & Lokasi --}}
                <div class="card p-6">
                    <h2 class="text-lg font-bold text-[var(--text-primary)] border-b border-[var(--border)] pb-3 mb-4">
                        Jadwal & Lokasi
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Hari</label>
                            <select wire:model="hari_kuliah" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-[var(--text-primary)]">
                                <option value="">-- Pilih --</option>
                                <option value="senin">Senin</option>
                                <option value="selasa">Selasa</option>
                                <option value="rabu">Rabu</option>
                                <option value="kamis">Kamis</option>
                                <option value="jumat">Jumat</option>
                                <option value="sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Mulai</label>
                                <input type="time" wire:model="jam_mulai" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-[var(--text-primary)]">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Selesai</label>
                                <input type="time" wire:model="jam_selesai" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-[var(--text-primary)]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Ruangan / Tautan Kelas</label>
                            <input type="text" wire:model="ruangan" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-[var(--text-primary)]">
                        </div>
                    </div>
                </div>

                {{-- Status & Akses --}}
                <div class="card p-6">
                    <h2 class="text-lg font-bold text-[var(--text-primary)] border-b border-[var(--border)] pb-3 mb-4">
                        Pengaturan Akses
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Mode Belajar (Materi)</label>
                            <select wire:model="mode_materi" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-[var(--text-primary)]">
                                <option value="semua">Bebas Akses (Semua)</option>
                                <option value="bertahap">Bertahap (Sekuensial)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Status Kelas</label>
                            <select wire:model="status" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-[var(--text-primary)]">
                                <option value="aktif">Aktif (Berjalan)</option>
                                <option value="selesai">Selesai (Arsip)</option>
                                <option value="arsip">Arsip (Disembunyikan)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Thumbnail Kelas</label>
                            
                            @if($thumbnail_lama && !$thumbnail)
                                <div class="mb-3 relative group">
                                    <img src="{{ asset('storage/'.$thumbnail_lama) }}" class="rounded-lg object-cover h-32 w-full border border-[var(--border)]">
                                    <button type="button" wire:click="hapusThumbnail" class="absolute top-2 right-2 bg-red-500 text-white w-8 h-8 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-lg">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif

                            <input type="file" wire:model="thumbnail" class="w-full text-sm text-[var(--text-secondary)] file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-[var(--teal-dim)] file:text-[var(--teal)] hover:file:bg-[var(--teal)] hover:file:text-white transition">
                            <div wire:loading wire:target="thumbnail" class="text-xs text-[var(--teal)] mt-1">Mengunggah...</div>
                            
                            @if ($thumbnail)
                                <img src="{{ $thumbnail->temporaryUrl() }}" class="mt-2 rounded-lg object-cover h-32 w-full border border-[var(--border)]">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" class="btn btn-outline" style="border-color:var(--border); color:var(--text-primary);">Batal</a>
            <button type="button" wire:click="simpan" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="simpan"><i class="fas fa-save mr-2"></i> Perbarui Kelas</span>
                <span wire:loading wire:target="simpan"><i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...</span>
            </button>
        </div>
    </div>
</div>
