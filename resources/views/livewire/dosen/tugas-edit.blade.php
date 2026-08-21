<div class="w-full px-2 xl:px-4 space-y-5 pb-12 fade-in">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <a href="{{ route('dosen.tugas.index', $kelas) }}"
               class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm inline-flex items-center gap-1 transition mb-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tugas
            </a>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Edit Tugas: {{ $tugas->judul }}</h1>
        </div>
    </div>

    <form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- Kolom Kiri: Detail Tugas --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-5">
                <h2 class="text-lg font-bold text-[var(--text-primary)] mb-5 border-b border-[var(--border)] pb-3">Informasi Dasar</h2>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1.5">Judul Tugas *</label>
                    <input wire:model="judul" type="text" class="form-input w-full" placeholder="Contoh: Tugas 1 Pemrograman Web">
                    @error('judul') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4" wire:ignore>
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1.5">Deskripsi Lengkap *</label>
                    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
                    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
                    <style>
                        trix-toolbar [data-trix-button-group="file-tools"] { display: none; }
                        trix-editor { min-height: 200px !important; background: var(--bg-body); border-color: var(--border); border-radius: 0.5rem; }
                    </style>
                    <input id="deskripsi_tugas" type="hidden" wire:model="deskripsi">
                    <trix-editor input="deskripsi_tugas" class="trix-content w-full" 
                                 x-data x-on:trix-change="$wire.set('deskripsi', $event.target.value)"></trix-editor>
                </div>
                @error('deskripsi') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror

                <div class="mb-2">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1.5">File Soal Tambahan (Opsional)</label>
                    <input wire:model="fileSoal" type="file" class="form-input w-full p-2 text-sm" accept=".pdf,.doc,.docx,.zip">
                    @if($tugas->file_soal)
                        <div class="text-xs text-[var(--teal)] mt-1 flex items-center gap-1">
                            <i class="fas fa-file-pdf"></i> File saat ini: {{ basename($tugas->file_soal) }}
                        </div>
                    @endif
                    <div class="text-xs text-[var(--text-muted)] mt-1">Biarkan kosong jika tidak ingin mengubah file. Maks. 20MB (PDF, DOCX, ZIP)</div>
                    @error('fileSoal') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Pengaturan --}}
        <div class="space-y-6">
            <div class="card p-5">
                <h3 class="font-bold text-[var(--text-primary)] mb-5 border-b border-[var(--border)] pb-3">Pengaturan Tugas</h3>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1.5">Deadline *</label>
                    <input wire:model="deadline" type="datetime-local" class="form-input w-full text-sm">
                    @error('deadline') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1.5">Poin Maksimal</label>
                    <input wire:model="bobotNilai" type="number" min="1" max="100" class="form-input w-full text-sm">
                    @error('bobotNilai') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1.5">Tipe Pengumpulan</label>
                    <select wire:model="tipe" class="form-input w-full text-sm">
                        <option value="file">Hanya File</option>
                        <option value="link">Hanya Link (URL)</option>
                        <option value="file_link">File atau Link</option>
                        <option value="teks">Hanya Teks</option>
                    </select>
                </div>

                @if(in_array($tipe, ['file', 'file_link']))
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1.5">Format File Diizinkan</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['pdf', 'docx', 'zip', 'png', 'jpg', 'xlsx'] as $ext)
                                <label class="flex items-center gap-1.5 text-xs text-[var(--text-primary)] bg-[var(--bg-body)] px-2 py-1 rounded cursor-pointer border border-[var(--border)] transition hover:border-[var(--teal)]">
                                    <input type="checkbox" wire:click="toggleExt('{{ $ext }}')" 
                                        {{ in_array($ext, $allowedExt) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-[var(--teal)] focus:ring-[var(--teal)]">
                                    .{{ $ext }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1.5">Ukuran Maks. File (MB)</label>
                        <input wire:model="maxFileSize" type="number" min="1" max="50" class="form-input w-full text-sm">
                    </div>
                @endif

                <div class="mb-6 pt-2 border-t border-[var(--border)] mt-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" wire:model="isPublished" class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[var(--teal)]"></div>
                        </div>
                        <span class="text-sm font-semibold text-[var(--text-primary)]">Publish Tugas</span>
                    </label>
                    <div class="text-xs text-[var(--text-muted)] mt-1">Jika aktif, mahasiswa bisa melihat tugas ini.</div>
                </div>

                <div class="pt-4 border-t border-[var(--border)]">
                    <button type="submit" class="btn btn-primary w-full justify-center">
                        <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                        <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>