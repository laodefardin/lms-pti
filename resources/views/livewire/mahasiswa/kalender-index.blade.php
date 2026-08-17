<div class="fade-in">
    <div style="margin-bottom: 1.5rem;">
        <h1 class="section-title">Kalender Akademik & Jadwal</h1>
        <p class="section-sub text-muted">Pantau jadwal akademik, batas waktu tugas, dan kuis.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- Calendar Grid (LEFT) -->
        <div style="grid-column: span 2;">
            <div class="card" style="padding: 1.5rem;"
                 x-data="calendarData()" 
                 x-init="initCalendar()">
                
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <button @click="prevMonth" style="padding: 0.5rem; border-radius: 0.375rem; background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h2 style="font-size: 1.25rem; font-weight: bold; color: var(--text-primary); margin: 0;" x-text="monthNames[month] + ' ' + year"></h2>
                    <button @click="nextMonth" style="padding: 0.5rem; border-radius: 0.375rem; background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.25rem; text-align: center; margin-bottom: 0.5rem;">
                    <template x-for="day in ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']">
                        <div style="font-size: 0.75rem; font-weight: 600; padding: 0.5rem 0; color: var(--text-muted);" x-text="day"></div>
                    </template>
                </div>

                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.25rem;">
                    <template x-for="blank in blankdays">
                        <div style="padding: 0.5rem; text-align: center; border: 1px solid transparent;"></div>
                    </template>
                    <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                        <div style="padding: 0.5rem; min-height: 5rem; border: 1px solid var(--border); border-radius: 0.375rem; position: relative; transition: background-color 0.2s;" 
                             :style="isToday(date) ? 'background-color: var(--teal-light); border-color: var(--border-teal);' : 'background-color: var(--bg-card);'">
                            <div style="font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;" 
                                 :style="isToday(date) ? 'color: var(--teal-dark);' : 'color: var(--text-primary);'" x-text="date"></div>
                            
                            <div style="margin-top: 0.25rem; display: flex; flex-direction: column; gap: 0.25rem; overflow-y: auto; max-height: 3.125rem;">
                                <template x-for="event in getEventsForDate(date)">
                                    <div style="font-size: 0.625rem; line-height: 1.25; padding: 0.25rem; border-radius: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;"
                                         :style="event.color === 'purple' ? 'background-color: rgba(147, 51, 234, 0.1); color: #6b21a8;' : (event.color === 'blue' ? 'background-color: rgba(59, 130, 246, 0.1); color: #1e40af;' : 'background-color: var(--input-bg); color: var(--text-secondary);')"
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
        <div>
            <div class="card" style="padding: 1.5rem;">
                <h3 style="font-size: 1.125rem; font-weight: bold; margin: 0 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); color: var(--text-primary);">Acara Mendatang</h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem; max-height: 37.5rem; overflow-y: auto; padding-right: 0.5rem;">
                    @php
                        $upcomingEvents = collect($events)->filter(function($e) {
                            return strtotime($e['date']) >= strtotime('today');
                        })->take(10);
                    @endphp

                    @forelse($upcomingEvents as $e)
                        <div style="padding: 0.75rem; border-radius: 0.375rem; border: 1px solid var(--border); background-color: var(--input-bg); display: flex; align-items: flex-start;">
                            <div style="margin-right: 0.75rem; margin-top: 0.25rem;">
                                @if($e['color'] === 'purple')
                                    <div style="width: 0.75rem; height: 0.75rem; border-radius: 50%; background-color: #9333ea;"></div>
                                @elseif($e['color'] === 'blue')
                                    <div style="width: 0.75rem; height: 0.75rem; border-radius: 50%; background-color: #3b82f6;"></div>
                                @else
                                    <div style="width: 0.75rem; height: 0.75rem; border-radius: 50%; background-color: #6b7280;"></div>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 0.875rem; font-weight: 600; margin: 0; color: var(--text-primary);">{{ $e['title'] }}</p>
                                <p style="font-size: 0.75rem; margin: 0.25rem 0 0 0; color: var(--text-secondary);">
                                    {{ \Carbon\Carbon::parse($e['date'])->format('d M Y') }}
                                    @if(isset($e['time'])) - {{ $e['time'] }} @endif
                                </p>
                                @if(isset($e['url']) && $e['url'] !== '#')
                                    <a href="{{ $e['url'] }}" style="font-size: 0.625rem; margin-top: 0.5rem; display: inline-block; color: var(--teal); text-decoration: none;">Lihat Detail &rarr;</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p style="font-size: 0.875rem; text-align: center; padding: 1rem 0; margin: 0; color: var(--text-muted);">Tidak ada acara mendatang.</p>
                    @endforelse
                </div>

                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                    <p style="font-size: 0.75rem; font-weight: 600; margin: 0 0 0.5rem 0; color: var(--text-secondary);">Keterangan:</p>
                    <div style="display: flex; align-items: center; font-size: 0.75rem; margin-bottom: 0.25rem;"><div style="width: 0.5rem; height: 0.5rem; border-radius: 50%; margin-right: 0.5rem; background-color: #9333ea;"></div> Tugas</div>
                    <div style="display: flex; align-items: center; font-size: 0.75rem; margin-bottom: 0.25rem;"><div style="width: 0.5rem; height: 0.5rem; border-radius: 50%; margin-right: 0.5rem; background-color: #3b82f6;"></div> Kuis</div>
                    <div style="display: flex; align-items: center; font-size: 0.75rem;"><div style="width: 0.5rem; height: 0.5rem; border-radius: 50%; margin-right: 0.5rem; background-color: #6b7280;"></div> Kalender Akademik</div>
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
                events: {!! $eventsJson ?? '[]' !!},
                
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
