<?php

namespace App\Livewire\Dosen;

use App\Models\Kelas;
use App\Models\Tugas;
use Livewire\Component;
use Livewire\Attributes\Layout;

class TugasIndex extends Component
{
    public Kelas $kelas;

    public function mount(Kelas $kelas)
    {
        if ($kelas->dosen_id !== auth()->id()) {
            abort(403);
        }
        $this->kelas = $kelas->load('tugas.pengumpulan', 'tugas.pertemuan', 'mahasiswa');
    }

    public function hapusTugas(int $tugasId): void
    {
        $tugas = Tugas::find($tugasId);
        if ($tugas && $tugas->kelas_id === $this->kelas->id) {
            $tugas->delete();
            $this->kelas->load('tugas.pengumpulan', 'tugas.pertemuan', 'mahasiswa');
            session()->flash('success', 'Tugas berhasil dihapus.');
        }
    }

    #[Layout('components.layouts.dosen', ['title' => 'Manajemen Tugas'])]
    public function render()
    {
        $totalMahasiswa = $this->kelas->mahasiswa->count();
        $totalTugas = $this->kelas->tugas->count();
        $totalPengumpulan = 0;
        $belumDinilai = 0;

        foreach ($this->kelas->tugas as $tugas) {
            $pengumpulan = $tugas->pengumpulan;
            $totalPengumpulan += $pengumpulan->count();
            $belumDinilai += $pengumpulan->whereNull('nilai')->count();
        }

        return view('livewire.dosen.tugas-index', [
            'totalMahasiswa' => $totalMahasiswa,
            'totalTugas' => $totalTugas,
            'totalPengumpulan' => $totalPengumpulan,
            'belumDinilai' => $belumDinilai
        ]);
    }
}
