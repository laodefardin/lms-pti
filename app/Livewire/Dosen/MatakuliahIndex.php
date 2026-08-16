<?php

namespace App\Livewire\Dosen;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.dosen', ['title' => 'Matakuliah Saya'])]
class MatakuliahIndex extends Component
{
    public $search = '';

    public function render()
    {
        $kelasList = Kelas::with(['mataKuliah', 'semester'])
            ->withCount('mahasiswa')
            ->where('dosen_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->whereHas('mataKuliah', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('kode', 'like', '%' . $this->search . '%');
                });
            })
            ->get();

        return view('livewire.dosen.matakuliah-index', [
            'kelasList' => $kelasList
        ]);
    }
}
