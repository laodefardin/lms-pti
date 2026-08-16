<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\{Kuis, KuisSesi, KuisSoal, KuisJawaban};

#[Layout('components.layouts.mahasiswa', ['title' => 'Kuis & Ujian'])]
class KuisIndex extends Component
{
    public function render()
    {
        $user = Auth::user();

        $kelasList = $user->kelas()->with(['mataKuliah'])->where('status', 'aktif')->get();
        $kelasIds  = $kelasList->pluck('id');

        $kuisList = Kuis::whereIn('kelas_id', $kelasIds)
            ->where('is_published', true)
            ->with(['kelas.mataKuliah'])
            ->orderBy('buka_at', 'desc')
            ->get()
            ->map(function($kuis) use ($user) {
                $sesi = KuisSesi::where('kuis_id', $kuis->id)
                    ->where('mahasiswa_id', $user->id)
                    ->latest()
                    ->first();
                return [
                    'kuis'   => $kuis,
                    'sesi'   => $sesi,
                    'status' => $this->statusKuis($kuis, $sesi),
                ];
            });

        return view('livewire.mahasiswa.kuis-index', compact('kuisList'));
    }

    private function statusKuis(Kuis $kuis, ?KuisSesi $sesi): string
    {
        if (now() < $kuis->buka_at) return 'belum_buka';
        if (now() > $kuis->tutup_at) {
            return $sesi ? 'selesai' : 'kadaluarsa';
        }
        if (!$sesi) return 'bisa_mulai';
        if ($sesi->status === 'selesai') return 'selesai';
        return 'sedang_berjalan';
    }
}
