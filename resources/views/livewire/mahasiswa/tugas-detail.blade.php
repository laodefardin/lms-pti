<div class="fade-in">
    <!-- Breadcrumb -->
    <div style="font-size: 0.875rem; margin-bottom: 1rem; color: var(--text-muted);">
        <a href="{{ route('mahasiswa.tugas') ?? '#' }}" style="color: var(--teal); text-decoration: none;">Tugas</a> > {{ $tugas->judul }}
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- Task Description (LEFT) -->
        <div style="grid-column: span 2;">
            <div class="card" style="padding: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                    <span class="badge badge-teal">{{ $tugas->kelas->mataKuliah->nama ?? '' }}</span>
                </div>
                <h1 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; color: var(--text-primary);">{{ $tugas->judul }}</h1>
                
                <div style="display: flex; align-items: center; font-size: 0.875rem; margin-bottom: 1.5rem; padding: 1rem; border-radius: 0.375rem; background-color: var(--input-bg); border: 1px solid var(--border);">
                    <div style="flex: 1;">
                        <p style="color: var(--text-muted); margin: 0;">Batas Waktu</p>
                        <p style="font-weight: 600; margin: 0; color: var(--text-primary);">{{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}</p>
                    </div>
                    @if($tugas->file_soal)
                    <div>
                        <a href="{{ Storage::url($tugas->file_soal) }}" target="_blank" class="btn btn-outline btn-sm">Download Soal</a>
                    </div>
                    @endif
                </div>

                <div style="color: var(--text-secondary); line-height: 1.6;">
                    {!! $tugas->deskripsi !!}
                </div>
            </div>
        </div>

        <!-- Submission Form (RIGHT) -->
        <div>
            <div class="card" style="padding: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); color: var(--text-primary);">Status Pengumpulan</h2>

                @if($showSuccess)
                    <div style="margin-bottom: 1rem; padding: 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; background-color: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid var(--success);">
                        Tugas berhasil dikumpulkan!
                    </div>
                @endif

                @php
                    $isPassed = \Carbon\Carbon::parse($tugas->deadline)->isPast();
                @endphp

                @if($pengumpulan)
                    <div style="margin-bottom: 1rem;">
                        <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0 0 0.25rem 0;">Status</p>
                        @if($pengumpulan->status === 'dinilai')
                            <span class="badge badge-green">Dinilai ({{ $pengumpulan->nilai }}/100)</span>
                        @else
                            <span class="badge badge-teal">Dikumpulkan</span>
                        @endif
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0 0 0.25rem 0;">Waktu Pengumpulan</p>
                        <p style="font-weight: 500; margin: 0; color: var(--text-primary);">{{ \Carbon\Carbon::parse($pengumpulan->waktu_pengumpulan)->format('d M Y, H:i') }}</p>
                    </div>
                    
                    @if($pengumpulan->file_path)
                        <div style="margin-bottom: 1rem;">
                            <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0 0 0.25rem 0;">File Terlampir</p>
                            <a href="{{ Storage::url($pengumpulan->file_path) }}" target="_blank" style="font-size: 0.875rem; color: var(--teal); text-decoration: none;">Lihat File</a>
                        </div>
                    @endif

                    @if($pengumpulan->link_url)
                        <div style="margin-bottom: 1rem;">
                            <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0 0 0.25rem 0;">Link Tautan</p>
                            <a href="{{ $pengumpulan->link_url }}" target="_blank" style="font-size: 0.875rem; color: var(--teal); text-decoration: none; word-break: break-all;">{{ $pengumpulan->link_url }}</a>
                        </div>
                    @endif
                    
                    @if($pengumpulan->catatan_dosen)
                        <div style="margin-top: 1.5rem; padding: 1rem; border-radius: 0.375rem; background-color: var(--teal-light); border: 1px solid var(--border-teal);">
                            <p style="font-size: 0.875rem; font-weight: 600; margin: 0 0 0.25rem 0; color: var(--teal-dark);">Feedback Dosen:</p>
                            <p style="font-size: 0.875rem; margin: 0; color: var(--teal-dark);">{{ $pengumpulan->catatan_dosen }}</p>
                        </div>
                    @endif
                    
                    @if($pengumpulan->status !== 'dinilai' && !$isPassed)
                        <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid var(--border);">
                        <p style="font-size: 0.875rem; margin-bottom: 0.75rem; font-weight: 600; color: var(--text-primary);">Update Pengumpulan</p>
                    @endif
                @endif

                @if(!$pengumpulan && $isPassed)
                    <div style="padding: 1rem; border-radius: 0.375rem; text-align: center; background-color: rgba(239, 68, 68, 0.1); border: 1px solid var(--danger);">
                        <p style="font-weight: 600; margin: 0; color: var(--danger);">Batas Waktu Telah Lewat</p>
                        <p style="font-size: 0.875rem; margin: 0.25rem 0 0 0; color: var(--danger);">Anda tidak dapat lagi mengumpulkan tugas ini.</p>
                    </div>
                @elseif(!$pengumpulan || ($pengumpulan && $pengumpulan->status !== 'dinilai' && !$isPassed))
                    <form wire:submit.prevent="kumpulkan" style="display: flex; flex-direction: column; gap: 1rem;">
                        @error('general') <span style="font-size: 0.75rem; color: var(--danger);">{{ $message }}</span> @enderror
                        
                        <div>
                            <label class="form-label" style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; color: var(--text-primary);">Upload File</label>
                            <input type="file" wire:model="fileUpload" class="form-input" style="width: 100%; box-sizing: border-box;">
                            @error('fileUpload') <span style="font-size: 0.75rem; color: var(--danger);">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; color: var(--text-primary);">Tautan (Link)</label>
                            <input type="url" wire:model="linkUrl" placeholder="https://..." class="form-input" style="width: 100%; box-sizing: border-box;">
                            @error('linkUrl') <span style="font-size: 0.75rem; color: var(--danger);">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; color: var(--text-primary);">Catatan</label>
                            <textarea wire:model="catatan" rows="3" class="form-input" style="width: 100%; box-sizing: border-box;"></textarea>
                            @error('catatan') <span style="font-size: 0.75rem; color: var(--danger);">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-full" style="display: flex; justify-content: center; align-items: center; width: 100%;">
                            <span wire:loading.remove wire:target="kumpulkan">Kumpulkan Tugas</span>
                            <span wire:loading wire:target="kumpulkan">Mengirim...</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
