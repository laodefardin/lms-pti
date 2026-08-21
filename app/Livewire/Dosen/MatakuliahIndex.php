<?php

namespace App\Livewire\Dosen;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.dosen', ['title' => 'Matakuliah Saya'])]
class MatakuliahIndex extends Component
{
    public $search = '';
    public $hapusId = null;

    public function konfirmasiHapus($id)
    {
        $this->hapusId = $id;
    }

    public function batalHapus()
    {
        $this->hapusId = null;
    }

    public function hapusKelas()
    {
        $kelas = Kelas::where('id', $this->hapusId)
                      ->where('dosen_id', auth()->id())
                      ->first();

        if ($kelas) {
            $nama = $kelas->nama_kelas;
            $kelas->delete();
            session()->flash('success', 'Kelas "' . $nama . '" berhasil dihapus.');
        }

        $this->hapusId = null;
    }

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
