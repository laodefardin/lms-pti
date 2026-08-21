<div class="w-full px-2 xl:px-4 space-y-6 pb-12 fade-in">
    {{-- Header & Breadcrumb --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm inline-block transition mb-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Kelas
            </a>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Tambah Materi Baru</h1>
            <p class="text-[var(--text-secondary)] mt-1">Matakuliah: <span class="font-semibold">{{ $kelas->mataKuliah->nama ?? '' }}</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- Main Form (Col 1 & 2) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6 shadow-sm border border-[var(--border)]">
                <div class="flex items-center gap-3 mb-6 border-b border-[var(--border)] pb-4">
                    <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h2 class="text-lg font-bold text-[var(--text-primary)]">Informasi Konten Materi</h2>
                </div>

                <div class="space-y-6">
                    {{-- Judul --}}
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Judul Materi <span class="text-red-500">*</span></label>
                        <input wire:model="judul" type="text" class="form-input w-full" placeholder="Contoh: Pengenalan HTML dan CSS">
                        @error('judul') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tipe --}}
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Tipe Konten <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            @foreach([
                                ['val'=>'artikel', 'icon'=>'fas fa-edit', 'label'=>'Artikel'],
                                ['val'=>'video',   'icon'=>'fas fa-play-circle', 'label'=>'Video'],
                                ['val'=>'pdf',     'icon'=>'fas fa-file-pdf', 'label'=>'PDF Document'],
                                ['val'=>'kode',    'icon'=>'fas fa-laptop-code', 'label'=>'Kode Snippet'],
                                ['val'=>'link',    'icon'=>'fas fa-link', 'label'=>'Link Eksternal'],
                            ] as $t)
                            <button type="button" wire:key="btn-tipe-{{ $t['val'] }}" @click="$wire.setTipe('{{ $t['val'] }}')"
                                    class="flex flex-col items-center justify-center p-3 rounded-xl border-2 transition-all duration-200 {{ $tipe === $t['val'] ? 'border-teal-500 bg-[var(--teal-dim)] text-[var(--teal)]' : 'border-[var(--border)] bg-[var(--input-bg)] text-[var(--text-muted)] hover:border-teal-200 hover:bg-[var(--bg-card-hover)]' }}">
                                <i class="{{ $t['icon'] }} text-2xl mb-2 {{ $tipe === $t['val'] ? 'text-teal-600' : 'text-[var(--text-muted)]' }}"></i>
                                <span class="text-xs font-bold">{{ $t['label'] }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Dynamic content area by type --}}
                    <div class="bg-[var(--input-bg)] rounded-xl p-5 border border-[var(--border)]">
                        @if($tipe === 'artikel')
                        <div wire:key="type-artikel" x-data="{}" x-init="window.initCKEditor('ckeditor-buat', 'ckeditor-hidden-buat')">
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Konten Artikel <span class="text-red-500">*</span></label>
                            <div wire:ignore>
                                <div id="ckeditor-buat" class="prose-editor-container">{{ $konten }}</div>
                            </div>
                            <input type="hidden" id="ckeditor-hidden-buat" wire:model="konten">
                            @error('konten') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @elseif($tipe === 'kode')
                        <div wire:key="type-kode" x-data="{
                            init() {
                                if (window.initMonaco) {
                                    window.initMonaco('kode-editor', 'kode-hidden', 'javascript');
                                } else {
                                    setTimeout(() => this.init(), 200);
                                }
                            }
                        }">
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Kode Contoh / Template <span class="text-red-500">*</span></label>
                            <div wire:ignore>
                                <div id="kode-editor" class="border border-[var(--border)] rounded-lg overflow-hidden h-[400px] shadow-sm"></div>
                            </div>
                            <textarea id="kode-hidden" wire:model="konten" style="display:none;"></textarea>
                            @error('konten') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @elseif($tipe === 'video')
                        <div wire:key="type-video">
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">URL Video (YouTube / Link Langsung) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fab fa-youtube text-[var(--text-muted)]"></i>
                                </div>
                                <input wire:model="url" type="url" class="form-input w-full pl-10" placeholder="https://youtube.com/watch?v=...">
                            </div>
                            @error('url') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            
                            @if($url && Str::contains($url, ['youtube', 'youtu.be']))
                            <div class="mt-3 p-3 bg-green-50 rounded-lg text-sm text-green-700 border border-green-100 flex items-start gap-2">
                                <i class="fas fa-check-circle mt-0.5 text-green-500"></i>
                                <span>YouTube URL terdeteksi! Video akan di-embed (ditampilkan langsung) di halaman materi mahasiswa.</span>
                            </div>
                            @endif
                        </div>

                        @elseif($tipe === 'pdf')
                        <div wire:key="type-pdf">
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Upload File PDF <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-[var(--border)] rounded-xl p-8 text-center transition-colors hover:bg-[var(--bg-card-hover)] hover:border-teal-400"
                                 wire:target="filePdf" wire:loading.class="opacity-50 bg-[var(--input-bg)]">
                                @if($filePdf)
                                    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 text-3xl mx-auto mb-3 shadow-sm border border-red-100">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="text-teal-700 font-bold mb-1 truncate px-4">{{ $filePdf->getClientOriginalName() }}</div>
                                    <div class="text-xs text-[var(--text-muted)] font-medium bg-[var(--input-bg)] inline-block px-3 py-1 rounded-full">{{ round($filePdf->getSize() / 1024, 1) }} KB</div>
                                    <div class="mt-4">
                                        <label class="cursor-pointer text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                            Ganti File
                                            <input type="file" wire:model="filePdf" accept=".pdf" class="hidden">
                                        </label>
                                    </div>
                                @else
                                    <div class="w-16 h-16 bg-[var(--input-bg)] rounded-2xl flex items-center justify-center text-[var(--text-muted)] text-3xl mx-auto mb-3 shadow-inner">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="text-[var(--text-muted)] text-sm mb-4">Tarik file ke sini atau klik tombol di bawah untuk memilih file</div>
                                    <label class="cursor-pointer inline-flex items-center px-4 py-2 bg-[var(--bg-card)] border border-[var(--border)] rounded-lg font-semibold text-sm text-[var(--text-secondary)] shadow-sm hover:bg-[var(--bg-card-hover)] focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all">
                                        <i class="fas fa-folder-open mr-2 text-teal-600"></i> Pilih File PDF
                                        <input type="file" wire:model="filePdf" accept=".pdf" class="hidden">
                                    </label>
                                    <div class="text-xs text-[var(--text-muted)] mt-3 font-medium">Maksimal Ukuran: 20MB</div>
                                @endif
                                <div wire:loading wire:target="filePdf" class="mt-4 text-sm font-semibold text-teal-600">
                                    <i class="fas fa-circle-notch fa-spin mr-2"></i> Sedang mengupload file...
                                </div>
                            </div>
                            @error('filePdf') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @elseif($tipe === 'link')
                        <div wire:key="type-link">
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">URL Link Eksternal <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-link text-[var(--text-muted)]"></i>
                                </div>
                                <input wire:model="url" type="url" class="form-input w-full pl-10" placeholder="https://example.com/resource">
                            </div>
                            @error('url') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Settings Sidebar --}}
        <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-6">
            {{-- Penempatan Pertemuan --}}
            <div class="card p-6 shadow-sm border border-[var(--border)]">
                <div class="flex items-center gap-3 mb-5 border-b border-[var(--border)] pb-3">
                    <div class="w-8 h-8 rounded-md bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <h2 class="text-md font-bold text-[var(--text-primary)]">Penempatan Sesi</h2>
                </div>

                <div class="space-y-4">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-[var(--border)] hover:bg-[var(--bg-card-hover)] cursor-pointer transition-colors">
                        <input type="checkbox" wire:model.live="buatPertemuanBaru" class="mt-1 w-4 h-4 text-teal-600 rounded border-[var(--border)] focus:ring-teal-500">
                        <div class="text-sm font-semibold text-[var(--text-primary)]">Buat Sesi Baru</div>
                    </label>

                    @if($buatPertemuanBaru)
                    <div class="space-y-3 p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                        <div>
                            <label class="block text-xs font-medium text-[var(--text-secondary)] mb-1">Topik Pertemuan Baru <span class="text-red-500">*</span></label>
                            <input wire:model="topik" type="text" class="form-input w-full text-sm" placeholder="Contoh: Pengenalan HTML">
                            @error('topik') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[var(--text-secondary)] mb-1">Tanggal</label>
                            <input wire:model="tanggal" type="date" class="form-input w-full text-sm">
                        </div>
                    </div>
                    @else
                    <div>
                        <label class="block text-xs font-medium text-[var(--text-secondary)] mb-1">Pilih Sesi / Pertemuan</label>
                        <select wire:model="pertemuanId" class="form-input w-full text-sm">
                            <option value="">-- Pilih Sesi --</option>
                            @foreach($pertemuanList as $p)
                                <option value="{{ $p->id }}">Pertemuan {{ $p->nomor }} — {{ $p->topik }}</option>
                            @endforeach
                        </select>
                        @error('pertemuanId') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>
            </div>

            <div class="card p-6 shadow-sm border border-[var(--border)]">
                <div class="flex items-center gap-3 mb-5 border-b border-[var(--border)] pb-3">
                    <div class="w-8 h-8 rounded-md bg-[var(--input-bg)] text-[var(--text-secondary)] flex items-center justify-center shrink-0">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h2 class="text-md font-bold text-[var(--text-primary)]">Pengaturan Akses</h2>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Estimasi Waktu Belajar (menit)</label>
                        <div class="relative">
                            <input wire:model="estimasiMenit" type="number" class="form-input w-full pr-12" placeholder="30" min="1" max="600">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-[var(--text-muted)] text-sm">Menit</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Urutan Tampil (Opsional)</label>
                        <input wire:model="urutan" type="number" class="form-input w-full" min="1">
                        <p class="text-xs text-[var(--text-muted)] mt-1">Urutan materi di dalam pertemuan ini.</p>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-start gap-3 p-4 rounded-xl border border-[var(--border)] hover:border-teal-300 hover:bg-teal-50/30 cursor-pointer transition-all">
                            <input type="checkbox" wire:model="isPublished" class="mt-1 w-5 h-5 text-teal-600 rounded border-[var(--border)] focus:ring-teal-500">
                            <div>
                                <div class="text-sm font-bold text-[var(--text-primary)]">Publish Sekarang</div>
                                <div class="text-xs text-[var(--text-muted)] mt-0.5">Jika dicentang, mahasiswa akan langsung bisa mengakses materi ini.</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button wire:click="save" class="btn btn-primary w-full shadow-lg shadow-teal-500/30 py-3 text-sm flex justify-center items-center">
                    <span wire:loading.remove wire:target="save"><i class="fas fa-save mr-2"></i> Simpan Konten Materi</span>
                    <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin mr-2"></i> Sedang Menyimpan...</span>
                </button>
                <a href="{{ route('dosen.matakuliah.detail', $kelas) }}" class="btn btn-outline w-full py-3 text-sm justify-center bg-[var(--bg-card)] border-[var(--border)] text-[var(--text-secondary)] hover:bg-[var(--bg-card-hover)]">
                    Batal & Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- CKEditor 5 (Artikel) + Monaco (Kode) --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <style>
        .ck-editor__editable {
           min-height: 420px !important;
           max-height: 600px !important;
           overflow-y: auto !important;
           font-size: 15px;
           line-height: 1.75;
           background: var(--bg-card) !important;
           color: var(--text-primary) !important;
           scroll-behavior: smooth;
       }
       .ck-editor__editable img { max-width: 100%; border-radius: 8px; }
       .ck-editor__editable blockquote {
           border-left: 4px solid #14b8a6;
           padding: 10px 20px;
           background: rgba(20,184,166,0.1);
           border-radius: 0 8px 8px 0;
           color: #14b8a6;
       }
       .ck.ck-editor { border-radius: 12px !important; overflow: hidden; border: 1px solid var(--border) !important; }
       .ck.ck-toolbar { border-bottom: 1px solid var(--border) !important; background: var(--input-bg) !important; padding: 4px 8px !important; }
       .ck.ck-toolbar .ck-button { color: var(--text-secondary) !important; }
       .ck.ck-toolbar .ck-button:hover, .ck.ck-toolbar .ck-button.ck-on { background: var(--teal-dim) !important; color: var(--teal) !important; }
       .ck.ck-toolbar__separator { background: var(--border) !important; }
       .ck.ck-dropdown__panel { background: var(--bg-card) !important; border: 1px solid var(--border) !important; border-radius: 8px !important; }
       .ck.ck-list { background: var(--bg-card) !important; }
       .ck.ck-list__item .ck-button { color: var(--text-primary) !important; }
       .ck.ck-list__item .ck-button:hover { background: var(--teal-dim) !important; }
       .ck.ck-list__item .ck-button.ck-on { background: var(--teal-dim) !important; color: var(--teal) !important; }
       .ck.ck-balloon-panel { background: var(--bg-card) !important; border: 1px solid var(--border) !important; }
       .ck.ck-word-count { background: var(--input-bg) !important; color: var(--text-muted) !important; border-top: 1px solid var(--border) !important; }
        .ck-content pre { background: #1e293b !important; color: #e2e8f0 !important; border-radius: 8px !important; padding: 16px !important; }
        .prose-editor-container { min-height: 200px; }
    </style>
    <script>
    (function() {
        // ── Base64 Image Upload Adapter ──────────────────────────
        function Base64UploadAdapter(loader) { this.loader = loader; }
        Base64UploadAdapter.prototype.upload = function() {
            var loader = this.loader;
            return loader.file.then(function(file) {
                return new Promise(function(resolve, reject) {
                    var reader = new FileReader();
                    reader.onload  = function() { resolve({ default: reader.result }); };
                    reader.onerror = function(err) { reject(err); };
                    reader.readAsDataURL(file);
                });
            });
        };
        Base64UploadAdapter.prototype.abort = function() {};
        function Base64UploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                return new Base64UploadAdapter(loader);
            };
        }

        // ── CKEditor 5 for Artikel ───────────────────────────────
        var _ckInstances = {};

        window.initCKEditor = function(containerId, hiddenId) {
            // Destroy previous instance on same element
            if (_ckInstances[containerId]) {
                _ckInstances[containerId].destroy().catch(function(){});
                delete _ckInstances[containerId];
            }

            const container = document.getElementById(containerId);
            if (!container) return;

            ClassicEditor.create(container, {
                extraPlugins: [Base64UploadAdapterPlugin],
                toolbar: {
                    items: [
                        'heading', '|',
                        'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'alignment', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'link', 'insertImage', 'mediaEmbed', 'insertTable', 'blockQuote', 'code', 'codeBlock', '|',
                        'horizontalLine', 'pageBreak', '|',
                        'undo', 'redo'
                    ],
                    shouldNotGroupWhenFull: false
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    ]
                },
                fontFamily: {
                    options: [
                        'default',
                        'Inter, sans-serif',
                        'Poppins, sans-serif',
                        'Arial, Helvetica, sans-serif',
                        'Georgia, serif',
                        'Times New Roman, Times, serif',
                        'Courier New, Courier, monospace',
                        'Verdana, Geneva, sans-serif',
                    ],
                    supportAllValues: true
                },
                fontSize: {
                    options: [11, 12, 13, 14, 'default', 16, 18, 20, 24, 28, 32, 36],
                    supportAllValues: true
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', 'toggleImageCaption', '|',
                        'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
                        'resizeImage'
                    ],
                    upload: {
                        types: ['jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff', 'svg+xml']
                    }
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
                },
                mediaEmbed: {
                    previewsInData: true
                },
                codeBlock: {
                    languages: [
                        { language: 'plaintext', label: 'Plain text' },
                        { language: 'javascript', label: 'JavaScript' },
                        { language: 'python', label: 'Python' },
                        { language: 'php', label: 'PHP' },
                        { language: 'html', label: 'HTML' },
                        { language: 'css', label: 'CSS' },
                        { language: 'sql', label: 'SQL' },
                        { language: 'bash', label: 'Bash' },
                    ]
                },
                language: 'en'
            }).then(function(editor) {
                _ckInstances[containerId] = editor;

                // Populate with existing content from hidden input
                const hidden = document.getElementById(hiddenId);
                if (hidden && hidden.value && hidden.value.trim()) {
                    editor.setData(hidden.value);
                }

                // Sync editor content to Livewire hidden input
                editor.model.document.on('change:data', function() {
                    if (hidden) {
                        hidden.value = editor.getData();
                        hidden.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                });
            }).catch(function(err) {
                console.error('CKEditor init error:', err);
            });
        };

        // ── Monaco for Kode Snippet ───────────────────────────────
        window.initMonaco = function(containerId, hiddenId, language) {
            const container = document.getElementById(containerId);
            if (!container) return;
            if (container.hasAttribute('data-monaco-initialized')) return;

            const loadMonaco = () => {
                if (typeof require === 'undefined') {
                    console.error('Monaco require is not defined even after loading script');
                    return;
                }
                
                container.setAttribute('data-monaco-initialized', 'true');
                const isDark = document.documentElement.classList.contains('dark');

                require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
                require(['vs/editor/editor.main'], function() {
                    const hidden = document.getElementById(hiddenId);
                    if (!hidden) return;
                    
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
                        hidden.dispatchEvent(new Event('input', { bubbles: true }));
                    });

                    // Update theme when toggled
                    window.addEventListener('theme-changed', (e) => {
                        monaco.editor.setTheme(e.detail.dark ? 'vs-dark' : 'vs');
                    });
                });
            };

            // Dynamically load Monaco loader if not present
            if (typeof require === 'undefined') {
                const scriptId = 'monaco-loader-script';
                if (!document.getElementById(scriptId)) {
                    const script = document.createElement('script');
                    script.id = scriptId;
                    script.src = 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js';
                    script.onload = loadMonaco;
                    document.head.appendChild(script);
                } else {
                    // Script is already loading, wait for it
                    document.getElementById(scriptId).addEventListener('load', loadMonaco);
                }
            } else {
                loadMonaco();
            }
        };
    })();
    </script>
</div>
