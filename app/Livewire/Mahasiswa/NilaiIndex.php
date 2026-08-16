<?php

namespace App\Livewire\Mahasiswa;

use App\Models\NilaiAkhir;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Nilai Saya'])]
class NilaiIndex extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        $nilai = NilaiAkhir::where('mahasiswa_id', $user->id)
            ->with(['kelas.mataKuliah', 'kelas.dosen'])
            ->get();

        $totalMk = $nilai->count();
        $rataRata = $totalMk > 0 ? $nilai->avg('nilai_akhir') : 0;
        
        $nilaiTerbaik = null;
        if ($totalMk > 0) {
            $best = $nilai->sortByDesc('nilai_akhir')->first();
            $nilaiTerbaik = $best->kelas->mataKuliah->nama_mk . ' (' . number_format($best->nilai_akhir, 2) . ')';
        }

        return view('livewire.mahasiswa.nilai-index', [
            'nilai' => $nilai,
            'totalMk' => $totalMk,
            'rataRata' => $rataRata,
            'nilaiTerbaik' => $nilaiTerbaik,
        ]);
    }
}
