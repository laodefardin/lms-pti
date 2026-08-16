<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use App\Models\NilaiAkhir;
use App\Services\ExportService;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.dosen', ['title' => 'Rekapitulasi Nilai'])]
class NilaiIndex extends Component
{
    public $kelasId;
    public $kelasList;

    public function mount()
    {
        $this->kelasList = Kelas::with('mataKuliah')
            ->where('dosen_id', Auth::id())
            ->where('status', 'aktif')
            ->get();
            
        if ($this->kelasList->isNotEmpty()) {
            $this->kelasId = $this->kelasList->first()->id;
        }
    }

    public function exportExcel(ExportService $exportService)
    {
        if ($this->kelasId) {
            return $exportService->exportNilaiKelasExcel($this->kelasId);
        }
    }

    public function render()
    {
        $nilaiList = collect();
        if ($this->kelasId) {
            $nilaiList = NilaiAkhir::with('mahasiswa')
                ->where('kelas_id', $this->kelasId)
                ->get();
        }

        return view('livewire.dosen.nilai-index', [
            'nilaiList' => $nilaiList
        ]);
    }
}
