<div x-data="{ 
    sidebarOpen: window.innerWidth > 768, 
    notesOpen: window.innerWidth > 768, 
    scrollProgress: 0,
    searchModalOpen: false,
    searchQuery: '',
    isSearching: false,
    searchTimeout: null,
    courseModules: @js($kelas->pertemuan->flatMap(fn($p) => $p->konten->where('is_published', true)->map(fn($k) => ['title' => $k->judul, 'pertemuan' => $p->judul, 'search_text' => strtolower($p->judul . ' ' . $k->judul), 'url' => route('mahasiswa.materi.viewer', [$kelas->slug, $k->slug])])->values())->toArray())
}" 
x-init="
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
    $watch('searchQuery', value => { 
        if(value.length >= 3) {
            isSearching = true; 
            clearTimeout(searchTimeout); 
            searchTimeout = setTimeout(() => { isSearching = false; }, 400); 
        } else {
            isSearching = false;
        }
    });
    return () => { 
        document.body.style.overflow = ''; 
        document.documentElement.style.overflow = '';
    }
"
@resize.window="if(window.innerWidth > 768) { sidebarOpen = true; notesOpen = true; } else { sidebarOpen = false; notesOpen = false; }" style="display:flex; flex-direction:column; height:100vh;">

    {{-- ── TOP NAVIGATION BAR ─────────────────────────────────────── --}}
    <nav style="height:52px; background:var(--bg-topbar); border-bottom:1px solid var(--border); backdrop-filter:blur(16px); display:flex; align-items:center; justify-content:space-between; padding:0 1rem; flex-shrink:0; z-index:60;">

        {{-- Left: back + breadcrumb --}}
        <div style="display:flex; align-items:center; gap:0.75rem; min-width:0;">
            <a href="{{ route('mahasiswa.matakuliah.detail', $kelas) }}" wire:navigate
               style="display:flex; align-items:center; gap:0.4rem; color:var(--text-secondary); text-decoration:none; font-size:0.78rem; white-space:nowrap; transition:color 0.2s; flex-shrink:0;"
               onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='var(--text-secondary)'">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            <span style="color:var(--border); font-size:0.8rem; display:none; @media(min-width:640px){display:inline;}">|</span>
            <div style="min-width:0; display:none; @media(min-width:640px){display:block;}">
                <div style="font-size:0.72rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $kelas->mataKuliah->nama }}</div>
                <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $konten->judul }}</div>
            </div>
        </div>

        {{-- Center: prev / next nav --}}
        <div style="display:flex; align-items:center; gap:0.5rem; flex-shrink:0;">
            @if($sebelumnya)
            <a href="{{ route('mahasiswa.materi.viewer', [$kelas->slug, $sebelumnya]) }}" wire:navigate class="btn btn-ghost btn-sm" title="Materi sebelumnya">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg> <span style="display:none; @media(min-width:640px){display:inline;}">Prev</span>
            </a>
            @endif
            @if($berikutnya)
            <a href="{{ route('mahasiswa.materi.viewer', [$kelas->slug, $berikutnya]) }}" wire:navigate class="btn btn-primary btn-sm" title="Materi berikutnya">
                <span style="display:none; @media(min-width:640px){display:inline;}">Next</span> <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif
        </div>

        {{-- Right: toggles + theme --}}
        <div style="display:flex; align-items:center; gap:0.5rem; flex-shrink:0;">
            {{-- Search toggle --}}
            <button @click="searchModalOpen = true; $nextTick(() => $refs.searchInput.focus())" class="theme-toggle" title="Search modules">
                <i class="fas fa-search" style="font-size:14px;"></i>
            </button>

            {{-- Sidebar toggle --}}
            <button @click="sidebarOpen = !sidebarOpen; if(window.innerWidth<=768 && sidebarOpen) notesOpen = false;" class="theme-toggle" title="Toggle daftar materi">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
            </button>

            {{-- Notes toggle --}}
            <button @click="notesOpen = !notesOpen; if(window.innerWidth<=768 && notesOpen) sidebarOpen = false;" class="theme-toggle" title="Toggle catatan">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>

            {{-- Theme toggle --}}
            @include('components.theme-toggle')
        </div>
    </nav>

    {{-- ── 3-PANEL BODY ─────────────────────────────────────────────── --}}
    <div style="display:flex; flex:1; overflow:hidden; position:relative;">

        {{-- Overlays --}}
        <div class="lms-mobile-overlay" :class="sidebarOpen || notesOpen ? 'is-active' : ''" @click="if(window.innerWidth<=768) { sidebarOpen = false; notesOpen = false; }"></div>

        {{-- LEFT PANEL — Course Outline ──────────────────────────────── --}}
        <div class="lms-sidebar-left" :class="sidebarOpen ? 'is-open' : 'is-closed'"
             style="background:#fff; border-right:1px solid #e5e7eb; display:flex; flex-direction:column; overflow:hidden;">

            {{-- Course Progress --}}
            <div style="padding:1rem 1.25rem; border-bottom:1px solid #f3f4f6; flex-shrink:0;">
                @php
                    $totalKonten  = $kelas->pertemuan->flatMap->konten->where('is_published', true)->count();
                    $selesaiCount = count($selesaiIds);
                    $pctGlobal    = $totalKonten > 0 ? round($selesaiCount / $totalKonten * 100) : 0;
                @endphp
                <div style="display:flex; justify-content:space-between; margin-bottom:0.4rem; align-items:center;">
                    <span style="font-size:0.85rem; color:#4b5563; font-weight:600;">Course Progress</span>
                    <span style="font-size:0.85rem; color:#10b981; font-weight:700;">{{ $pctGlobal }}%</span>
                </div>
                <div style="background:#e5e7eb; height:6px; border-radius:3px; overflow:hidden; margin-bottom:0.5rem;">
                    <div style="background:#10b981; height:100%; width:{{ $pctGlobal }}%;"></div>
                </div>
                <div style="font-size:0.75rem; color:#6b7280;">
                    <b>{{ $selesaiCount }}</b> dari <b>{{ $totalKonten }}</b> pelajaran selesai
                </div>
            </div>

            {{-- Scrollable outline --}}
            <div style="flex:1; overflow-y:auto; padding:1rem 0;">
                @foreach($kelas->pertemuan as $pertemuan)
                @php
                    $pertemuanKonten = $pertemuan->konten->where('is_published', true);
                    $pertemuanTotal = $pertemuanKonten->count();
                    $pertemuanSelesai = $pertemuanKonten->filter(fn($k) => in_array($k->id, $selesaiIds))->count();
                @endphp
                <div x-data="{ open: {{ $konten->pertemuan_id === $pertemuan->id ? 'true' : 'false' }} }" style="margin-bottom:0.5rem;">
                    <button @click="open = !open"
                            style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:0.5rem 1.25rem; background:none; border:none; cursor:pointer; text-align:left;">
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <div style="color:#9ca3af; background:#f3f4f6; padding:0.4rem; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                                <i class="far fa-folder" style="font-size:0.85rem;"></i>
                            </div>
                            <span style="font-size:0.875rem; font-weight:600; color:#374151;">{{ Str::limit($pertemuan->topik, 30) }}</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <span style="font-size:0.75rem; color:#9ca3af; font-weight:500;">{{ $pertemuanSelesai }}/{{ $pertemuanTotal }}</span>
                            <i class="fas fa-chevron-down" :style="open ? 'transform:rotate(180deg)' : ''" style="font-size:0.7rem; color:#9ca3af; transition:transform 0.2s;"></i>
                        </div>
                    </button>
                    
                    <div style="margin: 0 1.25rem; border-bottom:1px solid #e5e7eb;"></div>

                    <div x-show="open" x-transition style="padding:0.5rem 1.25rem;">
                        @foreach($pertemuanKonten as $k)
                        @php $isActive = $k->id === $konten->id; @endphp
                        <a href="{{ route('mahasiswa.materi.viewer', [$kelas->slug, $k]) }}" wire:navigate
                           style="display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0.75rem; border-radius:6px; text-decoration:none; margin-bottom:0.25rem; transition:all 0.2s;
                                  {{ $isActive ? 'background:#374151; color:#ffffff;' : 'color:#4b5563;' }}"
                           onmouseover="if(!{{ $isActive ? 'true' : 'false' }}) this.style.background='#f3f4f6'"
                           onmouseout="if(!{{ $isActive ? 'true' : 'false' }}) this.style.background='transparent'">
                            
                            @if(in_array($k->id, $selesaiIds))
                                <i class="far fa-check-circle" style="font-size:1rem; {{ $isActive ? 'color:#10b981;' : 'color:#10b981;' }}"></i>
                            @else
                                <i class="far fa-circle" style="font-size:1rem; {{ $isActive ? 'color:#9ca3af;' : 'color:#d1d5db;' }}"></i>
                            @endif
                            <span style="flex:1; font-size:0.875rem; line-height:1.4;">{{ Str::limit($k->judul, 35) }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CENTER PANEL — Content ────────────────────────────────────── --}}
        <div id="lms-scroll-container" @scroll="scrollProgress = Math.round(($event.target.scrollTop / ($event.target.scrollHeight - $event.target.clientHeight)) * 100) || 0" style="flex:1; overflow-y:auto; background:#ffffff; display:flex; flex-direction:column; scroll-behavior: smooth;">

            {{-- Content area (Removed max-width to allow full width per user request) --}}
            <div class="lms-center-panel" style="flex:1; padding:3rem 4rem; width:100%;">

                {{-- Content header --}}
                <div style="margin-bottom:2.5rem; padding-bottom:1rem; border-bottom:1px solid #f3f4f6;">
                    <h1 style="font-size:2rem; font-weight:800; color:#111827; line-height:1.3; margin-bottom:0.75rem;">{{ $konten->judul }}</h1>
                </div>

                {{-- ── Content by type ──────────────────────────── --}}

                @if($konten->tipe === 'video')
                    {{-- VIDEO --}}
                    <div style="margin-bottom:2rem;">
                        @if(Str::contains($konten->url ?? '', ['youtube.com', 'youtu.be']))
                        @php
                            preg_match('/(?:v=|youtu\.be\/)([^&\s]+)/', $konten->url, $m);
                            $ytId = $m[1] ?? '';
                        @endphp
                        <div class="video-wrap" style="border-radius:12px; overflow:hidden; box-shadow:0 10px 25px -5px rgba(0,0,0,0.1);">
                            <iframe src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                        @elseif($konten->file_path)
                        <video controls style="width:100%; border-radius:12px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.1);">
                            <source src="{{ asset('storage/'.$konten->file_path) }}">
                        </video>
                        @endif
                    </div>

                @elseif($konten->tipe === 'pdf')
                    {{-- PDF --}}
                    <div style="border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; height:700px; margin-bottom:2rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
                        @if($konten->file_path)
                        <iframe src="{{ asset('storage/'.$konten->file_path) }}" style="width:100%; height:100%; border:none;"></iframe>
                        @else
                        <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#9ca3af;">File PDF tidak tersedia</div>
                        @endif
                    </div>

                @elseif($konten->tipe === 'artikel')
                    {{-- ARTIKEL --}}
                    <div class="prose-lms" style="margin-bottom:2rem; color:#374151; font-size:1.05rem;">
                        {!! $konten->konten !!}
                    </div>

                @elseif($konten->tipe === 'kode')
                    {{-- CODE EDITOR (Monaco) --}}
                    <div style="margin-bottom:2rem;">
                        <div id="monaco-editor" style="height:500px; border-radius:12px;"></div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            if (typeof monaco !== 'undefined') {
                                monaco.editor.create(document.getElementById('monaco-editor'), {
                                    value: {!! json_encode($konten->konten ?? '// Kode akan dimuat di sini') !!},
                                    language: 'javascript',
                                    theme: 'vs-dark',
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
                    <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:3rem 2rem; text-align:center; margin-bottom:2rem;">
                        <div style="font-size:3rem; margin-bottom:1rem; color:#9ca3af;"><i class="fas fa-external-link-alt"></i></div>
                        <h3 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:0.5rem;">Materi Eksternal</h3>
                        <p style="color:#6b7280; margin-bottom:1.5rem; max-width:400px; margin-left:auto; margin-right:auto;">Materi ini mengarah ke tautan di luar platform. Silakan klik tombol di bawah untuk membukanya di tab baru.</p>
                        <a href="{{ $konten->url }}" target="_blank" class="btn btn-primary" style="padding:0.75rem 2rem; background:#2563eb; color:white; text-decoration:none; border-radius:6px; display:inline-block;">
                            Buka Tautan <i class="fas fa-arrow-right ml-2"></i>
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
                        <a href="{{ route('mahasiswa.materi.viewer', [$kelas->slug, $berikutnya]) }}" wire:navigate class="btn btn-outline">
                            Materi Berikutnya →
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL — TOC & Catatan ─────────────────────────────────────── --}}
        <div class="lms-sidebar-right" :class="notesOpen ? 'is-open' : 'is-closed'"
             style="background:var(--bg-sidebar); border-left:1px solid var(--border); display:flex; flex-direction:column; overflow:hidden;">

            {{-- On This Page Section --}}
            <div style="display:flex; align-items:center; justify-content:space-between; padding:0.875rem; border-bottom:1px solid var(--border); flex-shrink:0;">
                <div style="font-size:0.85rem; font-weight:700; color:var(--text-primary); display:flex; align-items:center; gap:0.75rem;">
                    <i class="fas fa-list-ul" style="color:var(--text-muted);"></i> On This Page
                </div>
                <button @click="notesOpen = false" style="background:none; border:none; color:var(--text-muted); cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>

            {{-- Reading Progress --}}
            <div style="padding:0.875rem; border-bottom:1px solid var(--border); flex-shrink:0;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.4rem;">
                    <span style="font-size:0.75rem; color:var(--text-secondary);">Reading Progress</span>
                    <span style="font-size:0.75rem; font-weight:700; color:var(--text-primary);" x-text="scrollProgress + '%'">0%</span>
                </div>
                <div style="height:6px; background:var(--border); border-radius:10px; overflow:hidden;">
                    <div style="height:100%; background:#10b981; transition:width 0.15s ease-out; width:0%;" :style="{ width: scrollProgress + '%' }"></div>
                </div>
            </div>



            {{-- Top / Bottom Nav --}}
            <div style="display:flex; justify-content:space-between; padding:0.875rem; border-bottom:1px solid var(--border); flex-shrink:0;">
                <button onclick="document.getElementById('lms-scroll-container').scrollTo({top:0, behavior:'smooth'})" style="background:none; border:none; font-size:0.75rem; color:var(--text-secondary); cursor:pointer; display:flex; align-items:center; gap:0.25rem;">
                    <i class="fas fa-arrow-up"></i> Top
                </button>
                <button onclick="document.getElementById('lms-scroll-container').scrollTo({top:document.getElementById('lms-scroll-container').scrollHeight, behavior:'smooth'})" style="background:none; border:none; font-size:0.75rem; color:var(--text-secondary); cursor:pointer; display:flex; align-items:center; gap:0.25rem;">
                    Bottom <i class="fas fa-arrow-down"></i>
                </button>
            </div>

            <div style="padding:0.875rem; border-bottom:1px solid var(--border); flex-shrink:0;">
                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.08em;"><i class="fas fa-edit"></i> Catatan Saya</div>
            </div>

            <div style="flex:1; padding:0.875rem; display:flex; flex-direction:column; overflow-y:auto;">
                <textarea wire:model.lazy="catatan" placeholder="Tulis catatan untuk materi ini..."
                          style="height:180px; width:100%; background:var(--input-bg); border:1.5px solid var(--input-border); border-radius:10px; padding:0.75rem; color:var(--text-primary); font-size:0.8rem; font-family:'Inter',sans-serif; resize:vertical; outline:none; transition:border-color 0.2s; line-height:1.6; flex-shrink:0;"
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
    {{-- Search Modal --}}
    <div x-show="searchModalOpen" style="display:none;" class="lms-search-modal" x-transition.opacity>
        <div class="lms-search-backdrop" @click="searchModalOpen = false"></div>
        <div class="lms-search-box" @click.stop x-transition.scale.origin.top>
            <div class="lms-search-header">
                <div style="display:flex; align-items:center; gap:0.5rem; font-weight:600; color:var(--text-primary);">
                    <i class="fas fa-search" style="color:#dc2626"></i> Search Modules
                </div>
                <button @click="searchModalOpen = false" style="background:none; border:none; color:var(--text-muted); cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding:1.5rem;">
                <input type="text" x-model="searchQuery" x-ref="searchInput" placeholder="Search modules here..." class="lms-search-input">
                
                <div class="lms-search-results" style="margin-top:2rem; min-height:150px; max-height:40vh; overflow-y:auto;">
                    <template x-if="searchQuery.length < 3">
                        <div style="text-align:center; color:var(--text-muted); padding-top:1rem;">
                            <div style="width:50px;height:50px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                                <i class="fas fa-search" style="font-size:1.2rem;"></i>
                            </div>
                            <div style="font-size:0.85rem;">Enter keywords to search modules</div>
                        </div>
                    </template>
                    
                    <template x-if="searchQuery.length >= 3">
                        <div>
                            {{-- Loading Spinner --}}
                            <template x-if="isSearching">
                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:2.5rem 0; color:var(--text-muted);">
                                    <div class="lms-spinner"></div>
                                    <div style="margin-top:1.25rem; font-size:0.85rem; font-weight:500;">Mencari modul...</div>
                                </div>
                            </template>
                            
                            {{-- Results --}}
                            <template x-if="!isSearching">
                                <div>
                                    <template x-for="module in courseModules.filter(m => m.search_text.includes(searchQuery.toLowerCase()))" :key="module.url">
                                        <a :href="module.url" wire:navigate style="display:block; padding:0.75rem 1rem; border-bottom:1px solid var(--border); color:var(--text-primary); text-decoration:none; transition:background 0.2s; border-radius:6px;" onmouseover="this.style.background='var(--input-bg)'" onmouseout="this.style.background='none'" @click="searchModalOpen = false">
                                            <div style="font-size:0.9rem; font-weight:500; display:flex; align-items:center;">
                                                <i class="far fa-file-alt" style="margin-right:0.5rem; color:var(--text-muted);"></i>
                                                <span x-text="module.title"></span>
                                            </div>
                                            <div style="font-size:0.75rem; color:var(--text-muted); margin-left:1.5rem; margin-top:0.25rem;">
                                                Folder: <span x-text="module.pertemuan"></span>
                                            </div>
                                        </a>
                                    </template>
                                    
                                    <template x-if="courseModules.filter(m => m.search_text.includes(searchQuery.toLowerCase())).length === 0">
                                        <div style="text-align:center; color:var(--text-muted); padding-top:1rem; font-size:0.85rem;">
                                            No modules found matching "<span x-text="searchQuery"></span>"
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
            <div style="padding:1rem 1.5rem; border-top:1px solid var(--border); font-size:0.7rem; color:var(--text-muted); text-align:center;">
                Search through all course modules &bull; Minimum 3 characters
            </div>
        </div>
    </div>

</div>

<style>
.lms-search-modal {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    z-index: 9999;
    display: flex; align-items: flex-start; justify-content: center;
    padding-top: 10vh;
}
.lms-search-backdrop {
    position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(4px);
}
.lms-search-box {
    position: relative;
    background: var(--bg-card);
    width: 90%; max-width: 600px;
    border-radius: 12px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    display: flex; flex-direction: column;
}
.lms-search-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border);
    display: flex; justify-content: space-between; align-items: center;
}
.lms-search-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--input-bg);
    color: var(--text-primary);
    font-size: 0.9rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.lms-search-input:focus {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
}
.lms-spinner {
    width: 32px;
    height: 32px;
    border: 3px solid rgba(220, 38, 38, 0.1);
    border-radius: 50%;
    border-top-color: #dc2626;
    animation: lms-spin 0.8s ease-in-out infinite;
}
@keyframes lms-spin {
    to { transform: rotate(360deg); }
}
</style>
</div>
