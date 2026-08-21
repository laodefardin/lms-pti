<?php

namespace App\Livewire\Dosen;

use App\Models\Kelas;
use App\Models\KontenMateri;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class MatakuliahDetail extends Component
{
    public Kelas $kelas;
    public $activeTab = 'materi';

    public function mount(Kelas $kelas)
    {
        if ($kelas->dosen_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this class.');
        }
        
        $this->kelas = $kelas->load([
            'mataKuliah', 
            'semester', 
            'mahasiswa' => function($q) {
                // optional: add logic for progress etc if needed
            },
            'pertemuan.konten',
            'tugas.pengumpulan',
            'kuis'
        ]);
        
        if (request()->has('tab')) {
            $this->activeTab = request('tab');
        }
    }
    public $hapusPertemuanId = null;
    public $hapusKontenId    = null;

    public function konfirmasiHapusPertemuan($id)
    {
        $this->hapusPertemuanId = $id;
    }

    public function batalHapusPertemuan()
    {
        $this->hapusPertemuanId = null;
    }

    public function hapusPertemuan()
    {
        if ($this->hapusPertemuanId) {
            $pertemuan = \App\Models\Pertemuan::find($this->hapusPertemuanId);
            if ($pertemuan && $pertemuan->kelas->dosen_id === Auth::id()) {
                $pertemuan->delete();
                $this->kelas->load('pertemuan.konten'); // refresh
            }
            $this->hapusPertemuanId = null;
        }
    }

    public $hapusTugasId = null;

    public function konfirmasiHapusTugas($id)
    {
        $this->hapusTugasId = $id;
    }

    public function batalHapusTugas()
    {
        $this->hapusTugasId = null;
    }

    public function hapusTugas()
    {
        if ($this->hapusTugasId) {
            $tugas = \App\Models\Tugas::find($this->hapusTugasId);
            if ($tugas && $tugas->kelas->dosen_id === Auth::id()) {
                $tugas->delete();
                $this->kelas->load(['tugas.pengumpulan']);
                session()->flash('success', 'Tugas berhasil dihapus.');
            }
            $this->hapusTugasId = null;
        }
    }

    public function konfirmasiHapusKonten($id)
    {
        $this->hapusKontenId = $id;
    }

    public function batalHapusKonten()
    {
        $this->hapusKontenId = null;
    }

    public function hapusKonten()
    {
        if ($this->hapusKontenId) {
            $konten = \App\Models\KontenMateri::find($this->hapusKontenId);
            if ($konten && $konten->pertemuan->kelas->dosen_id === Auth::id()) {
                $konten->delete();
                $this->kelas->load('pertemuan.konten');
            }
            $this->hapusKontenId = null;
        }
    }
    
    #[Layout('components.layouts.dosen')]
    public function render()
    {
        $nilaiList = [];
        if ($this->activeTab === 'nilai') {
            $nilaiList = \App\Models\NilaiAkhir::with('mahasiswa')
                ->where('kelas_id', $this->kelas->id)
                ->get();
        }

        return view('livewire.dosen.matakuliah-detail', [
            'nilaiList' => $nilaiList,
        ])->title($this->kelas->mataKuliah->nama ?? 'Detail Matakuliah');
    }
}
