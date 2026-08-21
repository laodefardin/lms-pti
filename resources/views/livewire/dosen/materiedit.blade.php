<div class="max-w-4xl mx-auto space-y-6 pb-12 fade-in">
    <div class="flex items-center justify-between mb-4">
        <div>
            <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm inline-block transition mb-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Kelas
            </a>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Edit Pertemuan</h1>
            <p class="text-[var(--text-secondary)] mt-1">Matakuliah: {{ $kelas->mataKuliah->nama ?? '' }}</p>
        </div>
    </div>

    <div class="card p-6">
        <form wire:submit.prevent="simpan" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pertemuan Ke <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="nomor" min="1" class="form-input w-full" required>
                    @error('nomor') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pertemuan</label>
                    <input type="date" wire:model="tanggal" class="form-input w-full">
                    @error('tanggal') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Topik / Judul Pertemuan <span class="text-red-500">*</span></label>
                <input type="text" wire:model="topik" class="form-input w-full" placeholder="Misal: Pengenalan HTML Dasar" required>
                @error('topik') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Catatan Tambahan (Opsional)</label>
                <textarea wire:model="deskripsi" class="form-input w-full" rows="4" placeholder="Tuliskan deskripsi singkat mengenai pertemuan ini..."></textarea>
                @error('deskripsi') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" class="btn btn-outline" style="border-color:var(--border); color:var(--text-primary);">Batal</a>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="simpan"><i class="fas fa-save mr-2"></i> Simpan Perubahan</span>
                    <span wire:loading wire:target="simpan"><i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>