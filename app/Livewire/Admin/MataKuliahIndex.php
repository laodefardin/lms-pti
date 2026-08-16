<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\MataKuliah;

#[Layout('components.layouts.admin', ['title' => 'Manajemen Mata Kuliah'])]
class MataKuliahIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editId = null;

    public $nama = '';
    public $kode = '';
    public $sks = 3;
    public $semester = 1;
    public $deskripsi = '';
    public $isActive = true;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editId', 'nama', 'kode', 'sks', 'semester', 'deskripsi']);
        $this->isActive = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $mk = MataKuliah::findOrFail($id);
        $this->editId = $id;
        $this->nama = $mk->nama;
        $this->kode = $mk->kode;
        $this->sks = $mk->sks;
        $this->semester = $mk->semester ?? 1;
        $this->deskripsi = $mk->deskripsi;
        $this->isActive = $mk->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:10|unique:mata_kuliah,kode,' . ($this->editId ?? 'NULL'),
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'deskripsi' => 'nullable|string',
            'isActive' => 'boolean',
        ]);

        MataKuliah::updateOrCreate(['id' => $this->editId], [
            'nama' => $this->nama,
            'kode' => $this->kode,
            'sks' => $this->sks,
            'semester' => $this->semester,
            'deskripsi' => $this->deskripsi,
            'is_active' => $this->isActive,
        ]);

        $this->showModal = false;
        $this->reset(['editId', 'nama', 'kode', 'sks', 'semester', 'deskripsi']);
    }

    public function delete($id)
    {
        $mk = MataKuliah::findOrFail($id);
        $mk->is_active = false;
        $mk->save();
    }

    public function toggleActive($id)
    {
        $mk = MataKuliah::findOrFail($id);
        $mk->is_active = !$mk->is_active;
        $mk->save();
    }

    public function render()
    {
        $query = MataKuliah::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('kode', 'like', '%' . $this->search . '%');
            });
        }

        $mataKuliah = $query->latest()->paginate(15);
        return view('livewire.admin.mata-kuliah-index', compact('mataKuliah'));
    }
}
