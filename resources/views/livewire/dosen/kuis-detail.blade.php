<div class="w-full px-2 xl:px-4 space-y-5 pb-12 fade-in">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('dosen.kuis.index', $kelas) }}"
               class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm inline-flex items-center gap-1 transition mb-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kuis
            </a>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">{{ $kuis->judul }}</h1>
            <p class="text-[var(--text-secondary)] text-sm mt-0.5">{{ $kelas->mataKuliah->nama ?? '' }} — {{ $kelas->nama_kelas }}</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="togglePublish"
                    class="btn {{ $kuis->is_published ? 'btn-ghost border border-orange-400 text-orange-500' : 'btn-outline border-[var(--teal)] text-[var(--teal)]' }}">
                <i class="fas fa-{{ $kuis->is_published ? 'eye-slash' : 'globe' }} mr-2"></i>
                {{ $kuis->is_published ? 'Sembunyikan' : 'Publish Kuis' }}
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3 rounded-lg text-sm font-medium border" style="background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3); color: #10B981;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Kolom Kiri: Info + Soal --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Info Kuis --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4 border-b border-[var(--border)] pb-3">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-info-circle text-[var(--teal)]"></i>
                        <h2 class="font-bold text-[var(--text-primary)]">Informasi Kuis</h2>
                    </div>
                    <button wire:click="$toggle('editInfo')" class="btn btn-sm btn-ghost border border-[var(--border)]">
                        <i class="fas fa-{{ $editInfo ? 'times' : 'edit' }} mr-1"></i> {{ $editInfo ? 'Batal' : 'Edit' }}
                    </button>
                </div>

                @if($editInfo)
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1">Judul *</label>
                            <input wire:model="judul" type="text" class="form-input w-full">
                            @error('judul') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1">Deskripsi</label>
                            <textarea wire:model="deskripsi" class="form-input w-full" rows="3"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1">Waktu Buka *</label>
                                <input wire:model="bukaAt" type="datetime-local" class="form-input w-full">
                                @error('bukaAt') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1">Waktu Tutup *</label>
                                <input wire:model="tutupAt" type="datetime-local" class="form-input w-full">
                                @error('tutupAt') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1">Durasi (Menit) *</label>
                                <input wire:model="durasiMenit" type="number" min="1" class="form-input w-full">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-1">Maks. Percobaan</label>
                                <input wire:model="maksPercobaan" type="number" min="1" class="form-input w-full">
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="acakSoal" class="w-4 h-4 accent-[var(--teal)]">
                                <span class="text-sm text-[var(--text-primary)]">Acak Soal</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="tampilkanPembahasan" class="w-4 h-4 accent-[var(--teal)]">
                                <span class="text-sm text-[var(--text-primary)]">Tampilkan Pembahasan</span>
                            </label>
                        </div>
                        <div class="flex justify-end">
                            <button wire:click="saveInfo" class="btn btn-primary">
                                <span wire:loading.remove wire:target="saveInfo"><i class="fas fa-save mr-2"></i> Simpan Perubahan</span>
                                <span wire:loading wire:target="saveInfo"><i class="fas fa-circle-notch fa-spin mr-2"></i> Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 text-sm">
                        <div class="space-y-1">
                            <div class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Tipe</div>
                            <div class="badge badge-gray uppercase inline-flex text-xs font-semibold px-2 py-0.5">{{ $kuis->tipe }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Durasi</div>
                            <div class="font-bold text-[var(--text-primary)] text-base">{{ $kuis->durasi_menit }} Menit</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Waktu Buka</div>
                            <div class="font-bold text-[var(--text-primary)] text-base">{{ $kuis->buka_at?->format('d M Y, H:i') ?? '—' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Waktu Tutup</div>
                            <div class="font-bold text-[var(--text-primary)] text-base">{{ $kuis->tutup_at?->format('d M Y, H:i') ?? '—' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Maks. Percobaan</div>
                            <div class="font-bold text-[var(--text-primary)] text-base">{{ $kuis->maks_percobaan }}x</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Acak Soal</div>
                            <div class="badge {{ $kuis->acak_soal ? 'badge-green' : 'badge-gray' }} inline-flex text-xs font-semibold px-2 py-0.5">
                                {{ $kuis->acak_soal ? 'Aktif' : 'Tidak' }}
                            </div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Tampilkan Pembahasan</div>
                            <div class="badge {{ $kuis->tampilkan_pembahasan ? 'badge-green' : 'badge-gray' }} inline-flex text-xs font-semibold px-2 py-0.5">
                                {{ $kuis->tampilkan_pembahasan ? 'Ya' : 'Tidak' }}
                            </div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-bold uppercase tracking-wider text-[var(--text-secondary)]">Nilai Maksimal</div>
                            <div class="font-bold text-[var(--text-primary)] text-base">{{ $kuis->nilai_max ?? '—' }}</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Bank Soal --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4 border-b border-[var(--border)] pb-3">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-list-ol text-[var(--teal)]"></i>
                        <h2 class="font-bold text-[var(--text-primary)]">Bank Soal ({{ $kuis->soal->count() }})</h2>
                    </div>
                    @if(!$showSoalForm)
                        <button wire:click="addSoal" class="btn btn-sm btn-outline border-[var(--teal)] text-[var(--teal)]">
                            <i class="fas fa-plus mr-1"></i> Tambah Soal
                        </button>
                    @endif
                </div>

                {{-- Form Soal --}}
                @if($showSoalForm)
                    <div class="border-2 border-dashed border-[var(--border)] rounded-xl p-5 bg-[var(--input-bg)] mb-6">
                        <h3 class="font-bold text-[var(--text-primary)] mb-4">
                            {{ $editSoalId > 0 ? 'Edit Soal' : 'Tambah Soal Baru' }}
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
                                    <label class="text-xs font-semibold text-[var(--text-secondary)]">Pilihan Jawaban</label>
                                    @if(count($soalPilihan) < 6)
                                        <button wire:click="addPilihan" class="text-xs text-[var(--teal)] hover:underline"><i class="fas fa-plus"></i> Tambah Opsi</button>
                                    @endif
                                </div>
                                @error('soalPilihan') <div class="text-red-500 text-xs mb-2">{{ $message }}</div> @enderror
                                <div class="space-y-2">
                                    @foreach($soalPilihan as $i => $pilihan)
                                        <div class="flex items-center gap-2">
                                            <input type="radio" wire:click="setPilihBenar({{ $i }})" {{ $pilihan['is_benar'] ? 'checked' : '' }}
                                                   name="benar" class="w-4 h-4 accent-[var(--teal)]" title="Jawaban benar">
                                            <input wire:model="soalPilihan.{{ $i }}.teks" type="text" class="form-input flex-1 text-sm py-1.5" placeholder="Opsi {{ chr(65+$i) }}">
                                            @if(count($soalPilihan) > 2)
                                                <button wire:click="hapusPilihan({{ $i }})" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-times"></i></button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-2 justify-end">
                            <button wire:click="batalSoal" class="btn btn-sm btn-ghost border border-[var(--border)]">Batal</button>
                            <button wire:click="simpanSoal" class="btn btn-sm btn-primary">Simpan Soal</button>
                        </div>
                    </div>
                @endif

                {{-- Daftar Soal --}}
                <div class="space-y-3">
                    @forelse($kuis->soal as $idx => $kuisSoal)
                        @php $bank = $kuisSoal->bankSoal; @endphp
                        <div class="border border-[var(--border)] rounded-lg p-4 flex gap-4 hover:border-[var(--teal)] transition">
                            <div class="font-bold text-[var(--teal)] w-6 text-center shrink-0">{{ $idx + 1 }}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[var(--text-primary)] font-medium text-sm mb-2">{{ $bank?->pertanyaan }}</p>
                                <div class="flex gap-2 mb-2">
                                    <span class="badge badge-gray text-[10px] uppercase">{{ str_replace('_', ' ', $bank?->tipe) }}</span>
                                    <span class="badge badge-blue text-[10px]">{{ $bank?->bobot }} Poin</span>
                                </div>
                                @if($bank?->tipe === 'pilihan_ganda' && $bank->opsi)
                                    @php $jawabanTemp = $bank->jawaban ?? []; @endphp
                                    <div class="flex flex-col gap-1.5 mt-2">
                                        @foreach($bank->opsi as $j => $opsiTeks)
                                            @php 
                                                $isBenar = false;
                                                $matchIdx = array_search($opsiTeks, $jawabanTemp);
                                                if ($matchIdx !== false) {
                                                    $isBenar = true;
                                                    unset($jawabanTemp[$matchIdx]);
                                                }
                                            @endphp
                                            <div class="flex items-center gap-2 text-sm px-3 py-1.5 rounded-lg border
                                                {{ $isBenar
                                                    ? 'border-green-400 bg-green-50 text-green-800 font-semibold'
                                                    : 'border-[var(--border)] bg-[var(--bg-body)] text-[var(--text-secondary)]' }}">
                                                <span class="w-5 h-5 rounded-full text-[10px] font-bold flex items-center justify-center shrink-0
                                                    {{ $isBenar ? 'bg-green-500 text-white' : 'bg-[var(--border)] text-[var(--text-muted)]' }}">
                                                    {{ $isBenar ? '✓' : chr(65 + $j) }}
                                                </span>
                                                {{ $opsiTeks }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col gap-1 shrink-0">
                                <button wire:click="editSoal({{ $kuisSoal->id }})" class="text-blue-500 hover:bg-blue-50 p-1.5 rounded transition" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button wire:click="hapusSoal({{ $kuisSoal->id }})"
                                        wire:confirm="Yakin hapus soal ini?"
                                        class="text-red-500 hover:bg-red-50 p-1.5 rounded transition" title="Hapus">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        @if(!$showSoalForm)
                            <div class="py-8 text-center text-[var(--text-muted)]">
                                <p>Belum ada soal.</p>
                                <button wire:click="addSoal" class="btn btn-sm btn-outline mt-3 border-[var(--teal)] text-[var(--teal)]">
                                    <i class="fas fa-plus mr-1"></i> Tambah Soal Pertama
                                </button>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Sidebar --}}
        <div class="space-y-5">
            {{-- Status Card --}}
            <div class="card p-5">
                <h3 class="font-bold text-[var(--text-primary)] mb-4 border-b border-[var(--border)] pb-2">Status Kuis</h3>
                @php $status = $kuis->statusLabel(); @endphp
                <div class="text-center py-4">
                    <span class="badge text-sm px-4 py-1.5 {{ match($status) { 'aktif' => 'badge-green', 'terjadwal' => 'badge-blue', 'selesai' => 'badge-red', default => 'badge-gray' } }} capitalize">
                        {{ $status }}
                    </span>
                    <p class="text-xs text-[var(--text-muted)] mt-3">
                        {{ $kuis->is_published ? 'Kuis sudah dipublish' : 'Kuis masih draft' }}
                    </p>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="card p-5">
                <h3 class="font-bold text-[var(--text-primary)] mb-4 border-b border-[var(--border)] pb-2">Statistik</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[var(--text-muted)]">Jumlah Soal</span>
                        <span class="font-semibold text-[var(--text-primary)]">{{ $kuis->soal->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[var(--text-muted)]">Total Peserta</span>
                        <span class="font-semibold text-[var(--text-primary)]">{{ $kuis->sesi->pluck('mahasiswa_id')->unique()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[var(--text-muted)]">Sudah Selesai</span>
                        <span class="font-semibold text-[var(--text-primary)]">{{ $kuis->sesi->where('status', 'selesai')->count() }}</span>
                    </div>
                    @if($kuis->sesi->where('status', 'selesai')->count() > 0)
                        <div class="flex justify-between">
                            <span class="text-[var(--text-muted)]">Rata-rata Nilai</span>
                            <span class="font-semibold text-[var(--teal)]">
                                {{ round($kuis->sesi->where('status', 'selesai')->avg('nilai'), 1) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Daftar Peserta --}}
            <div class="card p-0 overflow-hidden">
                <div class="px-5 py-3 border-b border-[var(--border)]">
                    <h3 class="font-bold text-[var(--text-primary)] text-sm">Hasil Peserta</h3>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    @forelse($kuis->sesi->where('status', 'selesai')->sortByDesc('nilai') as $sesi)
                        <div class="flex items-center justify-between px-5 py-3 border-b border-[var(--border)] last:border-0">
                            <div>
                                <div class="text-sm font-medium text-[var(--text-primary)]">{{ $sesi->mahasiswa->name ?? '-' }}</div>
                                <div class="text-xs text-[var(--text-muted)]">Percobaan ke-{{ $sesi->percobaan_ke }}</div>
                            </div>
                            <span class="badge {{ ($sesi->nilai ?? 0) >= 70 ? 'badge-green' : 'badge-red' }} font-bold">
                                {{ $sesi->nilai ?? '-' }}
                            </span>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-center text-xs text-[var(--text-muted)]">Belum ada peserta yang menyelesaikan kuis.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
