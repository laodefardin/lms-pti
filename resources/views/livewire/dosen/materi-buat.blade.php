<div class="fade-in">

    {{-- Breadcrumb --}}
    <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; color:var(--text-secondary); margin-bottom:1.25rem;">
        <a href="{{ route('dosen.matakuliah.index') }}" style="color:var(--text-secondary); text-decoration:none;" onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='var(--text-secondary)'">Matakuliah</a>
        <span>/</span>
        <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" style="color:var(--text-secondary); text-decoration:none;" onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='var(--text-secondary)'">{{ $kelas->mataKuliah->nama }}</a>
        <span>/</span>
        <span style="color:var(--text-primary);">Tambah Materi</span>
    </div>

    <div style="display:grid; grid-template-columns:1fr 340px; gap:1.25rem; align-items:start;">

        {{-- Main Form --}}
        <div>
            <div class="card" style="margin-bottom:1.25rem;">
                <div class="section-title" style="margin-bottom:1.25rem;">📝 Informasi Materi</div>

                {{-- Judul --}}
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Judul Materi *</label>
                    <input wire:model="judul" type="text" class="form-input" placeholder="Contoh: Pengenalan HTML dan CSS">
                    @error('judul') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                {{-- Tipe --}}
                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Tipe Konten *</label>
                    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:0.5rem;">
                        @foreach([
                            ['val'=>'artikel', 'icon'=>'📝', 'label'=>'Artikel'],
                            ['val'=>'video',   'icon'=>'🎬', 'label'=>'Video'],
                            ['val'=>'pdf',     'icon'=>'📄', 'label'=>'PDF'],
                            ['val'=>'kode',    'icon'=>'💻', 'label'=>'Kode'],
                            ['val'=>'link',    'icon'=>'🔗', 'label'=>'Link'],
                        ] as $t)
                        <button type="button" wire:click="$set('tipe','{{ $t['val'] }}')"
                                style="padding:0.6rem; border-radius:10px; border:1.5px solid {{ $tipe === $t['val'] ? 'var(--teal)' : 'var(--border)' }}; background:{{ $tipe === $t['val'] ? 'var(--teal-dim)' : 'var(--input-bg)' }}; cursor:pointer; transition:all 0.15s; display:flex; flex-direction:column; align-items:center; gap:0.25rem;">
                            <span style="font-size:1.2rem;">{{ $t['icon'] }}</span>
                            <span style="font-size:0.68rem; font-weight:600; color:{{ $tipe === $t['val'] ? 'var(--teal)' : 'var(--text-secondary)' }};">{{ $t['label'] }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Dynamic content area by type --}}
                @if($tipe === 'artikel')
                <div style="margin-bottom:1rem;" x-data="{ initEditor() {
                    if(typeof monaco !== 'undefined') return;
                    // Simple textarea fallback — Monaco loaded below
                }}">
                    <label class="form-label">Konten Artikel *</label>
                    {{-- Monaco Editor container --}}
                    <div id="artikel-editor" style="border:1.5px solid var(--input-border); border-radius:10px; overflow:hidden; height:400px;"></div>
                    {{-- Hidden textarea synced via JS --}}
                    <textarea id="artikel-hidden" wire:model="konten" style="display:none;"></textarea>
                    @error('konten') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                @elseif($tipe === 'kode')
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Kode Contoh / Template *</label>
                    <div id="kode-editor" style="border:1.5px solid var(--input-border); border-radius:10px; overflow:hidden; height:400px;"></div>
                    <textarea id="kode-hidden" wire:model="konten" style="display:none;"></textarea>
                    @error('konten') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                @elseif($tipe === 'video')
                <div style="margin-bottom:1rem;">
                    <label class="form-label">URL Video (YouTube / link langsung) *</label>
                    <div style="position:relative;">
                        <svg style="position:absolute;left:0.875rem;top:50%;transform:translateY(-50%);color:var(--text-muted);" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <input wire:model="url" type="url" class="form-input" placeholder="https://youtube.com/watch?v=..." style="padding-left:2.75rem;">
                    </div>
                    @error('url') <div class="form-error">{{ $message }}</div> @enderror
                    @if($url && Str::contains($url, ['youtube', 'youtu.be']))
                    <div style="margin-top:0.75rem; padding:0.75rem; background:var(--input-bg); border-radius:8px; font-size:0.8rem; color:var(--text-secondary);">
                        ✅ YouTube URL terdeteksi — video akan di-embed otomatis
                    </div>
                    @endif
                </div>

                @elseif($tipe === 'pdf')
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Upload File PDF *</label>
                    <div style="border:2px dashed var(--border); border-radius:12px; padding:2rem; text-align:center; transition:border-color 0.2s;"
                         wire:target="filePdf" wire:loading.class="opacity-50">
                        @if($filePdf)
                        <div style="color:var(--success); font-size:0.875rem; font-weight:600;">📄 {{ $filePdf->getClientOriginalName() }}</div>
                        <div style="font-size:0.72rem; color:var(--text-muted); margin-top:0.25rem;">{{ round($filePdf->getSize() / 1024, 1) }} KB</div>
                        @else
                        <div style="font-size:2rem; margin-bottom:0.5rem;">📤</div>
                        <div style="font-size:0.85rem; color:var(--text-secondary); margin-bottom:0.75rem;">Drag & drop atau klik untuk upload</div>
                        <label style="cursor:pointer; padding:0.4rem 1rem; background:var(--teal-dim); border:1px solid var(--border-teal); border-radius:8px; font-size:0.78rem; color:var(--teal); font-weight:600;">
                            Pilih File PDF
                            <input type="file" wire:model="filePdf" accept=".pdf" style="display:none;">
                        </label>
                        <div style="font-size:0.68rem; color:var(--text-muted); margin-top:0.5rem;">Maks. 20MB</div>
                        @endif
                        <div wire:loading wire:target="filePdf" style="font-size:0.78rem; color:var(--teal); margin-top:0.5rem;">⏳ Mengupload...</div>
                    </div>
                    @error('filePdf') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                @elseif($tipe === 'link')
                <div style="margin-bottom:1rem;">
                    <label class="form-label">URL Link Eksternal *</label>
                    <input wire:model="url" type="url" class="form-input" placeholder="https://example.com/resource">
                    @error('url') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                @endif
            </div>

            {{-- Pertemuan --}}
            <div class="card">
                <div class="section-title" style="margin-bottom:1.25rem;">📅 Pertemuan</div>

                <div style="margin-bottom:1rem;">
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-size:0.85rem; color:var(--text-primary); margin-bottom:0.75rem;">
                        <input type="checkbox" wire:model.live="buatPertemuanBaru" style="accent-color:var(--teal);">
                        Buat pertemuan baru
                    </label>

                    @if($buatPertemuanBaru)
                    <div style="display:grid; grid-template-columns:1fr auto; gap:0.875rem;">
                        <div>
                            <label class="form-label">Topik Pertemuan *</label>
                            <input wire:model="topik" type="text" class="form-input" placeholder="Contoh: Pengenalan HTML">
                            @error('topik') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">Tanggal</label>
                            <input wire:model="tanggal" type="date" class="form-input">
                        </div>
                    </div>
                    @else
                    <div>
                        <label class="form-label">Pilih Pertemuan</label>
                        <select wire:model="pertemuanId" class="form-input">
                            <option value="">-- Pilih Pertemuan --</option>
                            @foreach($pertemuanList as $p)
                            <option value="{{ $p->id }}">P{{ $p->nomor }} — {{ $p->topik }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Settings sidebar --}}
        <div style="position:sticky; top:1rem;">
            <div class="card" style="margin-bottom:1rem;">
                <div class="section-title" style="margin-bottom:1rem;">⚙️ Pengaturan</div>

                <div style="margin-bottom:0.875rem;">
                    <label class="form-label">Estimasi Waktu (menit)</label>
                    <input wire:model="estimasiMenit" type="number" class="form-input" placeholder="30" min="1" max="600">
                </div>

                <div style="margin-bottom:0.875rem;">
                    <label class="form-label">Urutan Tampil</label>
                    <input wire:model="urutan" type="number" class="form-input" min="1">
                </div>

                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; padding:0.75rem; background:var(--input-bg); border-radius:8px; border:1px solid var(--border);">
                    <input type="checkbox" wire:model="isPublished" style="accent-color:var(--teal); width:16px; height:16px;">
                    <div>
                        <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary);">Publish Sekarang</div>
                        <div style="font-size:0.7rem; color:var(--text-muted);">Mahasiswa langsung bisa melihat</div>
                    </div>
                </label>
            </div>

            <div class="card" style="background:var(--teal-dim); border-color:var(--border-teal);">
                <div style="font-size:0.75rem; color:var(--text-secondary); margin-bottom:0.75rem;">📚 Ringkasan:</div>
                <div style="font-size:0.82rem; color:var(--text-primary); line-height:1.7;">
                    <div>Kelas: <strong>{{ $kelas->mataKuliah->nama }}</strong></div>
                    <div>Tipe: <strong>{{ ucfirst($tipe) }}</strong></div>
                    <div>Status: <strong>{{ $isPublished ? 'Dipublish' : 'Draft' }}</strong></div>
                </div>
            </div>

            <div style="display:flex; gap:0.5rem; margin-top:1rem;">
                <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" class="btn btn-ghost btn-sm" style="flex:1; justify-content:center;">Batal</a>
                <button wire:click="save" class="btn btn-primary" style="flex:2;">
                    <span wire:loading.remove wire:target="save">💾 Simpan Materi</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Monaco Editor --}}
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');

    function initMonaco(containerId, hiddenId, language) {
        const container = document.getElementById(containerId);
        if (!container) return;

        require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function() {
            const hidden = document.getElementById(hiddenId);
            const editor = monaco.editor.create(container, {
                value: hidden.value || '',
                language: language,
                theme: isDark ? 'vs-dark' : 'vs',
                automaticLayout: true,
                minimap: { enabled: false },
                fontSize: 14,
                lineHeight: 22,
                wordWrap: 'on',
                scrollBeyondLastLine: false,
                padding: { top: 12 },
            });

            // Sync editor content to Livewire via hidden textarea
            editor.onDidChangeModelContent(() => {
                hidden.value = editor.getValue();
                hidden.dispatchEvent(new Event('input'));
            });

            // Update theme when toggled
            document.querySelector('#theme-toggle-btn')?.addEventListener('click', () => {
                const darkNow = document.documentElement.classList.contains('dark');
                monaco.editor.setTheme(darkNow ? 'vs-dark' : 'vs');
            });
        });
    }

    // Init editor based on current tipe
    @if($tipe === 'artikel')
        initMonaco('artikel-editor', 'artikel-hidden', 'html');
    @elseif($tipe === 'kode')
        initMonaco('kode-editor', 'kode-hidden', 'javascript');
    @endif
});
</script>
