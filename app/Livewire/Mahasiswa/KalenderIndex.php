<?php

namespace App\Livewire\Mahasiswa;

use App\Models\KalenderAkademik;
use App\Models\Tugas;
use App\Models\Kuis;
use App\Models\Semester;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Layout('components.layouts.mahasiswa', ['title' => 'Kalender Akademik'])]
class KalenderIndex extends Component
{
    public function render()
    {
        $user = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');
        
        $activeSemester = Semester::where('is_aktif', true)->first();
        
        $events = [];

        if ($activeSemester) {
            $kalender = KalenderAkademik::where('semester_id', $activeSemester->id)->get();
            foreach ($kalender as $k) {
                $events[] = [
                    'id' => 'ka_'.$k->id,
                    'title' => $k->nama_kegiatan,
                    'date' => $k->tanggal_mulai,
                    'end_date' => $k->tanggal_selesai,
                    'type' => 'akademik',
                    'color' => 'gray'
                ];
            }
        }

        $tugas = Tugas::whereIn('kelas_id', $kelasIds)
            ->where('is_published', true)
            ->where('deadline', '>=', now()->subDays(7))
            ->with('kelas.mataKuliah')
            ->get();
            
        foreach ($tugas as $t) {
            $events[] = [
                'id' => 'tugas_'.$t->id,
                'title' => 'Tugas: ' . $t->judul . ' (' . $t->kelas->mataKuliah->nama_mk . ')',
                'date' => Carbon::parse($t->deadline)->format('Y-m-d'),
                'time' => Carbon::parse($t->deadline)->format('H:i'),
                'type' => 'tugas',
                'color' => 'purple',
                'url' => route('mahasiswa.tugas.detail', $t->id)
            ];
        }

        $kuis = Kuis::whereIn('kelas_id', $kelasIds)
            ->where('is_published', true)
            ->where('buka_at', '>=', now()->subDays(7))
            ->with('kelas.mataKuliah')
            ->get();
            
        foreach ($kuis as $k) {
            $events[] = [
                'id' => 'kuis_'.$k->id,
                'title' => 'Kuis: ' . $k->judul . ' (' . $k->kelas->mataKuliah->nama_mk . ')',
                'date' => Carbon::parse($k->buka_at)->format('Y-m-d'),
                'time' => Carbon::parse($k->buka_at)->format('H:i'),
                'type' => 'kuis',
                'color' => 'blue',
                'url' => '#'
            ];
        }

        usort($events, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        return view('livewire.mahasiswa.kalender-index', [
            'events' => $events,
            'eventsJson' => json_encode($events)
        ]);
    }
}
