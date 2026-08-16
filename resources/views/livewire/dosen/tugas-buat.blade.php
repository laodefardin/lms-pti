<div class="fade-in">
    <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; color:var(--text-secondary); margin-bottom:1.25rem;">
        <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" style="color:var(--text-secondary); text-decoration:none;" onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='var(--text-secondary)'">{{ $kelas->mataKuliah->nama }}</a>
        <span>/</span><span style="color:var(--text-primary);">Buat Tugas</span>
    </div>

    <div style="display:grid; grid-template-columns:1fr 320px; gap:1.25rem; align-items:start;">
        <div>
            <div class="card" style="margin-bottom:1.25rem;">
                <div class="section-title" style="margin-bottom:1.25rem;">📝 Detail Tugas</div>

                <div style="margin-bottom:1rem;">
                    <label class="form-label">Judul Tugas *</label>
                    <input wire:model="judul" type="text" class="form-input" placeholder="Contoh: Tugas 1 — Membuat Halaman HTML">
                    @error('judul') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="margin-bottom:1rem;">
                    <label class="form-label">Deskripsi & Petunjuk *</label>
                    <textarea wire:model="deskripsi" class="form-input" rows="6" placeholder="Jelaskan apa yang harus dikerjakan mahasiswa..."
                              style="resize:vertical; line-height:1.6;"></textarea>
                    @error('deskripsi') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="margin-bottom:1rem;">
                    <label class="form-label">File Soal (opsional)</label>
                    <div style="border:2px dashed var(--border); border-radius:10px; padding:1.25rem; text-align:center;">
                        @if($fileSoal)
                            <div style="color:var(--success); font-weight:600; font-size:0.875rem;">📎 {{ $fileSoal->getClientOriginalName() }}</div>
                        @else
                            <div style="font-size:1.5rem; margin-bottom:0.4rem;">📎</div>
                            <div style="font-size:0.78rem; color:var(--text-secondary); margin-bottom:0.6rem;">Upload file soal untuk mahasiswa (PDF, DOCX, ZIP)</div>
                            <label style="cursor:pointer; padding:0.35rem 0.875rem; background:var(--input-bg); border:1px solid var(--border); border-radius:7px; font-size:0.78rem; color:var(--text-primary); font-weight:500;">
                                Pilih File <input type="file" wire:model="fileSoal" style="display:none;" accept=".pdf,.doc,.docx,.zip">
                            </label>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="section-title" style="margin-bottom:1rem;">📤 Pengaturan Pengumpulan</div>
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Tipe Pengumpulan</label>
                    <div style="display:flex; gap:0.5rem;">
                        @foreach(['upload'=>'📁 Upload File','link'=>'🔗 Link URL','keduanya'=>'📁🔗 Keduanya'] as $val => $label)
                        <button type="button" wire:click="$set('tipe','{{ $val }}')"
                                style="flex:1; padding:0.5rem; border-radius:8px; border:1.5px solid {{ $tipe === $val ? 'var(--teal)' : 'var(--border)' }}; background:{{ $tipe === $val ? 'var(--teal-dim)' : 'var(--input-bg)' }}; cursor:pointer; font-size:0.78rem; font-weight:600; color:{{ $tipe === $val ? 'var(--teal)' : 'var(--text-secondary)' }};">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

                @if($tipe !== 'link')
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Format File Diterima</label>
                    <div style="display:flex; flex-wrap:wrap; gap:0.4rem;">
                        @foreach(['pdf','docx','doc','zip','rar','jpg','png','mp4','py','js','php'] as $ext)
                        <button type="button" wire:click="toggleExt('{{ $ext }}')"
                                style="padding:0.25rem 0.65rem; border-radius:6px; font-size:0.73rem; font-weight:600; cursor:pointer; border:1.5px solid {{ in_array($ext, $allowedExt) ? 'var(--teal)' : 'var(--border)' }}; background:{{ in_array($ext, $allowedExt) ? 'var(--teal-dim)' : 'var(--input-bg)' }}; color:{{ in_array($ext, $allowedExt) ? 'var(--teal)' : 'var(--text-muted)' }}; transition:all 0.15s;">
                            .{{ $ext }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div style="margin-bottom:1rem;">
                    <label class="form-label">Ukuran File Maksimal (MB)</label>
                    <input wire:model="maxFileSize" type="number" class="form-input" min="1" max="100" style="max-width:120px;">
                </div>
                @endif
            </div>
        </div>

        {{-- Right sidebar --}}
        <div style="position:sticky; top:1rem;">
            <div class="card" style="margin-bottom:1rem;">
                <div class="section-title" style="margin-bottom:1rem;">⚙️ Pengaturan</div>

                <div style="margin-bottom:1rem;">
                    <label class="form-label">Deadline *</label>
                    <input wire:model="deadline" type="datetime-local" class="form-input">
                    @error('deadline') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Bobot Nilai (total 100%)</label>
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <input wire:model="bobotNilai" type="range" min="1" max="100" style="flex:1; accent-color:var(--teal);">
                        <span style="font-size:0.9rem; font-weight:700; color:var(--teal); min-width:35px;">{{ $bobotNilai }}%</span>
                    </div>
                </div>

                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; padding:0.75rem; background:var(--input-bg); border-radius:8px; border:1px solid var(--border);">
                    <input type="checkbox" wire:model="isPublished" style="accent-color:var(--teal); width:16px; height:16px;">
                    <div>
                        <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary);">Publish Sekarang</div>
                        <div style="font-size:0.7rem; color:var(--text-muted);">Mahasiswa bisa melihat dan mengumpulkan</div>
                    </div>
                </label>
            </div>

            <div style="display:flex; gap:0.5rem;">
                <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" class="btn btn-ghost btn-sm" style="flex:1; justify-content:center;">Batal</a>
                <button wire:click="save" class="btn btn-primary" style="flex:2;">
                    <span wire:loading.remove wire:target="save">💾 Buat Tugas</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>
