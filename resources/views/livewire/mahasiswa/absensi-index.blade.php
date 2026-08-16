<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Riwayat Kehadiran</h1>
            <p class="section-sub text-muted">Pantau persentase kehadiran Anda. Batas minimal 75%.</p>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($dataKehadiran as $index => $data)
            <div class="card p-6" x-data="{ open: false }" style="background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 0.5rem; box-shadow: var(--shadow-card);">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold" style="color: var(--text-primary);">{{ $data['kelas']->mataKuliah->nama_mk }}</h2>
                        <p class="text-sm" style="color: var(--text-muted);">Dosen: {{ $data['kelas']->dosen->name }} | Total Pertemuan: {{ $data['total'] }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 text-right">
                        <span class="text-2xl font-bold {{ $data['persentase'] >= 75 ? 'text-teal-600' : 'text-red-500' }}" style="color: {{ $data['persentase'] >= 75 ? 'var(--teal)' : 'var(--danger)' }}">{{ number_format($data['persentase'], 1) }}%</span>
                        <p class="text-xs" style="color: var(--text-muted);">Tingkat Kehadiran</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-6" style="background-color: #e5e7eb;">
                    <div class="h-2.5 rounded-full" style="width: {{ $data['persentase'] }}%; background-color: {{ $data['persentase'] >= 75 ? 'var(--teal)' : 'var(--danger)' }};"></div>
                </div>
                
                @if($data['persentase'] < 75 && $data['total'] > 0)
                    <div class="mb-4 p-3 rounded-md text-sm" style="background-color: #fee2e2; color: var(--danger); border: 1px solid #fecaca;">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Perhatian: Kehadiran Anda di bawah batas minimal (75%).
                    </div>
                @endif

                <div class="flex space-x-2 md:space-x-4 mb-4">
                    <div class="px-3 py-1 rounded-md text-xs font-medium" style="background-color: #dcfce7; color: var(--success);">Hadir: {{ $data['hadir'] }}</div>
                    <div class="px-3 py-1 rounded-md text-xs font-medium" style="background-color: #fef3c7; color: #d97706;">Izin: {{ $data['izin'] }}</div>
                    <div class="px-3 py-1 rounded-md text-xs font-medium" style="background-color: #e0f2fe; color: #0284c7;">Sakit: {{ $data['sakit'] }}</div>
                    <div class="px-3 py-1 rounded-md text-xs font-medium" style="background-color: #fee2e2; color: var(--danger);">Alpha: {{ $data['alpha'] }}</div>
                </div>

                <button @click="open = !open" class="text-sm font-medium flex items-center mt-4" style="color: var(--teal);">
                    <span x-text="open ? 'Sembunyikan Detail' : 'Lihat Detail Pertemuan'"></span>
                    <svg class="w-4 h-4 ml-1 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="open" class="mt-4 overflow-hidden" style="display: none;">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <th class="py-2 text-xs font-semibold" style="color: var(--text-secondary);">Pertemuan</th>
                                <th class="py-2 text-xs font-semibold" style="color: var(--text-secondary);">Tanggal</th>
                                <th class="py-2 text-xs font-semibold" style="color: var(--text-secondary);">Materi</th>
                                <th class="py-2 text-xs font-semibold text-right" style="color: var(--text-secondary);">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['detail'] as $det)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td class="py-2 text-sm" style="color: var(--text-primary);">Ke-{{ $det['pertemuan_ke'] }}</td>
                                    <td class="py-2 text-sm" style="color: var(--text-secondary);">{{ $det['tanggal'] ? \Carbon\Carbon::parse($det['tanggal'])->format('d M Y') : '-' }}</td>
                                    <td class="py-2 text-sm" style="color: var(--text-secondary);">{{ $det['materi'] }}</td>
                                    <td class="py-2 text-sm text-right">
                                        @if($det['status'] === 'hadir')
                                            <span class="px-2 py-0.5 rounded text-xs" style="background-color: #dcfce7; color: var(--success);">Hadir</span>
                                        @elseif($det['status'] === 'sakit')
                                            <span class="px-2 py-0.5 rounded text-xs" style="background-color: #e0f2fe; color: #0284c7;">Sakit</span>
                                        @elseif($det['status'] === 'izin')
                                            <span class="px-2 py-0.5 rounded text-xs" style="background-color: #fef3c7; color: #d97706;">Izin</span>
                                        @elseif($det['status'] === 'alpha')
                                            <span class="px-2 py-0.5 rounded text-xs" style="background-color: #fee2e2; color: var(--danger);">Alpha</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-xs" style="background-color: #f3f4f6; color: var(--text-muted);">Belum Ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="text-center py-10 card" style="background-color: var(--bg-card); border: 1px solid var(--border);">
                <p style="color: var(--text-muted);">Anda belum terdaftar di kelas manapun.</p>
            </div>
        @endforelse
    </div>
</div>
