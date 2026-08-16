<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

#[Layout('components.layouts.admin', ['title' => 'Manajemen Mahasiswa'])]
class MahasiswaIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $angkatan = '';
    public $showModal = false;
    public $editId = null;

    public $name = '';
    public $nim = '';
    public $email = '';
    public $password = '';
    public $angkatan_input = '';
    public $no_hp = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingAngkatan()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editId', 'name', 'nim', 'email', 'password', 'angkatan_input', 'no_hp']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $user = User::findOrFail($id);
        $this->editId = $id;
        $this->name = $user->name;
        $this->nim = $user->nim_nidn;
        $this->email = $user->email;
        $this->password = '';
        $this->angkatan_input = $user->angkatan;
        $this->no_hp = $user->no_hp;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:users,nim_nidn,' . ($this->editId ?? 'NULL'),
            'email' => 'required|email|max:255|unique:users,email,' . ($this->editId ?? 'NULL'),
            'angkatan_input' => 'nullable|integer',
            'no_hp' => 'nullable|string|max:20',
        ];

        if (!$this->editId || $this->password) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'nim_nidn' => $this->nim,
            'email' => $this->email,
            'angkatan' => $this->angkatan_input,
            'no_hp' => $this->no_hp,
            'role' => 'mahasiswa',
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if (!$this->editId) {
            $data['is_active'] = true;
        }

        User::updateOrCreate(['id' => $this->editId], $data);

        $this->showModal = false;
        $this->reset(['editId', 'name', 'nim', 'email', 'password', 'angkatan_input', 'no_hp']);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = false;
        $user->save();
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
    }

    public function render()
    {
        $query = User::where('role', 'mahasiswa');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nim_nidn', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->angkatan) {
            $query->where('angkatan', $this->angkatan);
        }

        $mahasiswa = $query->latest()->paginate(15);
        $totalActive = User::where('role', 'mahasiswa')->where('is_active', true)->count();
        $totalInactive = User::where('role', 'mahasiswa')->where('is_active', false)->count();

        return view('livewire.admin.mahasiswa-index', compact('mahasiswa', 'totalActive', 'totalInactive'));
    }
}
