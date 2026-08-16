<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\User;
use App\Models\Semester;

#[Layout('components.layouts.admin', ['title' => 'Manajemen Kelas'])]
class KelasIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $semesterId = '';
    public $showModal = false;
    public $editId = null;

    public $mataKuliahId = '';
    public $dosenId = '';
    public $semesterId_form = '';
    public $namaKelas = 'A';
    public $hariKuliah = '';
    public $jamMulai = '';
    public $jamSelesai = '';
    public $ruangan = '';
    public $batasKehadiran = 75;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSemesterId()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editId', 'mataKuliahId', 'dosenId', 'semesterId_form', 'namaKelas', 'hariKuliah', 'jamMulai', 'jamSelesai', 'ruangan']);
        $this->batasKehadiran = 75;
        
        // Auto-select active semester if available
        $activeSemester = Semester::where('is_aktif', true)->first();
        if ($activeSemester) {
            $this->semesterId_form = $activeSemester->id;
        }

        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $this->editId = $id;
        $this->mataKuliahId = $kelas->mata_kuliah_id;
        $this->dosenId = $kelas->dosen_id;
        $this->semesterId_form = $kelas->semester_id;
        $this->namaKelas = $kelas->nama_kelas;
        $this->hariKuliah = $kelas->hari_kuliah;
        $this->jamMulai = $kelas->jam_mulai;
        $this->jamSelesai = $kelas->jam_selesai;
        $this->ruangan = $kelas->ruangan;
        $this->batasKehadiran = $kelas->batas_kehadiran;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'mataKuliahId' => 'required|exists:mata_kuliah,id',
            'dosenId' => 'required|exists:users,id',
            'semesterId_form' => 'required|exists:semesters,id',
            'namaKelas' => 'required|string|max:5',
            'hariKuliah' => 'nullable|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'jamMulai' => 'nullable',
            'jamSelesai' => 'nullable',
            'ruangan' => 'nullable|string|max:50',
            'batasKehadiran' => 'required|integer|min:0|max:100',
        ]);

        Kelas::updateOrCreate(['id' => $this->editId], [
            'mata_kuliah_id' => $this->mataKuliahId,
            'dosen_id' => $this->dosenId,
            'semester_id' => $this->semesterId_form,
            'nama_kelas' => $this->namaKelas,
            'hari_kuliah' => $this->hariKuliah,
            'jam_mulai' => $this->jamMulai,
            'jam_selesai' => $this->jamSelesai,
            'ruangan' => $this->ruangan,
            'batas_kehadiran' => $this->batasKehadiran,
            'status' => 'aktif',
        ]);

        $this->showModal = false;
        $this->reset(['editId', 'mataKuliahId', 'dosenId', 'semesterId_form', 'namaKelas', 'hariKuliah', 'jamMulai', 'jamSelesai', 'ruangan']);
    }

    public function delete($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->status = 'arsip';
        $kelas->save();
    }

    public function render()
    {
        $query = Kelas::with(['mataKuliah', 'dosen', 'semester'])->withCount('mahasiswa');

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('mataKuliah', function ($q2) {
                    $q2->where('nama', 'like', '%' . $this->search . '%')
                       ->orWhere('kode', 'like', '%' . $this->search . '%');
                })->orWhere('nama_kelas', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->semesterId) {
            $query->where('semester_id', $this->semesterId);
        }

        $kelasList = $query->latest()->paginate(15);
        
        $mataKuliahList = MataKuliah::where('is_active', true)->get();
        $dosenList = User::dosen()->where('is_active', true)->get();
        $semesterList = Semester::orderBy('tanggal_mulai', 'desc')->get();

        return view('livewire.admin.kelas-index', compact('kelasList', 'mataKuliahList', 'dosenList', 'semesterList'));
    }
}
