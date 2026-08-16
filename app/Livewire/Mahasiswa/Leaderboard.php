<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Leaderboard'])]
class Leaderboard extends Component
{
    public $filter = 'semua'; // semua, kelas_saya

    public function render()
    {
        $user = Auth::user();

        $query = User::role('mahasiswa');
        
        if ($this->filter === 'kelas_saya') {
            $kelasIds = $user->mahasiswaKelas()->pluck('kelas_id');
            $query->whereHas('mahasiswaKelas', function($q) use ($kelasIds) {
                $q->whereIn('kelas_id', $kelasIds);
            });
        }

        $mahasiswaList = $query->withSum('gamifikasiPoin', 'poin')
            ->orderByDesc('gamifikasi_poin_sum_poin')
            ->get();

        $mahasiswaList->each(function($mhs, $index) {
            $mhs->rank = $index + 1;
            $mhs->total_poin = $mhs->gamifikasi_poin_sum_poin ?? 0;
        });

        $myRank = $mahasiswaList->firstWhere('id', $user->id);

        $top3 = $mahasiswaList->take(3);
        $others = $mahasiswaList->skip(3);

        return view('livewire.mahasiswa.leaderboard', [
            'myRank' => $myRank,
            'top3' => $top3,
            'others' => $others
        ]);
    }
}
