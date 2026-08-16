<div class="fade-in">
    <div class="mb-6">
        <h1 class="section-title">Kalender Akademik & Jadwal</h1>
        <p class="section-sub text-muted">Pantau jadwal akademik, batas waktu tugas, dan kuis.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Calendar Grid (LEFT) -->
        <div class="lg:col-span-2">
            <div class="card p-6" style="background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 0.5rem; box-shadow: var(--shadow-card);"
                 x-data="calendarData()" 
                 x-init="initCalendar()">
                
                <div class="flex items-center justify-between mb-4">
                    <button @click="prevMonth" class="p-2 rounded-md hover:bg-gray-100" style="color: var(--text-secondary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <h2 class="text-xl font-bold" style="color: var(--text-primary);" x-text="monthNames[month] + ' ' + year"></h2>
                    <button @click="nextMonth" class="p-2 rounded-md hover:bg-gray-100" style="color: var(--text-secondary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                    <template x-for="day in ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']">
                        <div class="text-xs font-semibold py-2" style="color: var(--text-muted);" x-text="day"></div>
                    </template>
                </div>

                <div class="grid grid-cols-7 gap-1">
                    <template x-for="blank in blankdays">
                        <div class="p-2 text-center border border-transparent"></div>
                    </template>
                    <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                        <div class="p-2 min-h-[80px] border rounded-md relative transition-colors" 
                             :class="{
                                'bg-teal-50 border-teal-200': isToday(date),
                                'border-gray-100 bg-white': !isToday(date)
                             }"
                             style="border-color: var(--border);">
                            <div class="text-sm font-medium" :class="isToday(date) ? 'text-teal-700' : 'text-gray-700'" x-text="date" style="color: var(--text-primary);"></div>
                            
                            <div class="mt-1 space-y-1 overflow-y-auto max-h-[50px]">
                                <template x-for="event in getEventsForDate(date)">
                                    <div class="text-[10px] leading-tight p-1 rounded truncate cursor-pointer"
                                         :class="{
                                            'bg-purple-100 text-purple-800': event.color === 'purple',
                                            'bg-blue-100 text-blue-800': event.color === 'blue',
                                            'bg-gray-100 text-gray-800': event.color === 'gray'
                                         }"
                                         :title="event.title"
                                         @click="if(event.url && event.url !== '#') window.location.href = event.url">
                                        <span x-show="event.time" x-text="event.time + ' '"></span>
                                        <span x-text="event.title"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Event List (RIGHT) -->
        <div class="lg:col-span-1">
            <div class="card p-6" style="background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 0.5rem; box-shadow: var(--shadow-card);">
                <h3 class="text-lg font-bold mb-4 border-b pb-2" style="color: var(--text-primary); border-color: var(--border);">Acara Mendatang</h3>
                
                <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                    @php
                        $upcomingEvents = collect($events)->filter(function($e) {
                            return strtotime($e['date']) >= strtotime('today');
                        })->take(10);
                    @endphp

                    @forelse($upcomingEvents as $e)
                        <div class="p-3 rounded-md border flex items-start" style="border-color: var(--border); background-color: #f8fafc;">
                            <div class="mr-3 mt-1">
                                @if($e['color'] === 'purple')
                                    <div class="w-3 h-3 rounded-full" style="background-color: #9333ea;"></div>
                                @elseif($e['color'] === 'blue')
                                    <div class="w-3 h-3 rounded-full" style="background-color: #3b82f6;"></div>
                                @else
                                    <div class="w-3 h-3 rounded-full" style="background-color: #6b7280;"></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold" style="color: var(--text-primary);">{{ $e['title'] }}</p>
                                <p class="text-xs mt-1" style="color: var(--text-secondary);">
                                    {{ \Carbon\Carbon::parse($e['date'])->format('d M Y') }}
                                    @if(isset($e['time'])) - {{ $e['time'] }} @endif
                                </p>
                                @if(isset($e['url']) && $e['url'] !== '#')
                                    <a href="{{ $e['url'] }}" class="text-[10px] mt-2 inline-block hover:underline" style="color: var(--teal);">Lihat Detail &rarr;</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-center py-4" style="color: var(--text-muted);">Tidak ada acara mendatang.</p>
                    @endforelse
                </div>

                <div class="mt-6 pt-4 border-t" style="border-color: var(--border);">
                    <p class="text-xs font-semibold mb-2" style="color: var(--text-secondary);">Keterangan:</p>
                    <div class="flex items-center text-xs mb-1"><div class="w-2 h-2 rounded-full mr-2" style="background-color: #9333ea;"></div> Tugas</div>
                    <div class="flex items-center text-xs mb-1"><div class="w-2 h-2 rounded-full mr-2" style="background-color: #3b82f6;"></div> Kuis</div>
                    <div class="flex items-center text-xs"><div class="w-2 h-2 rounded-full mr-2" style="background-color: #6b7280;"></div> Kalender Akademik</div>
                </div>
            </div>
        </div>
    </div>

    <!-- AlpineJS Data -->
    <script>
        function calendarData() {
            return {
                month: '',
                year: '',
                no_of_days: [],
                blankdays: [],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                events: {!! $eventsJson !!},
                
                initCalendar() {
                    let today = new Date();
                    this.month = today.getMonth();
                    this.year = today.getFullYear();
                    this.getNoOfDays();
                },
                
                isToday(date) {
                    const today = new Date();
                    const d = new Date(this.year, this.month, date);
                    return today.toDateString() === d.toDateString();
                },
                
                getEventsForDate(date) {
                    let d = new Date(this.year, this.month, date);
                    let dateStr = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                    return this.events.filter(e => e.date === dateStr);
                },
                
                getNoOfDays() {
                    let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                    let dayOfWeek = new Date(this.year, this.month).getDay();
                    let blankdaysArray = [];
                    for (var i = 1; i <= dayOfWeek; i++) {
                        blankdaysArray.push(i);
                    }
                    let daysArray = [];
                    for (var i = 1; i <= daysInMonth; i++) {
                        daysArray.push(i);
                    }
                    this.blankdays = blankdaysArray;
                    this.no_of_days = daysArray;
                },
                
                nextMonth() {
                    if (this.month == 11) {
                        this.month = 0;
                        this.year++;
                    } else {
                        this.month++;
                    }
                    this.getNoOfDays();
                },
                
                prevMonth() {
                    if (this.month == 0) {
                        this.month = 11;
                        this.year--;
                    } else {
                        this.month--;
                    }
                    this.getNoOfDays();
                }
            }
        }
    </script>
</div>
