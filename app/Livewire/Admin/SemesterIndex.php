<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Semester;

#[Layout('components.layouts.admin', ['title' => 'Manajemen Semester'])]
class SemesterIndex extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editId = null;

    public $nama = '';
    public $tahunAjaran = '';
    public $tipe = 'ganjil';
    public $mulaiAt = '';
    public $selesaiAt = '';
    public $isAktif = false;

    public function openCreate()
    {
        $this->reset(['editId', 'nama', 'tahunAjaran', 'tipe', 'mulaiAt', 'selesaiAt', 'isAktif']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $semester = Semester::findOrFail($id);
        $this->editId = $id;
        $this->nama = $semester->nama;
        $this->tahunAjaran = $semester->tahun_akademik;
        $this->tipe = $semester->tipe;
        $this->mulaiAt = $semester->tanggal_mulai ? \Carbon\Carbon::parse($semester->tanggal_mulai)->format('Y-m-d') : '';
        $this->selesaiAt = $semester->tanggal_selesai ? \Carbon\Carbon::parse($semester->tanggal_selesai)->format('Y-m-d') : '';
        $this->isAktif = $semester->is_aktif;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'tahunAjaran' => 'required|string|max:9',
            'tipe' => 'required|in:ganjil,genap',
            'mulaiAt' => 'required|date',
            'selesaiAt' => 'required|date|after:mulaiAt',
        ]);

        if ($this->isAktif) {
            Semester::where('is_aktif', true)->update(['is_aktif' => false]);
        }

        Semester::updateOrCreate(['id' => $this->editId], [
            'nama' => $this->nama,
            'tahun_akademik' => $this->tahunAjaran,
            'tipe' => $this->tipe,
            'tanggal_mulai' => $this->mulaiAt,
            'tanggal_selesai' => $this->selesaiAt,
            'is_aktif' => $this->isAktif,
        ]);

        $this->showModal = false;
        $this->reset(['editId', 'nama', 'tahunAjaran', 'tipe', 'mulaiAt', 'selesaiAt', 'isAktif']);
    }

    public function setAktif($id)
    {
        Semester::where('is_aktif', true)->update(['is_aktif' => false]);
        Semester::where('id', $id)->update(['is_aktif' => true]);
    }

    public function render()
    {
        $semesters = Semester::orderBy('tanggal_mulai', 'desc')->paginate(15);
        return view('livewire.admin.semester-index', compact('semesters'));
    }
}
