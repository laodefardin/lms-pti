<div class="max-w-5xl mx-auto space-y-6 pb-12">
    <div class="flex items-center justify-between mb-2">
        <div>
            <a href="{{ route('dosen.matakuliah.index') }}" wire:navigate class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm mb-2 inline-block transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Kelas
            </a>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Buka Kelas Baru</h1>
            <p class="text-[var(--text-secondary)] text-sm mt-1">Konfigurasi pengaturan kelas, jadwal, dan proporsi nilai.</p>
        </div>
    </div>

    <div class="space-y-6" wire:keydown.enter="simpan">
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-500 p-4 rounded-lg mb-6">
                <div class="font-bold mb-2"><i class="fas fa-exclamation-circle mr-2"></i> Mohon perbaiki kesalahan berikut:</div>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
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
                            <select wire:model="mata_kuliah_id" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2.5 text-[var(--text-primary)]">
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($daftarMk as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->kode }} - {{ $mk->nama }} ({{ $mk->sks }} SKS)</option>
                                @endforeach
                            </select>
                            @error('mata_kuliah_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Nama Kelas <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="nama_kelas" placeholder="Contoh: PTI-A, TI-B, Khusus" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2.5 text-[var(--text-primary)]">
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
                            <textarea wire:model="deskripsi" rows="3" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2.5 text-[var(--text-primary)]" placeholder="Tuliskan aturan kelas atau deskripsi singkat..."></textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Pengaturan Nilai & Absensi --}}
                <div class="card p-6"
                    x-data="{
                        tugas: {{ (int)$bobot_tugas }},
                        kuis:  {{ (int)$bobot_kuis }},
                        uts:   {{ (int)$bobot_uts }},
                        uas:   {{ (int)$bobot_uas }},
                        hadir: {{ (int)$bobot_kehadiran }},
                        get total() { return this.tugas + this.kuis + this.uts + this.uas + this.hadir; },
                        get isValid() { return this.total === 100; }
                    }">
                    <h2 class="text-lg font-bold text-[var(--text-primary)] border-b border-[var(--border)] pb-3 mb-4">
                        Proporsi Nilai & Kehadiran
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase">Tugas (%)</label>
                            <input type="number" wire:model.live="bobot_tugas"
                                x-model.number="tugas"
                                min="0" max="100"
                                class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-center text-[var(--text-primary)] font-bold text-lg"
                                :class="isValid ? 'border-green-500/50' : 'border-orange-500/50'">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase">Kuis (%)</label>
                            <input type="number" wire:model.live="bobot_kuis"
                                x-model.number="kuis"
                                min="0" max="100"
                                class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-center text-[var(--text-primary)] font-bold text-lg"
                                :class="isValid ? 'border-green-500/50' : 'border-orange-500/50'">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase">UTS (%)</label>
                            <input type="number" wire:model.live="bobot_uts"
                                x-model.number="uts"
                                min="0" max="100"
                                class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-center text-[var(--text-primary)] font-bold text-lg"
                                :class="isValid ? 'border-green-500/50' : 'border-orange-500/50'">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase">UAS (%)</label>
                            <input type="number" wire:model.live="bobot_uas"
                                x-model.number="uas"
                                min="0" max="100"
                                class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-center text-[var(--text-primary)] font-bold text-lg"
                                :class="isValid ? 'border-green-500/50' : 'border-orange-500/50'">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase">Absen (%)</label>
                            <input type="number" wire:model.live="bobot_kehadiran"
                                x-model.number="hadir"
                                min="0" max="100"
                                class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-center text-[var(--text-primary)] font-bold text-lg"
                                :class="isValid ? 'border-green-500/50' : 'border-orange-500/50'">
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mb-2">
                        <div class="w-full rounded-full h-3 overflow-hidden" style="background:var(--input-bg);">
                            <div class="h-3 rounded-full transition-all duration-300"
                                :style="`width: ${Math.min(total, 100)}%; background: ${isValid ? '#22c55e' : total > 100 ? '#ef4444' : '#f59e0b'};`">
                            </div>
                        </div>
                    </div>

                    {{-- Total Badge --}}
                    <div class="flex items-center justify-between p-3 rounded-lg border transition-all duration-300 mb-4"
                        :class="isValid
                            ? 'bg-green-500/10 border-green-500/30'
                            : total > 100
                                ? 'bg-red-500/10 border-red-500/30'
                                : 'bg-orange-500/10 border-orange-500/30'">
                        <div class="flex items-center gap-2">
                            <i class="fas" :class="isValid ? 'fa-check-circle text-green-500' : total > 100 ? 'fa-times-circle text-red-500' : 'fa-exclamation-circle text-orange-500'"></i>
                            <span class="font-bold text-sm" :class="isValid ? 'text-green-600 dark:text-green-400' : total > 100 ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400'">
                                <span x-show="isValid">Total sudah tepat 100%!</span>
                                <span x-show="!isValid && total < 100" x-cloak>Kurang <span x-text="100 - total"></span>% lagi untuk mencapai 100%</span>
                                <span x-show="total > 100" x-cloak>Kelebihan <span x-text="total - 100"></span>% — kurangi salah satu bobot</span>
                            </span>
                        </div>
                        <span class="font-black text-2xl transition-colors duration-300"
                            :class="isValid ? 'text-green-600 dark:text-green-400' : total > 100 ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400'"
                            x-text="total + '%'">
                        </span>
                    </div>

                    @error('bobot_tugas') <span class="text-red-500 text-xs mb-4 block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror

                    <div>
                        <label class="block text-sm font-bold text-[var(--text-primary)] mb-1">Batas Minimal Kehadiran (%) <span class="text-red-500">*</span></label>
                        <p class="text-xs text-[var(--text-muted)] mb-2">Syarat kehadiran minimal untuk mengikuti ujian akhir.</p>
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
                            <input type="text" wire:model="ruangan" placeholder="Contoh: Lab Komputer 1 / Zoom Link" class="w-full bg-[var(--input-bg)] border border-[var(--border)] rounded p-2 text-[var(--text-primary)]">
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
                            <p class="text-[0.7rem] text-[var(--text-muted)] mt-1">Bertahap mewajibkan mahasiswa membaca materi sebelumnya sebelum membuka materi baru.</p>
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
            <a href="{{ route('dosen.matakuliah.index') }}" class="btn btn-outline" style="border-color:var(--border); color:var(--text-primary);">Batal</a>
            <button type="button" wire:click="simpan" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="simpan"><i class="fas fa-save mr-2"></i> Simpan Kelas</span>
                <span wire:loading wire:target="simpan"><i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...</span>
            </button>
        </div>
    </div>
</div>
