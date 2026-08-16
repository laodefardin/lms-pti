<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Matakuliah Saya'])]
class MatakuliahIndex extends Component
{
    public string $search = '';

    public function render()
    {
        $user = Auth::user();

        $kelasList = $user->kelas()
            ->with(['mataKuliah', 'dosen', 'pertemuan.konten', 'semester'])
            ->where('status', 'aktif')
            ->when($this->search, fn($q) =>
                $q->whereHas('mataKuliah', fn($r) =>
                    $r->where('nama', 'like', '%'.$this->search.'%')
                )
            )
            ->get()
            ->map(fn($k) => [
                'kelas'  => $k,
                'persen' => $k->progressMahasiswa($user->id),
            ]);

        return view('livewire.mahasiswa.matakuliah-index', compact('kelasList'));
    }
}
