<div class="w-full px-2 xl:px-4 space-y-5 pb-12 fade-in">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <a href="{{ route('dosen.matakuliah.detail', ['kelas' => $kelas, 'tab' => 'kuis']) }}"
               class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm inline-flex items-center gap-1 transition mb-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Manajemen Kuis
            </a>
            @if($pertemuanId)
                @php $pt = \App\Models\Pertemuan::find($pertemuanId); @endphp
                @if($pt)
                    <div class="mb-2 flex items-center gap-3 px-4 py-2.5 rounded-xl border text-sm font-medium" style="background:rgba(99,102,241,0.08); border-color:rgba(99,102,241,0.3); color:#4f46e5;">
                        <i class="fas fa-link"></i>
                        <span>Kuis ini akan dikaitkan ke <strong>Pertemuan ke-{{ $pt->nomor }} &mdash; {{ $pt->topik ?? 'Tanpa Judul' }}</strong></span>
                    </div>
                @endif
            @endif
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Buat Kuis / Ujian Baru</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- Kolom Kiri: Detail Kuis & Daftar Soal --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Detail Kuis --}}
            <div class="card p-6">
                <div class="flex items-center gap-2 mb-6 border-b border-[var(--border)] pb-3">
                    <i class="fas fa-info-circle text-[var(--teal)] text-lg"></i>
                    <h2 class="text-lg font-bold text-[var(--text-primary)]">Informasi Dasar</h2>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Judul Kuis / Ujian *</label>
                    <input wire:model="judul" type="text" class="form-input w-full" placeholder="Contoh: Kuis 1 Dasar Pemrograman">
                    @error('judul') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Deskripsi (Opsional)</label>
                    <textarea wire:model="deskripsi" class="form-input w-full" rows="3" placeholder="Deskripsi singkat mengenai kuis ini..."></textarea>
                    @error('deskripsi') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-2">
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Tipe Evaluasi</label>
                        <select wire:model="tipe" class="form-input w-full">
                            <option value="kuis">Kuis Rutin</option>
                            <option value="uts">Ujian Tengah Semester (UTS)</option>
                            <option value="uas">Ujian Akhir Semester (UAS)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Durasi Pengerjaan (Menit) *</label>
                        <input wire:model="durasiMenit" type="number" min="1" max="300" class="form-input w-full">
                        @error('durasiMenit') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Builder Soal --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-6 border-b border-[var(--border)] pb-3">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-list-ol text-[var(--teal)] text-lg"></i>
                        <h2 class="text-lg font-bold text-[var(--text-primary)]">Bank Soal ({{ count($soalList) }})</h2>
                    </div>
                    @if(!$showSoalForm)
                        <button wire:click="addSoal" class="btn btn-sm btn-outline border-[var(--teal)] text-[var(--teal)] hover:bg-[var(--teal-dim)]">
                            <i class="fas fa-plus mr-1"></i> Tambah Soal
                        </button>
                    @endif
                </div>

                @error('soalList') 
                    <div class="p-3 mb-4 rounded bg-red-50 text-red-600 border border-red-200 text-sm font-medium">
                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $message }}
                    </div> 
                @enderror

                {{-- Form Editor Soal --}}
                @if($showSoalForm)
                    <div class="border-2 border-dashed border-[var(--border)] p-5 rounded-xl bg-[var(--input-bg)] mb-6">
                        <h3 class="font-bold text-[var(--text-primary)] mb-4">
                            {{ $editSoalIdx >= 0 ? 'Edit Soal #'.($editSoalIdx+1) : 'Buat Soal Baru' }}
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Tipe Soal</label>
                                <select wire:model.live="soalTipe" class="form-input w-full text-sm">
                                    <option value="pilihan_ganda">Pilihan Ganda</option>
                                    <option value="essay">Essay / Isian</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Bobot Poin</label>
                                <input wire:model="soalBobot" type="number" min="1" class="form-input w-full text-sm">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Pertanyaan *</label>
                            <textarea wire:model="soalPertanyaan" class="form-input w-full text-sm" rows="3" placeholder="Tuliskan pertanyaan..."></textarea>
                            @error('soalPertanyaan') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        @if($soalTipe === 'pilihan_ganda')
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)]">Pilihan Jawaban (Pilih yang benar)</label>
                                    @if(count($soalPilihan) < 6)
                                        <button wire:click="addPilihan" class="text-xs text-[var(--teal)] hover:underline"><i class="fas fa-plus"></i> Tambah Opsi</button>
                                    @endif
                                </div>
                                
                                @error('soalPilihan') <div class="text-red-500 text-xs mb-2">{{ $message }}</div> @enderror

                                <div class="space-y-2">
                                    @foreach($soalPilihan as $i => $pilihan)
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="benarIdx" wire:click="setPilihBenar({{ $i }})" {{ $pilihan['is_benar'] ? 'checked' : '' }} 
                                                   class="w-4 h-4 text-[var(--teal)] accent-[var(--teal)]" title="Tandai sebagai jawaban benar">
                                            <input wire:model="soalPilihan.{{ $i }}.teks" type="text" class="form-input flex-1 text-sm py-1.5" placeholder="Opsi {{ chr(65+$i) }}">
                                            @if(count($soalPilihan) > 2)
                                                <button wire:click="hapusPilihan({{ $i }})" class="text-red-400 hover:text-red-600 px-2" title="Hapus opsi"><i class="fas fa-times"></i></button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-2 justify-end mt-4">
                            <button wire:click="batalSoal" class="btn btn-sm btn-ghost border border-[var(--border)]">Batal</button>
                            <button wire:click="simpanSoal" class="btn btn-sm btn-primary">Simpan Soal ke Daftar</button>
                        </div>
                    </div>
                @endif

                {{-- Daftar Soal --}}
                <div class="space-y-3">
                    @forelse($soalList as $idx => $soal)
                        <div class="border border-[var(--border)] rounded-lg p-4 flex gap-4 hover:border-[var(--teal)] transition">
                            <div class="font-bold text-[var(--teal)] w-6 text-center">{{ $idx + 1 }}</div>
                            <div class="flex-1">
                                <p class="text-[var(--text-primary)] font-medium text-sm mb-2">{{ $soal['pertanyaan'] }}</p>
                                <div class="flex gap-2 mb-2">
                                    <span class="badge badge-gray text-[10px] uppercase">{{ str_replace('_', ' ', $soal['tipe']) }}</span>
                                    <span class="badge badge-blue text-[10px]">{{ $soal['bobot'] }} Poin</span>
                                </div>
                                
                                @if($soal['tipe'] === 'pilihan_ganda')
                                    <div class="grid grid-cols-2 gap-2 mt-2">
                                        @foreach($soal['pilihan'] as $j => $pilihan)
                                            @if(!empty($pilihan['teks']))
                                                <div class="text-xs p-1.5 rounded {{ $pilihan['is_benar'] ? 'bg-[var(--success)] text-white font-semibold' : 'bg-[var(--bg-body)] text-[var(--text-secondary)]' }}">
                                                    {{ chr(65+$j) }}. {{ $pilihan['teks'] }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col gap-2">
                                <button wire:click="editSoal({{ $idx }})" class="text-blue-500 hover:bg-blue-50 p-1.5 rounded transition"><i class="fas fa-edit"></i></button>
                                <button wire:click="hapusSoal({{ $idx }})" class="text-red-500 hover:bg-red-50 p-1.5 rounded transition"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    @empty
                        @if(!$showSoalForm)
                            <div class="text-center py-8 text-[var(--text-muted)]">
                                <p>Belum ada soal ditambahkan.</p>
                                <p class="text-xs mt-1">Klik tombol "Tambah Soal" untuk mulai membuat bank soal.</p>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Pengaturan Jadwal --}}
        <div class="lg:sticky lg:top-4 space-y-6">
            <div class="card p-6">
                <div class="flex items-center gap-2 mb-6 border-b border-[var(--border)] pb-3">
                    <i class="fas fa-cog text-[var(--teal)] text-lg"></i>
                    <h2 class="text-lg font-bold text-[var(--text-primary)]">Pengaturan Ujian</h2>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Waktu Buka Kuis *</label>
                    <input wire:model="bukaAt" type="datetime-local" class="form-input w-full">
                    @error('bukaAt') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Waktu Tutup Kuis *</label>
                    <input wire:model="tutupAt" type="datetime-local" class="form-input w-full">
                    @error('tutupAt') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Batas Percobaan (Retries)</label>
                    <input wire:model="maksPercobaan" type="number" min="1" max="10" class="form-input w-full">
                </div>

                <div class="space-y-3 border-t border-[var(--border)] pt-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="acakSoal" class="w-4 h-4 accent-[var(--teal)]">
                        <span class="text-sm font-medium text-[var(--text-primary)]">Acak Urutan Soal</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="tampilkanPembahasan" class="w-4 h-4 accent-[var(--teal)]">
                        <span class="text-sm font-medium text-[var(--text-primary)]">Tampilkan Pembahasan setelah selesai</span>
                    </label>
                    
                    <label class="flex items-start gap-3 cursor-pointer p-3 bg-[var(--input-bg)] border border-[var(--border)] rounded-lg hover:border-[var(--teal)] transition group mt-4">
                        <input type="checkbox" wire:model="isPublished" class="w-5 h-5 mt-0.5 accent-[var(--teal)]">
                        <div>
                            <div class="text-sm font-bold text-[var(--text-primary)] group-hover:text-[var(--teal)] transition">Publish Sekarang</div>
                            <div class="text-xs text-[var(--text-muted)] mt-0.5">Mahasiswa akan menerima notifikasi kuis</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-3">
                <button wire:click="save" class="btn btn-primary w-full justify-center text-sm shadow-md shadow-teal-500/20">
                    <span wire:loading.remove wire:target="save"><i class="fas fa-save mr-2"></i> Simpan Kuis</span>
                    <span wire:loading wire:target="save"><i class="fas fa-circle-notch fa-spin mr-2"></i> Memproses...</span>
                </button>
            </div>
        </div>
    </div>
</div>
