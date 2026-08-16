<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\User;

#[Layout('components.layouts.admin', ['title' => 'Manajemen Kelas'])]
class KelasIndex extends Component
{
    public $semester_id;
    public $showModal = false;
    
    public $mata_kuliah_id;
    public $dosen_id;
    public $nama_kelas;
    public $editId;

    public function mount()
    {
        $activeSemester = Semester::where('is_active', true)->first();
        if ($activeSemester) {
            $this->semester_id = $activeSemester->id;
        } else {
            $latestSemester = Semester::latest()->first();
            if ($latestSemester) {
                $this->semester_id = $latestSemester->id;
            }
        }
    }

    public function resetForm()
    {
        $this->mata_kuliah_id = '';
        $this->dosen_id = '';
        $this->nama_kelas = '';
        $this->editId = null;
        $this->resetValidation();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $kelas = Kelas::findOrFail($id);
        $this->editId = $kelas->id;
        $this->mata_kuliah_id = $kelas->mata_kuliah_id;
        $this->dosen_id = $kelas->dosen_id;
        $this->nama_kelas = $kelas->nama_kelas;
        $this->showModal = true;
    }

    public function saveKelas()
    {
        $this->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:users,id',
            'nama_kelas' => 'required|string|max:255',
        ]);

        if (!$this->semester_id) {
            session()->flash('error', 'Semester tidak valid.');
            return;
        }

        Kelas::updateOrCreate(
            ['id' => $this->editId],
            [
                'semester_id' => $this->semester_id,
                'mata_kuliah_id' => $this->mata_kuliah_id,
                'dosen_id' => $this->dosen_id,
                'nama_kelas' => $this->nama_kelas,
            ]
        );

        $this->showModal = false;
        $this->resetForm();
        session()->flash('message', 'Kelas berhasil disimpan.');
    }

    public function deleteKelas($id)
    {
        Kelas::findOrFail($id)->delete();
        session()->flash('message', 'Kelas berhasil dihapus.');
    }

    public function render()
    {
        $semesters = Semester::orderBy('id', 'desc')->get();
        
        $kelases = collect();
        if ($this->semester_id) {
            $kelases = Kelas::with(['mataKuliah', 'dosen'])
                ->withCount('mahasiswa')
                ->where('semester_id', $this->semester_id)
                ->get();
        }

        $mataKuliahs = MataKuliah::all();
        $dosens = User::where('role', 'dosen')->get();

        return view('livewire.admin.kelas-index', [
            'semesters' => $semesters,
            'kelases' => $kelases,
            'mataKuliahs' => $mataKuliahs,
            'dosens' => $dosens,
        ]);
    }
}
