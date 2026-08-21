<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{Kelas, Tugas};

class TugasDetail extends Component
{
    public Kelas $kelas;
    public Tugas $tugas;

    public function mount(Kelas $kelas, Tugas $tugas)
    {
        if ($kelas->dosen_id !== auth()->id() || $tugas->kelas_id !== $kelas->id) {
            abort(403);
        }
        $this->kelas = $kelas;
        $this->tugas = $tugas->load('pengumpulan.mahasiswa');
    }

    public function togglePublish(): void
    {
        $this->tugas->update(['is_published' => !$this->tugas->is_published]);
        $this->tugas->refresh();
    }

    #[Layout('components.layouts.dosen', ['title' => 'Detail Tugas'])]
    public function render()
    {
        return view('livewire.dosen.tugas-detail', [
            'pengumpulans' => $this->tugas->pengumpulan,
            'totalMahasiswa' => $this->kelas->mahasiswa()->count(),
        ]);
    }
}
