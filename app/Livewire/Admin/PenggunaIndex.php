<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use App\Services\ImportService;

#[Layout('components.layouts.admin', ['title' => 'Manajemen Pengguna'])]
class PenggunaIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $role = 'mahasiswa';
    public $showModal = false;
    
    public $name;
    public $email;
    public $nim;
    public $angkatan;
    public $editId;
    public $fileUpload;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRole()
    {
        $this->resetPage();
    }

    public function setRole($role)
    {
        $this->role = $role;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->nim = '';
        $this->angkatan = '';
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
        $user = User::findOrFail($id);
        $this->editId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nim = $user->nim ?? $user->nidn;
        $this->angkatan = $user->angkatan;
        $this->showModal = true;
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($this->editId ? ',' . $this->editId : ''),
            'nim' => 'nullable|string|max:255',
            'angkatan' => 'nullable|string|max:4',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->role === 'mahasiswa') {
            $data['nim'] = $this->nim;
            $data['angkatan'] = $this->angkatan;
        } else {
            $data['nidn'] = $this->nim;
        }

        if (!$this->editId) {
            $data['password'] = Hash::make('password'); // Default password
        }

        User::updateOrCreate(['id' => $this->editId], $data);

        $this->showModal = false;
        $this->resetForm();
        session()->flash('message', 'Pengguna berhasil disimpan.');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'Pengguna berhasil dihapus.');
    }

    public function importUsers()
    {
        $this->validate([
            'fileUpload' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        // App\Services\ImportService::import($this->fileUpload, $this->role);
        session()->flash('message', 'Fitur import pengguna berhasil (stub).');
        $this->fileUpload = null;
    }

    public function render()
    {
        $users = User::where('role', $this->role)
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nim', 'like', '%' . $this->search . '%')
                      ->orWhere('nidn', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.pengguna-index', compact('users'));
    }
}
