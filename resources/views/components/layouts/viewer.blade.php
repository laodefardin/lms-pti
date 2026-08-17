<!DOCTYPE html>
<html lang="id" x-init="$store.theme.init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $konten->judul ?? 'Materi' }} — {{ $kelas->mataKuliah->nama ?? 'LMS Pendidikan Teknologi Informasi' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        /* Override page-level scroll for viewer */
        html, body { height: 100%; overflow: hidden; }

        /* Monaco editor container */
        #monaco-editor { width: 100%; min-height: 400px; border-radius: 10px; overflow: hidden; border: 1px solid var(--border); }

        /* Video responsive */
        .video-wrap { position: relative; padding-bottom: 56.25%; height: 0; border-radius: 12px; overflow: hidden; }
        .video-wrap iframe, .video-wrap video { position: absolute; top:0; left:0; width:100%; height:100%; }

        /* Prose content (artikel) */
        .prose-lms { line-height: 1.8; color: var(--text-primary); }
        .prose-lms h1,.prose-lms h2,.prose-lms h3 { font-weight: 700; color: var(--text-primary); margin: 1.5rem 0 0.75rem; }
        .prose-lms p  { margin-bottom: 1rem; }
        .prose-lms ul,.prose-lms ol { padding-left: 1.5rem; margin-bottom: 1rem; }
        .prose-lms li { margin-bottom: 0.35rem; }
        .prose-lms pre { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 1rem; overflow-x: auto; margin-bottom: 1rem; }
        .prose-lms code { font-size: 0.85em; background: var(--input-bg); padding: 0.15em 0.4em; border-radius: 4px; }
        .prose-lms pre code { background: none; padding: 0; }
        .prose-lms a { color: var(--teal); text-decoration: underline; }
        .prose-lms blockquote { border-left: 3px solid var(--teal); padding-left: 1rem; color: var(--text-secondary); margin: 1rem 0; }
        .prose-lms img { max-width: 100%; border-radius: 8px; margin: 1rem 0; }
        .prose-lms table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        .prose-lms th, .prose-lms td { border: 1px solid var(--border); padding: 0.5rem 0.75rem; font-size: 0.875rem; }
        .prose-lms th { background: var(--input-bg); font-weight: 600; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

{{-- ═══════════════════════════ VIEWER SHELL ═══════════════════════════ --}}
<div x-data="{ sidebarOpen: true, notesOpen: true }" style="display:flex; flex-direction:column; height:100vh;">

    {{-- ── TOP NAVIGATION BAR ─────────────────────────────────────── --}}
    <nav style="height:52px; background:var(--bg-topbar); border-bottom:1px solid var(--border); backdrop-filter:blur(16px); display:flex; align-items:center; justify-content:space-between; padding:0 1rem; flex-shrink:0; z-index:30;">

        {{-- Left: back + breadcrumb --}}
        <div style="display:flex; align-items:center; gap:0.75rem; min-width:0;">
            <a href="{{ route('mahasiswa.matakuliah.detail', $kelas) }}"
               style="display:flex; align-items:center; gap:0.4rem; color:var(--text-secondary); text-decoration:none; font-size:0.78rem; white-space:nowrap; transition:color 0.2s; flex-shrink:0;"
               onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='var(--text-secondary)'">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            <span style="color:var(--border); font-size:0.8rem;">|</span>
            <div style="min-width:0;">
                <div style="font-size:0.72rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $kelas->mataKuliah->nama }}</div>
                <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $konten->judul }}</div>
            </div>
        </div>

        {{-- Center: prev / next nav --}}
        <div style="display:flex; align-items:center; gap:0.5rem; flex-shrink:0;">
            @if($sebelumnya)
            <a href="{{ route('mahasiswa.materi.viewer', [$kelas->slug, $sebelumnya]) }}" class="btn btn-ghost btn-sm" title="Materi sebelumnya">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg> Prev
            </a>
            @endif
            @if($berikutnya)
            <a href="{{ route('mahasiswa.materi.viewer', [$kelas->slug, $berikutnya]) }}" class="btn btn-primary btn-sm" title="Materi berikutnya">
                Next <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif
        </div>

        {{-- Right: toggles + theme --}}
        <div style="display:flex; align-items:center; gap:0.5rem; flex-shrink:0;">
            {{-- Sidebar toggle --}}
            <button @click="sidebarOpen = !sidebarOpen" class="theme-toggle" title="Toggle daftar materi">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
            </button>

            {{-- Notes toggle --}}
            <button @click="notesOpen = !notesOpen" class="theme-toggle" title="Toggle catatan">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>

            {{-- Theme toggle --}}
            @include('components.theme-toggle')
        </div>
    </nav>

    {{-- ── 3-PANEL BODY ─────────────────────────────────────────────── --}}
    <div style="display:flex; flex:1; overflow:hidden;">

        {{-- LEFT PANEL — Course Outline ──────────────────────────────── --}}
        <div :style="sidebarOpen ? 'width:288px;min-width:288px;' : 'width:0;min-width:0;'"
             style="background:var(--bg-sidebar); border-right:1px solid var(--border); display:flex; flex-direction:column; overflow:hidden; transition:width 0.3s ease, min-width 0.3s ease;">

            {{-- Panel header --}}
            <div style="padding:0.875rem 0.875rem 0.6rem; border-bottom:1px solid var(--border); flex-shrink:0;">
                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.35rem;">Daftar Materi</div>

                {{-- Overall progress --}}
                @php
                    $totalKonten  = $kelas->pertemuan->flatMap->konten->where('is_published', true)->count();
                    $selesaiCount = count($selesaiIds);
                    $pctGlobal    = $totalKonten > 0 ? round($selesaiCount / $totalKonten * 100) : 0;
                @endphp
                <div style="display:flex; justify-content:space-between; margin-bottom:0.3rem;">
                    <span style="font-size:0.68rem; color:var(--text-muted);">{{ $selesaiCount }}/{{ $totalKonten }} selesai</span>
                    <span style="font-size:0.68rem; color:var(--teal); font-weight:600;">{{ $pctGlobal }}%</span>
                </div>
                <div class="progress-wrap"><div class="progress-bar" style="width:{{ $pctGlobal }}%;"></div></div>
            </div>

            {{-- Scrollable outline --}}
            <div style="flex:1; overflow-y:auto; padding:0.5rem;">
                @foreach($kelas->pertemuan as $pertemuan)
                <div x-data="{ open: {{ $konten->pertemuan_id === $pertemuan->id ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:0.5rem 0.6rem; border-radius:7px; background:none; border:none; cursor:pointer; text-align:left; transition:background 0.15s;"
                            onmouseover="this.style.background='var(--teal-dim)'" onmouseout="this.style.background='transparent'">
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <span style="font-size:0.68rem; font-weight:700; color:var(--teal); background:var(--teal-dim); border-radius:4px; padding:0.1rem 0.35rem; flex-shrink:0;">P{{ $pertemuan->nomor }}</span>
                            <span style="font-size:0.76rem; font-weight:600; color:var(--text-primary); line-height:1.3;">{{ Str::limit($pertemuan->topik, 30) }}</span>
                        </div>
                        <svg :style="open ? 'transform:rotate(180deg)' : ''" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0; transition:transform 0.2s; color:var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" x-transition style="padding:0.1rem 0 0.25rem 0.75rem;">
                        @foreach($pertemuan->konten->where('is_published', true) as $k)
                        <a href="{{ route('mahasiswa.materi.viewer', [$kelas->slug, $k]) }}"
                           class="viewer-item {{ $k->id === $konten->id ? 'active' : '' }}"
                           style="{{ $k->id === $konten->id ? '' : '' }}">
                            <div class="viewer-item-icon {{ in_array($k->id, $selesaiIds) ? 'done' : '' }}">
                                @if(in_array($k->id, $selesaiIds))
                                    <span style="color:var(--success);">✓</span>
                                @else
                                    {{ $k->ikon }}
                                @endif
                            </div>
                            <span style="flex:1; line-height:1.35;">{{ Str::limit($k->judul, 32) }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CENTER PANEL — Content ────────────────────────────────────── --}}
        <div style="flex:1; overflow-y:auto; background:var(--bg-main); display:flex; flex-direction:column;">

            {{-- Content area --}}
            <div style="flex:1; padding:2rem; max-width:820px; margin:0 auto; width:100%;">

                {{-- Content header --}}
                <div style="margin-bottom:1.5rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.6rem;">
                        <span style="font-size:1.1rem;">{{ $konten->ikon }}</span>
                        <span class="badge badge-gray">{{ ucfirst($konten->tipe) }}</span>
                        @if($konten->estimasi_menit)
                        <span class="badge badge-gray"><i class="fas fa-clock"></i> {{ $konten->estimasi_menit }} mnt</span>
                        @endif
                        @if($isSelesai)
                        <span class="badge badge-green"><i class="fas fa-check-circle"></i> Selesai</span>
                        @endif
                    </div>
                    <h1 style="font-size:1.5rem; font-weight:800; color:var(--text-primary); line-height:1.3; margin-bottom:0.5rem;">{{ $konten->judul }}</h1>
                </div>

                {{-- ── Content by type ──────────────────────────── --}}

                @if($konten->tipe === 'video')
                    {{-- VIDEO --}}
                    <div style="margin-bottom:1.5rem;">
                        @if(Str::contains($konten->url ?? '', ['youtube.com', 'youtu.be']))
                        @php
                            preg_match('/(?:v=|youtu\.be\/)([^&\s]+)/', $konten->url, $m);
                            $ytId = $m[1] ?? '';
                        @endphp
                        <div class="video-wrap" style="border-radius:12px; overflow:hidden; box-shadow:var(--shadow-card);">
                            <iframe src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                        @elseif($konten->file_path)
                        <video controls style="width:100%; border-radius:12px;">
                            <source src="{{ asset('storage/'.$konten->file_path) }}">
                        </video>
                        @endif
                    </div>

                @elseif($konten->tipe === 'pdf')
                    {{-- PDF --}}
                    <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden; height:600px; margin-bottom:1.5rem; box-shadow:var(--shadow-card);">
                        @if($konten->file_path)
                        <iframe src="{{ asset('storage/'.$konten->file_path) }}" style="width:100%; height:100%; border:none;"></iframe>
                        @else
                        <div style="display:flex; align-items:center; justify-content:center; height:100%; color:var(--text-muted);">File PDF tidak tersedia</div>
                        @endif
                    </div>

                @elseif($konten->tipe === 'artikel')
                    {{-- ARTIKEL --}}
                    <div class="prose-lms" style="margin-bottom:1.5rem;">
                        {!! $konten->konten !!}
                    </div>

                @elseif($konten->tipe === 'kode')
                    {{-- CODE EDITOR (Monaco) --}}
                    <div style="margin-bottom:1.5rem;">
                        <div id="monaco-editor" style="height:480px;"></div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            // Monaco akan dimuat via CDN jika tersedia
                            if (typeof monaco !== 'undefined') {
                                monaco.editor.create(document.getElementById('monaco-editor'), {
                                    value: {!! json_encode($konten->konten ?? '// Kode akan dimuat di sini') !!},
                                    language: 'javascript',
                                    theme: document.documentElement.classList.contains('dark') ? 'vs-dark' : 'vs',
                                    automaticLayout: true,
                                    minimap: { enabled: false },
                                    fontSize: 14,
                                    lineHeight: 22,
                                    readOnly: false,
                                });
                            }
                        });
                    </script>

                @elseif($konten->tipe === 'link')
                    {{-- EXTERNAL LINK --}}
                    <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:1.5rem; text-align:center; margin-bottom:1.5rem;">
                        <div style="font-size:2.5rem; margin-bottom:0.75rem;">🔗</div>
                        <p style="color:var(--text-secondary); margin-bottom:1rem; font-size:0.9rem;">Materi ini ada di link eksternal:</p>
                        <a href="{{ $konten->url }}" target="_blank" class="btn btn-primary">
                            Buka Link →
                        </a>
                    </div>
                @endif

                {{-- Deskripsi tambahan --}}
                @if($konten->konten && !in_array($konten->tipe, ['artikel','kode']))
                <div class="card" style="margin-bottom:1.5rem;">
                    <div style="font-size:0.8rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.75rem;"><i class="fas fa-clipboard-list"></i> Keterangan</div>
                    <div class="prose-lms" style="font-size:0.875rem;">{!! nl2br(e($konten->konten)) !!}</div>
                </div>
                @endif

                {{-- Mark Selesai Button --}}
                <div style="padding:1.25rem; background:var(--bg-card); border:1px solid var(--border); border-radius:12px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                    <div>
                        @if($isSelesai)
                        <div style="color:var(--success); font-weight:600; font-size:0.9rem;"><i class="fas fa-check-circle"></i> Kamu sudah menyelesaikan materi ini</div>
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">Progress tersimpan otomatis</div>
                        @else
                        <div style="color:var(--text-primary); font-weight:600; font-size:0.9rem;">Sudah selesai membaca?</div>
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">Tandai selesai untuk melacak progress</div>
                        @endif
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        @if(!$isSelesai)
                        <button wire:click="markSelesai" class="btn btn-primary">
                            <svg wire:loading.remove wire:target="markSelesai" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <svg wire:loading wire:target="markSelesai" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite;"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                            Tandai Selesai
                        </button>
                        @endif
                        @if($berikutnya)
                        <a href="{{ route('mahasiswa.materi.viewer', [$kelas->slug, $berikutnya]) }}" class="btn btn-outline">
                            Materi Berikutnya →
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL — Catatan ─────────────────────────────────────── --}}
        <div :style="notesOpen ? 'width:268px;min-width:268px;' : 'width:0;min-width:0;'"
             style="background:var(--bg-sidebar); border-left:1px solid var(--border); display:flex; flex-direction:column; overflow:hidden; transition:width 0.3s ease, min-width 0.3s ease;">

            <div style="padding:0.875rem; border-bottom:1px solid var(--border); flex-shrink:0;">
                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.08em;"><i class="fas fa-edit"></i> Catatan Saya</div>
            </div>

            <div style="flex:1; padding:0.875rem; display:flex; flex-direction:column; overflow-y:auto;">
                <textarea wire:model.lazy="catatan" placeholder="Tulis catatan untuk materi ini..."
                          style="flex:1; min-height:200px; width:100%; background:var(--input-bg); border:1.5px solid var(--input-border); border-radius:10px; padding:0.75rem; color:var(--text-primary); font-size:0.8rem; font-family:'Inter',sans-serif; resize:none; outline:none; transition:border-color 0.2s; line-height:1.6;"
                          onfocus="this.style.borderColor='var(--teal)'" onblur="this.style.borderColor='var(--input-border)'"></textarea>

                <button wire:click="simpanCatatan" class="btn btn-primary btn-sm btn-full" style="margin-top:0.75rem;">
                    <svg wire:loading.remove wire:target="simpanCatatan" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    <span wire:loading.remove wire:target="simpanCatatan">Simpan Catatan</span>
                    <span wire:loading wire:target="simpanCatatan">Menyimpan...</span>
                </button>

                @if($saved)
                <div style="text-align:center; font-size:0.72rem; color:var(--success); margin-top:0.5rem;" x-data x-init="setTimeout(() => $el.remove(), 3000)">
                    <i class="fas fa-check-circle"></i> Catatan tersimpan!
                </div>
                @endif

                <div style="margin-top:1.25rem; padding-top:1rem; border-top:1px solid var(--border);">
                    <div style="font-size:0.7rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.6rem;">Info Materi</div>
                    <div style="font-size:0.75rem; color:var(--text-secondary); line-height:1.7;">
                        <div>📁 Tipe: <span style="color:var(--text-primary); font-weight:500;">{{ ucfirst($konten->tipe) }}</span></div>
                        @if($konten->estimasi_menit)
                        <div><i class="fas fa-clock"></i> Estimasi: <span style="color:var(--text-primary); font-weight:500;">{{ $konten->estimasi_menit }} menit</span></div>
                        @endif
                        <div><i class="fas fa-calendar-alt"></i> Pertemuan: <span style="color:var(--text-primary); font-weight:500;">{{ $konten->pertemuan->nomor ?? '-' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@livewireScripts
<style>
@keyframes spin { from{transform:rotate(0deg);} to{transform:rotate(360deg);} }
</style>
</body>
</html>
