<div class="space-y-6 max-w-4xl mx-auto">
    <a href="{{ route('mahasiswa.kuis.index') }}" class="text-[var(--text-muted)] hover:text-[var(--teal)] text-sm mb-4 inline-block transition">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Kuis
    </a>

    {{-- Hero Card: Hasil Kuis --}}
    <div class="card p-8 border-t-4 border-t-[var(--teal)] text-center relative overflow-hidden">
        {{-- Background Decoration --}}
        <div class="absolute -top-10 -right-10 text-[var(--teal-dim)] opacity-20 pointer-events-none">
            <i class="fas fa-award text-9xl"></i>
        </div>

        <h1 class="text-2xl font-bold text-[var(--text-primary)] mb-2">{{ $kuis->judul }}</h1>
        <p class="text-[var(--text-secondary)] mb-8">Percobaan ke-{{ $sesi->percobaan_ke }} • Diselesaikan pada {{ $sesi->selesai_at ? $sesi->selesai_at->format('d M Y, H:i') : '-' }}</p>

        <div class="flex flex-col md:flex-row justify-center items-center gap-8 md:gap-16">
            <div class="text-center">
                <p class="text-sm text-[var(--text-muted)] font-bold uppercase tracking-wider mb-2">Nilai Akhir</p>
                <div class="w-32 h-32 rounded-full border-8 border-[var(--teal-dim)] flex items-center justify-center mx-auto mb-2 relative">
                    <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                        <circle cx="50%" cy="50%" r="46%" stroke="var(--teal)" stroke-width="8" fill="none" stroke-dasharray="290" stroke-dashoffset="{{ 290 - (290 * ($sesi->nilai / 100)) }}" class="transition-all duration-1000" />
                    </svg>
                    <span class="text-4xl font-black text-[var(--teal)]">{{ number_format($sesi->nilai, 0) }}</span>
                </div>
                <p class="text-xs text-[var(--text-secondary)]">dari nilai maks {{ $kuis->nilai_max }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-left">
                <div class="bg-[var(--bg-card-hover)] p-4 rounded-xl border border-[var(--border)] min-w-[140px]">
                    <div class="text-[var(--teal)] mb-1"><i class="fas fa-clock"></i> Waktu Pengerjaan</div>
                    <div class="font-bold text-[var(--text-primary)]">{{ $durasiText }}</div>
                </div>
                <div class="bg-[var(--bg-card-hover)] p-4 rounded-xl border border-[var(--border)] min-w-[140px]">
                    <div class="text-[var(--text-secondary)] mb-1"><i class="fas fa-tasks"></i> Total Soal</div>
                    <div class="font-bold text-[var(--text-primary)]">{{ count($sesi->urutan_soal ?? []) }} Soal</div>
                </div>
                <div class="bg-green-500/10 border border-green-500/20 p-4 rounded-xl min-w-[140px]">
                    <div class="text-green-600 dark:text-green-400 mb-1"><i class="fas fa-check-circle"></i> Benar</div>
                    <div class="font-bold text-green-700 dark:text-green-300">{{ $benar }} Soal</div>
                </div>
                <div class="bg-red-500/10 border border-red-500/20 p-4 rounded-xl min-w-[140px]">
                    <div class="text-red-600 dark:text-red-400 mb-1"><i class="fas fa-times-circle"></i> Salah</div>
                    <div class="font-bold text-red-700 dark:text-red-300">{{ $salah }} Soal</div>
                </div>
            </div>
        </div>
    </div>

    @if(!$tampilkanPembahasan)
        <div class="bg-[var(--input-bg)] border border-[var(--border)] rounded-xl p-6 text-center text-[var(--text-secondary)]">
            <i class="fas fa-lock text-3xl mb-3 text-[var(--text-muted)]"></i>
            <h3 class="font-bold text-lg text-[var(--text-primary)] mb-1">Kunci Jawaban Dirahasiakan</h3>
            <p>Dosen pengampu tidak mengizinkan tampilan kunci jawaban dan pembahasan untuk kuis ini.</p>
        </div>
    @else
        <div class="flex items-center gap-2 text-lg font-bold text-[var(--text-primary)] pb-2 border-b border-[var(--border)]">
            <i class="fas fa-list-ul text-[var(--teal)]"></i> Detail Jawaban & Pembahasan
        </div>

        <div class="space-y-6">
            @foreach($sesi->jawaban as $index => $jawaban)
                @php
                    $soal = $jawaban->soal->bankSoal ?? null;
                    if(!$soal) continue;
                    
                    $isBenar = $soal->tipe === 'pg' && $jawaban->jawaban_text === $soal->jawaban;
                    $borderColor = $isBenar ? 'border-green-500' : 'border-red-500';
                    $bgColor = $isBenar ? 'bg-green-500/5' : 'bg-red-500/5';
                @endphp

                <div class="card p-6 border-l-4 {{ $borderColor }} {{ $bgColor }}">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded bg-[var(--bg-body)] border border-[var(--border)] flex items-center justify-center font-bold text-[var(--text-secondary)]">
                                {{ $index + 1 }}
                            </div>
                            <div class="pt-1 prose prose-sm max-w-none text-[var(--text-primary)] prose-invert">
                                {!! $soal->pertanyaan !!}
                            </div>
                        </div>
                        @if($soal->tipe === 'pg')
                            <div class="px-3 py-1 rounded text-xs font-bold shrink-0 {{ $isBenar ? 'bg-green-500/20 text-green-600 dark:text-green-400' : 'bg-red-500/20 text-red-600 dark:text-red-400' }}">
                                @if($isBenar)
                                    <i class="fas fa-check mr-1"></i> Benar
                                @else
                                    <i class="fas fa-times mr-1"></i> Salah
                                @endif
                            </div>
                        @else
                            <div class="px-3 py-1 rounded text-xs font-bold shrink-0 bg-blue-500/20 text-blue-600 dark:text-blue-400">
                                Essay
                            </div>
                        @endif
                    </div>

                    @if($soal->tipe === 'pg')
                        <div class="pl-11 grid gap-2 mb-4">
                            @foreach($soal->pilihan as $idx => $pilihan)
                                @php
                                    $huruf = chr(65 + $idx); // A, B, C, D
                                    
                                    $isDipilih = $jawaban->jawaban_text === $huruf;
                                    $isKunci = $soal->jawaban === $huruf;
                                    
                                    $optClass = 'border-[var(--border)] bg-[var(--bg-body)]'; // Default
                                    $textClass = 'text-[var(--text-secondary)]';
                                    $icon = '';
                                    
                                    if ($isKunci) {
                                        // Ini adalah jawaban yang benar menurut kunci
                                        $optClass = 'border-green-500 bg-green-500/10';
                                        $textClass = 'text-green-700 dark:text-green-400 font-medium';
                                        $icon = '<i class="fas fa-check-circle text-green-500"></i>';
                                    } elseif ($isDipilih && !$isKunci) {
                                        // Mahasiswa memilih ini dan salah
                                        $optClass = 'border-red-500 bg-red-500/10';
                                        $textClass = 'text-red-700 dark:text-red-400 font-medium';
                                        $icon = '<i class="fas fa-times-circle text-red-500"></i>';
                                    }
                                @endphp
                                <div class="p-3 border rounded-lg flex items-start gap-3 {{ $optClass }}">
                                    <div class="font-bold w-6 h-6 flex-shrink-0 flex items-center justify-center rounded bg-[var(--bg-card)] border border-[var(--border)] {{ $textClass }}">
                                        {{ $huruf }}
                                    </div>
                                    <div class="flex-1 text-sm {{ $textClass }} pt-0.5">
                                        {{ $pilihan['teks'] ?? '' }}
                                    </div>
                                    @if($icon)
                                        <div class="flex-shrink-0 mt-0.5">{!! $icon !!}</div>
                                    @endif
                                    @if($isDipilih)
                                        <div class="text-xs bg-[var(--bg-card)] border border-[var(--border)] px-2 py-0.5 rounded-full whitespace-nowrap opacity-70">
                                            Jawaban Anda
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Tampilan untuk soal Essay --}}
                        <div class="pl-11 mb-4">
                            <div class="text-sm font-semibold text-[var(--text-secondary)] mb-1">Jawaban Anda:</div>
                            <div class="bg-[var(--bg-body)] border border-[var(--border)] p-4 rounded-lg text-sm text-[var(--text-primary)]">
                                {!! nl2br(e($jawaban->jawaban_text)) ?: '<span class="italic opacity-50">Kosong</span>' !!}
                            </div>
                        </div>
                    @endif

                    @if($soal->pembahasan)
                        <div class="pl-11 mt-4">
                            <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4">
                                <div class="text-blue-600 dark:text-blue-400 font-bold text-xs uppercase tracking-wider mb-2 flex items-center gap-2">
                                    <i class="fas fa-lightbulb"></i> Pembahasan
                                </div>
                                <div class="prose prose-sm max-w-none text-[var(--text-primary)] prose-invert opacity-90">
                                    {!! $soal->pembahasan !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
