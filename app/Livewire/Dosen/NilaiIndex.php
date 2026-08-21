<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use App\Models\NilaiAkhir;
use App\Services\ExportService;
use Illuminate\Support\Facades\Auth;

class NilaiIndex extends Component
{
    public Kelas $kelas;

    public function mount(Kelas $kelas)
    {
        abort_unless($kelas->dosen_id === Auth::id(), 403);
        $this->kelas = $kelas;
    }

    public function exportExcel(ExportService $exportService)
    {
        return $exportService->exportNilaiKelasExcel($this->kelas->id);
    }

    #[Layout('components.layouts.dosen', ['title' => 'Rekapitulasi Nilai'])]
    public function render()
    {
        $nilaiList = NilaiAkhir::with('mahasiswa')
            ->where('kelas_id', $this->kelas->id)
            ->get();

        return view('livewire.dosen.nilai-index', [
            'nilaiList' => $nilaiList
        ]);
    }
}
