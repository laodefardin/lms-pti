<div class="fade-in">
    <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; color:var(--text-secondary); margin-bottom:1.25rem;">
        <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" style="color:var(--text-secondary); text-decoration:none;" onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='var(--text-secondary)'">{{ $kelas->mataKuliah->nama }}</a>
        <span>/</span><span style="color:var(--text-primary);">Buat Kuis</span>
    </div>

    <div style="display:grid; grid-template-columns:1fr 320px; gap:1.25rem; align-items:start;">

        {{-- LEFT: Kuis info + Soal builder --}}
        <div>
            {{-- Info Kuis --}}
            <div class="card" style="margin-bottom:1.25rem;">
                <div class="section-title" style="margin-bottom:1.25rem;"><i class="fas fa-bolt text-yellow-500"></i> Info Kuis</div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.875rem; margin-bottom:1rem;">
                    <div style="grid-column:1/-1;">
                        <label class="form-label">Judul Kuis *</label>
                        <input wire:model="judul" type="text" class="form-input" placeholder="Contoh: Kuis 1 — HTML & CSS">
                        @error('judul') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">Tipe</label>
                        <select wire:model="tipe" class="form-input">
                            <option value="kuis">Kuis Reguler</option>
                            <option value="uts">UTS</option>
                            <option value="uas">UAS</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Durasi (menit)</label>
                        <input wire:model="durasiMenit" type="number" class="form-input" min="5" max="300">
                        @error('durasiMenit') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">Buka Pada</label>
                        <input wire:model="bukaAt" type="datetime-local" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tutup Pada</label>
                        <input wire:model="tutupAt" type="datetime-local" class="form-input">
                        @error('tutupAt') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">KKM / Passing Grade</label>
                        <input wire:model="passingGrade" type="number" class="form-input" min="0" max="100" placeholder="60">
                    </div>
                    <div>
                        <label class="form-label">Maks. Percobaan</label>
                        <input wire:model="maxPercobaan" type="number" class="form-input" min="1" max="10">
                    </div>
                </div>

                <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                    <label style="display:flex; align-items:center; gap:0.4rem; cursor:pointer; font-size:0.82rem; color:var(--text-primary);">
                        <input type="checkbox" wire:model="acakSoal" style="accent-color:var(--teal);"> Acak urutan soal
                    </label>
                    <label style="display:flex; align-items:center; gap:0.4rem; cursor:pointer; font-size:0.82rem; color:var(--text-primary);">
                        <input type="checkbox" wire:model="tampilkanNilai" style="accent-color:var(--teal);"> Tampilkan nilai setelah selesai
                    </label>
                </div>
            </div>

            {{-- Bank Soal --}}
            <div class="card">
                <div class="section-header" style="margin-bottom:1rem;">
                    <div>
                        <div class="section-title"><i class="fas fa-clipboard-list"></i> Bank Soal</div>
                        <div class="section-sub">{{ count($soalList) }} soal ditambahkan</div>
                    </div>
                    <button wire:click="addSoal" class="btn btn-primary btn-sm" {{ $showSoalForm ? 'disabled style=opacity:0.5' : '' }}>
                        + Tambah Soal
                    </button>
                </div>
                @error('soalList') <div class="form-error" style="margin-bottom:0.75rem;">{{ $message }}</div> @enderror

                {{-- Soal Form (inline) --}}
                @if($showSoalForm)
                <div class="card" style="border-color:var(--border-teal); background:linear-gradient(135deg,var(--teal-dim),var(--bg-card)); margin-bottom:1.25rem;">
                    <div style="font-size:0.82rem; font-weight:700; color:var(--teal); margin-bottom:1rem;">
                        {{ $editSoalIdx >= 0 ? '✏️ Edit Soal ' . ($editSoalIdx+1) : '+ Soal Baru' }}
                    </div>

                    <div style="margin-bottom:0.875rem;">
                        <label class="form-label">Pertanyaan *</label>
                        <textarea wire:model="soalPertanyaan" class="form-input" rows="3" placeholder="Tulis pertanyaan..." style="resize:vertical;"></textarea>
                        @error('soalPertanyaan') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.875rem;">
                        <div>
                            <label class="form-label">Tipe Soal</label>
                            <select wire:model.live="soalTipe" class="form-input">
                                <option value="pg">Pilihan Ganda</option>
                                <option value="benar_salah">Benar / Salah</option>
                                <option value="esai">Esai</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Bobot Poin</label>
                            <input wire:model="soalBobot" type="number" class="form-input" min="1" max="10">
                        </div>
                    </div>

                    {{-- Pilihan --}}
                    @if($soalTipe !== 'esai')
                    <div style="margin-bottom:0.875rem;">
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                            <label class="form-label" style="margin-bottom:0;">Pilihan Jawaban (klik ● untuk tandai benar)</label>
                            @if($soalTipe === 'pg')
                            <button type="button" wire:click="addPilihan" class="btn btn-ghost btn-sm" style="font-size:0.7rem;">+ Pilihan</button>
                            @endif
                        </div>
                        @php $huruf = ['A','B','C','D','E','F']; @endphp
                        @foreach($soalPilihan as $pi => $pilihan)
                        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.4rem;">
                            <button type="button" wire:click="setPilihBenar({{ $pi }})"
                                    style="width:28px; height:28px; border-radius:50%; border:2px solid {{ $pilihan['is_benar'] ? 'var(--success)' : 'var(--border)' }}; background:{{ $pilihan['is_benar'] ? 'rgba(34,197,94,0.15)' : 'var(--input-bg)' }}; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all 0.15s;">
                                @if($pilihan['is_benar'])
                                <div style="width:12px; height:12px; border-radius:50%; background:var(--success);"></div>
                                @endif
                            </button>
                            <span style="font-weight:700; color:var(--teal); font-size:0.8rem; flex-shrink:0;">{{ $huruf[$pi] ?? '' }}.</span>
                            <input wire:model="soalPilihan.{{ $pi }}.teks" type="text" class="form-input"
                                   placeholder="Pilihan {{ $huruf[$pi] ?? '' }}..."
                                   style="flex:1;">
                            @if($soalTipe === 'pg' && count($soalPilihan) > 2)
                            <button type="button" wire:click="hapusPilihan({{ $pi }})"
                                    style="color:var(--danger); background:none; border:none; cursor:pointer; font-size:1rem; flex-shrink:0;">×</button>
                            @endif
                        </div>
                        @endforeach
                        @if(!collect($soalPilihan)->contains('is_benar', true))
                        <div style="font-size:0.72rem; color:var(--warning); margin-top:0.3rem;">⚠️ Pilih jawaban yang benar dengan klik tombol ●</div>
                        @endif
                    </div>
                    @else
                    <div style="padding:0.75rem; background:var(--input-bg); border-radius:8px; font-size:0.78rem; color:var(--text-muted); margin-bottom:0.875rem;">
                        <i class="fas fa-lightbulb"></i> Soal esai perlu dinilai manual oleh dosen
                    </div>
                    @endif

                    <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                        <button type="button" wire:click="batalSoal" class="btn btn-ghost btn-sm">Batal</button>
                        <button type="button" wire:click="simpanSoal" class="btn btn-primary btn-sm">
                            <span wire:loading.remove wire:target="simpanSoal"><i class="fas fa-check-circle"></i> Simpan Soal</span>
                            <span wire:loading wire:target="simpanSoal">Menyimpan...</span>
                        </button>
                    </div>
                </div>
                @endif

                {{-- Soal List --}}
                @forelse($soalList as $idx => $soal)
                <div style="display:flex; align-items:flex-start; gap:0.875rem; padding:0.875rem; background:var(--input-bg); border:1px solid var(--border); border-radius:10px; margin-bottom:0.5rem; transition:border-color 0.15s;"
                     onmouseover="this.style.borderColor='var(--border-teal)'" onmouseout="this.style.borderColor='var(--border)'">
                    <div style="width:28px; height:28px; background:var(--teal-dim); border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; color:var(--teal); flex-shrink:0;">{{ $idx+1 }}</div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.25rem; line-height:1.4;">{{ Str::limit($soal['pertanyaan'], 80) }}</div>
                        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                            <span class="badge badge-gray">{{ $soal['tipe'] === 'pg' ? 'Pilihan Ganda' : ($soal['tipe'] === 'esai' ? 'Esai' : 'Benar/Salah') }}</span>
                            <span class="badge badge-teal">{{ $soal['bobot'] }} poin</span>
                            @if($soal['tipe'] !== 'esai')
                            <span style="font-size:0.68rem; color:var(--text-muted);">{{ count($soal['pilihan']) }} pilihan</span>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex; gap:0.35rem; flex-shrink:0;">
                        <button type="button" wire:click="editSoal({{ $idx }})" class="btn btn-ghost btn-sm" style="padding:0.25rem 0.5rem;">✏️</button>
                        <button type="button" wire:click="hapusSoal({{ $idx }})" class="btn btn-danger btn-sm" style="padding:0.25rem 0.5rem;"
                                onclick="return confirm('Hapus soal ini?')">🗑</button>
                    </div>
                </div>
                @empty
                @if(!$showSoalForm)
                <div style="text-align:center; padding:2.5rem; color:var(--text-muted);">
                    <div style="font-size:2rem; margin-bottom:0.5rem;"><i class="fas fa-clipboard-list" style="color:var(--text-muted);"></i></div>
                    <div style="font-size:0.875rem;">Belum ada soal. Klik "Tambah Soal" untuk mulai.</div>
                </div>
                @endif
                @endforelse

                @if(count($soalList) > 0)
                <div style="margin-top:0.75rem; padding:0.75rem; background:var(--teal-dim); border-radius:8px; font-size:0.78rem; color:var(--text-secondary); display:flex; justify-content:space-between;">
                    <span>Total soal: <strong style="color:var(--text-primary);">{{ count($soalList) }}</strong></span>
                    <span>Total poin: <strong style="color:var(--teal);">{{ collect($soalList)->sum('bobot') }}</strong></span>
                </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Settings --}}
        <div style="position:sticky; top:1rem;">
            <div class="card" style="margin-bottom:1rem; background:var(--teal-dim); border-color:var(--border-teal);">
                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); margin-bottom:0.875rem;"><i class="fas fa-chart-bar"></i> Ringkasan Kuis</div>
                <div style="font-size:0.82rem; color:var(--text-primary); line-height:2;">
                    <div>Soal: <strong>{{ count($soalList) }}</strong></div>
                    <div>Durasi: <strong>{{ $durasiMenit }} menit</strong></div>
                    <div>KKM: <strong>{{ $passingGrade ?? '-' }}</strong></div>
                    <div>Percobaan: <strong>{{ $maxPercobaan }}x</strong></div>
                </div>
            </div>

            <div class="card" style="margin-bottom:1rem;">
                <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer; padding:0.75rem; border-radius:8px; background:var(--input-bg); border:1px solid var(--border);">
                    <input type="checkbox" wire:model="isPublished" style="accent-color:var(--teal); width:16px; height:16px;">
                    <div>
                        <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary);">Publish Sekarang</div>
                        <div style="font-size:0.7rem; color:var(--text-muted);">Aktif sesuai jadwal buka/tutup</div>
                    </div>
                </label>
            </div>

            <div style="display:flex; gap:0.5rem;">
                <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" class="btn btn-ghost btn-sm" style="flex:1; justify-content:center;">Batal</a>
                <button wire:click="save" class="btn btn-primary" style="flex:2;">
                    <span wire:loading.remove wire:target="save">💾 Buat Kuis</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>
