<!DOCTYPE html>
<html lang="id" x-init="$store.theme.init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kuis->judul ?? 'Kuis' }} — LMS PTI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        html, body { height: 100%; overflow: hidden; }

        .kuis-shell { display: flex; flex-direction: column; height: 100vh; background: var(--bg-main); }

        .kuis-topbar {
            height: 56px; background: var(--bg-topbar); border-bottom: 1px solid var(--border);
            backdrop-filter: blur(16px); display: flex; align-items: center;
            justify-content: space-between; padding: 0 1.25rem; flex-shrink: 0; z-index: 20;
        }

        .kuis-body { display: flex; flex: 1; overflow: hidden; }

        /* Left: question navigator */
        .kuis-nav-panel {
            width: 220px; min-width: 220px; background: var(--bg-sidebar);
            border-right: 1px solid var(--border); display: flex; flex-direction: column;
            overflow: hidden; flex-shrink: 0;
        }

        .soal-num-btn {
            width: 36px; height: 36px; border-radius: 8px; border: 1.5px solid var(--border);
            background: var(--input-bg); font-size: 0.78rem; font-weight: 600;
            color: var(--text-secondary); cursor: pointer; display: flex; align-items: center;
            justify-content: center; transition: all 0.15s;
        }
        .soal-num-btn:hover  { border-color: var(--teal); color: var(--teal); }
        .soal-num-btn.aktif  { background: var(--teal); border-color: var(--teal); color: white; }
        .soal-num-btn.dijawab { background: var(--teal-dim); border-color: var(--border-teal); color: var(--teal); }

        /* Center: question area */
        .kuis-content { flex: 1; overflow-y: auto; }

        .pilihan-item {
            display: flex; align-items: flex-start; gap: 0.875rem;
            padding: 0.875rem 1rem; border-radius: 10px;
            border: 1.5px solid var(--border); cursor: pointer;
            transition: all 0.15s; margin-bottom: 0.5rem; background: var(--input-bg);
        }
        .pilihan-item:hover { border-color: var(--border-teal); background: var(--teal-dim); }
        .pilihan-item.selected { border-color: var(--teal); background: var(--teal-dim); }

        .pilihan-radio {
            width: 20px; height: 20px; border-radius: 50%;
            border: 2px solid var(--border); display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-top: 1px; transition: all 0.15s;
        }
        .pilihan-item.selected .pilihan-radio { border-color: var(--teal); }
        .pilihan-item.selected .pilihan-radio::after {
            content: ''; width: 10px; height: 10px; border-radius: 50%; background: var(--teal);
        }

        /* Timer */
        .timer-display {
            font-size: 1rem; font-weight: 800; font-family: monospace;
            padding: 0.3rem 0.75rem; border-radius: 8px;
            background: var(--input-bg); border: 1px solid var(--border);
        }
        .timer-warning { color: var(--warning); border-color: rgba(245,158,11,0.4); background: rgba(245,158,11,0.08); }
        .timer-danger  { color: var(--danger);  border-color: rgba(239,68,68,0.4);  background: rgba(239,68,68,0.08); animation: pulse 1s infinite; }

        /* Modal */
        .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; z-index:100; }
        .modal-card { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:2rem; max-width:420px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.5); }
    </style>
</head>
<body>
<div class="kuis-shell" x-data="{
    waktu: {{ $sisaDetik ?? 0 }},
    interval: null,
    formatTime(s) {
        const m = Math.floor(s/60).toString().padStart(2,'0');
        const sc = (s%60).toString().padStart(2,'0');
        return m+':'+sc;
    },
    startTimer() {
        this.interval = setInterval(() => {
            if (this.waktu > 0) { this.waktu--; }
            else { clearInterval(this.interval); $wire.submitKuis(); }
        }, 1000);
    },
    get timerClass() {
        if(this.waktu <= 60) return 'timer-danger';
        if(this.waktu <= 300) return 'timer-warning';
        return '';
    }
}" x-init="if(!{{ $selesai ? 'true' : 'false' }}) startTimer()">

    @if(!$selesai)
    {{-- ── TOPBAR ──────────────────────────────────── --}}
    <div class="kuis-topbar">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:8px; height:8px; border-radius:50%; background:var(--success); animation:pulse 2s infinite;"></div>
            <div>
                <div style="font-size:0.9rem; font-weight:700; color:var(--text-primary);">{{ $kuis->judul }}</div>
                <div style="font-size:0.7rem; color:var(--text-secondary);">{{ $kuis->kelas->mataKuliah->nama }}</div>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:1rem;">
            <div class="timer-display" :class="timerClass">
                ⏱ <span x-text="formatTime(waktu)">00:00</span>
            </div>
            @include('components.theme-toggle')
            <button wire:click="$set('confirmSubmit',true)" class="btn btn-primary btn-sm">Selesai & Kumpulkan</button>
        </div>
    </div>

    {{-- ── BODY ────────────────────────────────────── --}}
    <div class="kuis-body">

        {{-- LEFT: Navigator --}}
        <div class="kuis-nav-panel">
            <div style="padding:0.875rem; border-bottom:1px solid var(--border); flex-shrink:0;">
                <div style="font-size:0.7rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.6rem;">Navigator Soal</div>
                <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:0.4rem;">
                    @foreach($soalList as $i => $soal)
                    <button class="soal-num-btn {{ $currentIndex === $i ? 'aktif' : (isset($jawaban[$soal->id]) ? 'dijawab' : '') }}"
                            wire:click="goTo({{ $i }})">
                        {{ $i + 1 }}
                    </button>
                    @endforeach
                </div>
            </div>
            <div style="padding:0.875rem; font-size:0.75rem; color:var(--text-secondary);">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.4rem;">
                    <div style="width:14px; height:14px; border-radius:3px; background:var(--teal);"></div> Sedang dikerjakan
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.4rem;">
                    <div style="width:14px; height:14px; border-radius:3px; background:var(--teal-dim); border:1px solid var(--border-teal);"></div> Sudah dijawab
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <div style="width:14px; height:14px; border-radius:3px; background:var(--input-bg); border:1px solid var(--border);"></div> Belum dijawab
                </div>
                <div style="margin-top:0.875rem; padding-top:0.875rem; border-top:1px solid var(--border);">
                    <span style="font-weight:700; color:var(--teal);">{{ count($jawaban) }}</span>
                    <span style="color:var(--text-muted);"> / {{ $soalList->count() }} dijawab</span>
                </div>
            </div>
        </div>

        {{-- CENTER: Question --}}
        <div class="kuis-content">
            <div style="max-width:720px; margin:0 auto; padding:2rem;">

                @if($soalList->isNotEmpty())
                @php $soal = $soalList[$currentIndex]; @endphp

                {{-- Progress --}}
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                    <span style="font-size:0.8rem; color:var(--text-secondary);">Soal {{ $currentIndex + 1 }} dari {{ $soalList->count() }}</span>
                    <div class="progress-wrap" style="width:200px;">
                        <div class="progress-bar" style="width:{{ round(($currentIndex+1)/$soalList->count()*100) }}%;"></div>
                    </div>
                </div>

                {{-- Question card --}}
                <div class="card" style="margin-bottom:1.25rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.875rem;">
                        <span style="font-size:0.7rem; font-weight:700; color:var(--teal); background:var(--teal-dim); border-radius:5px; padding:0.15rem 0.5rem;">No. {{ $currentIndex + 1 }}</span>
                        <span class="badge badge-gray">{{ $soal->bobot ?? 1 }} poin</span>
                        @if($soal->tipe === 'pg') <span class="badge badge-gray">Pilihan Ganda</span>
                        @elseif($soal->tipe === 'esai') <span class="badge badge-gray">Esai</span>
                        @elseif($soal->tipe === 'benar_salah') <span class="badge badge-gray">Benar / Salah</span>
                        @endif
                    </div>
                    <div style="font-size:1rem; color:var(--text-primary); line-height:1.7; font-weight:500;">
                        {!! nl2br(e($soal->pertanyaan)) !!}
                    </div>
                    @if($soal->gambar)
                    <img src="{{ asset('storage/'.$soal->gambar) }}" style="max-width:100%; border-radius:8px; margin-top:1rem;">
                    @endif
                </div>

                {{-- Answer area --}}
                @if($soal->tipe === 'pg' || $soal->tipe === 'benar_salah')
                <div>
                    @php $huruf = ['A','B','C','D','E','F']; @endphp
                    @foreach($soal->pilihan as $pi => $pilihan)
                    <div class="pilihan-item {{ ($jawaban[$soal->id] ?? null) == $pilihan->id ? 'selected' : '' }}"
                         wire:click="pilih({{ $soal->id }}, {{ $pilihan->id }})">
                        <div class="pilihan-radio" style="{{ ($jawaban[$soal->id] ?? null) == $pilihan->id ? 'border-color:var(--teal);' : '' }}">
                            @if(($jawaban[$soal->id] ?? null) == $pilihan->id)
                            <div style="width:10px; height:10px; border-radius:50%; background:var(--teal);"></div>
                            @endif
                        </div>
                        <div style="display:flex; align-items:flex-start; gap:0.5rem; flex:1;">
                            <span style="font-weight:700; color:var(--teal); flex-shrink:0; margin-top:1px;">{{ $huruf[$pi] ?? '' }}.</span>
                            <span style="font-size:0.9rem; color:var(--text-primary); line-height:1.6;">{{ $pilihan->teks }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                @elseif($soal->tipe === 'esai')
                <textarea
                    wire:change="pilih({{ $soal->id }}, $event.target.value)"
                    placeholder="Tulis jawaban kamu di sini..."
                    style="width:100%; min-height:180px; background:var(--input-bg); border:1.5px solid var(--input-border); border-radius:10px; padding:0.875rem; color:var(--text-primary); font-size:0.875rem; font-family:'Inter',sans-serif; resize:vertical; outline:none; transition:border-color 0.2s; line-height:1.6;"
                    onfocus="this.style.borderColor='var(--teal)'" onblur="this.style.borderColor='var(--input-border)'">{{ $jawaban[$soal->id] ?? '' }}</textarea>
                @endif

                {{-- Navigation --}}
                <div style="display:flex; justify-content:space-between; margin-top:1.5rem; gap:0.75rem;">
                    <button wire:click="goTo({{ max(0, $currentIndex-1) }})"
                            class="btn btn-ghost" {{ $currentIndex === 0 ? 'disabled' : '' }}
                            style="{{ $currentIndex === 0 ? 'opacity:0.4; cursor:not-allowed;' : '' }}">
                        ← Sebelumnya
                    </button>
                    @if($currentIndex < $soalList->count()-1)
                    <button wire:click="goTo({{ $currentIndex + 1 }})" class="btn btn-primary">
                        Selanjutnya →
                    </button>
                    @else
                    <button wire:click="$set('confirmSubmit',true)" class="btn btn-primary">
                        ✅ Selesai & Kumpulkan
                    </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Confirm Modal --}}
    @if($confirmSubmit)
    <div class="modal-overlay" wire:click.self="$set('confirmSubmit',false)">
        <div class="modal-card fade-in">
            <div style="text-align:center; margin-bottom:1.5rem;">
                <div style="font-size:2.5rem; margin-bottom:0.75rem;">📤</div>
                <h2 style="font-size:1.1rem; font-weight:700; color:var(--text-primary); margin-bottom:0.5rem;">Kumpulkan Jawaban?</h2>
                <p style="font-size:0.85rem; color:var(--text-secondary); line-height:1.6;">
                    Kamu sudah menjawab <strong style="color:var(--teal);">{{ count($jawaban) }}</strong> dari <strong>{{ $soalList->count() }}</strong> soal.
                    Setelah dikumpulkan, jawaban tidak bisa diubah lagi.
                </p>
            </div>
            <div style="display:flex; gap:0.75rem;">
                <button wire:click="$set('confirmSubmit',false)" class="btn btn-ghost btn-full">Batal, lanjutkan</button>
                <button wire:click="submitKuis" class="btn btn-primary btn-full">
                    <span wire:loading.remove wire:target="submitKuis">✅ Ya, Kumpulkan</span>
                    <span wire:loading wire:target="submitKuis">Memproses...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    @else
    {{-- ── HASIL KUIS ───────────────────────────── --}}
    <div style="display:flex; align-items:center; justify-content:center; flex:1;">
        <div style="text-align:center; max-width:460px; padding:2rem;" class="fade-in">
            @php
                $nilai = $sesi->nilai_akhir ?? 0;
                $lulus = $nilai >= ($kuis->passing_grade ?? 60);
            @endphp
            <div style="font-size:4rem; margin-bottom:1.25rem;">{{ $lulus ? '🎉' : '📚' }}</div>
            <h1 style="font-size:1.6rem; font-weight:800; color:var(--text-primary); margin-bottom:0.5rem;">
                {{ $lulus ? 'Selamat, Lulus!' : 'Kuis Selesai' }}
            </h1>
            <p style="color:var(--text-secondary); font-size:0.875rem; margin-bottom:2rem;">
                {{ $lulus ? 'Kamu berhasil melewati KKM kuis ini.' : 'Kamu belum mencapai KKM. Jangan menyerah!' }}
            </p>

            <div class="card" style="margin-bottom:1.5rem; background:{{ $lulus ? 'linear-gradient(135deg,rgba(34,197,94,0.1),var(--bg-card))' : 'linear-gradient(135deg,rgba(239,68,68,0.07),var(--bg-card))' }}; border-color:{{ $lulus ? 'rgba(34,197,94,0.3)' : 'rgba(239,68,68,0.2)' }};">
                <div style="font-size:3rem; font-weight:900; color:{{ $lulus ? 'var(--success)' : 'var(--danger)' }}; margin-bottom:0.25rem;">{{ $nilai }}</div>
                <div style="color:var(--text-secondary); font-size:0.85rem;">dari 100 · KKM {{ $kuis->passing_grade ?? 60 }}</div>
            </div>

            <div style="display:flex; gap:0.75rem; justify-content:center;">
                <a href="{{ route('mahasiswa.kuis.index') }}" class="btn btn-ghost">← Kembali ke Kuis</a>
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-primary">Dashboard</a>
            </div>
        </div>
    </div>
    @endif

</div>
@livewireScripts
</body>
</html>
