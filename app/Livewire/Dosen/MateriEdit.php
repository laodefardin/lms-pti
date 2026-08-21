<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{Kelas, Pertemuan};
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.dosen', ['title' => 'Edit Pertemuan'])]
class MateriEdit extends Component
{
    public Kelas $kelas;
    public Pertemuan $pertemuan;

    public $nomor;
    public $topik;
    public $tanggal;
    public $deskripsi;

    public function mount(Kelas $kelas, Pertemuan $pertemuan)
    {
        abort_unless($kelas->dosen_id === Auth::id(), 403);
        
        $this->kelas = $kelas;
        $this->pertemuan = $pertemuan;

        $this->nomor = $pertemuan->nomor;
        $this->topik = $pertemuan->topik;
        $this->tanggal = $pertemuan->tanggal ? \Carbon\Carbon::parse($pertemuan->tanggal)->format('Y-m-d') : null;
        $this->deskripsi = $pertemuan->deskripsi;
    }

    public function simpan()
    {
        $this->validate([
            'nomor' => 'required|integer|min:1',
            'topik' => 'required|string|max:200',
            'tanggal' => 'nullable|date',
            'deskripsi' => 'nullable|string',
        ]);

        $this->pertemuan->update([
            'nomor' => $this->nomor,
            'topik' => $this->topik,
            'tanggal' => $this->tanggal,
            'deskripsi' => $this->deskripsi,
        ]);

        session()->flash('message', 'Pertemuan berhasil diperbarui.');
        return $this->redirect(route('dosen.matakuliah.detail', $this->kelas), navigate: true);
    }

    public function render()
    {
        return view('livewire.dosen.materiedit');
    }
}
