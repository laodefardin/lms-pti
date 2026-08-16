<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

#[Layout('components.layouts.admin', ['title' => 'Manajemen Dosen'])]
class DosenIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editId = null;

    public $name = '';
    public $nidn = '';
    public $email = '';
    public $password = '';
    public $no_hp = '';
    public $bio = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editId', 'name', 'nidn', 'email', 'password', 'no_hp', 'bio']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $user = User::findOrFail($id);
        $this->editId = $id;
        $this->name = $user->name;
        $this->nidn = $user->nim_nidn;
        $this->email = $user->email;
        $this->password = '';
        $this->no_hp = $user->no_hp;
        $this->bio = $user->bio;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'nidn' => 'required|string|max:20|unique:users,nim_nidn,' . ($this->editId ?? 'NULL'),
            'email' => 'required|email|max:255|unique:users,email,' . ($this->editId ?? 'NULL'),
            'no_hp' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
        ];

        if (!$this->editId || $this->password) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'nim_nidn' => $this->nidn,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'bio' => $this->bio,
            'role' => 'dosen',
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if (!$this->editId) {
            $data['is_active'] = true;
        }

        User::updateOrCreate(['id' => $this->editId], $data);

        $this->showModal = false;
        $this->reset(['editId', 'name', 'nidn', 'email', 'password', 'no_hp', 'bio']);
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
        $query = User::dosen()->withCount('kelasYangDiampu');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nim_nidn', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $dosen = $query->latest()->paginate(15);
        $totalActive = User::dosen()->where('is_active', true)->count();
        $totalInactive = User::dosen()->where('is_active', false)->count();

        return view('livewire.admin.dosen-index', compact('dosen', 'totalActive', 'totalInactive'));
    }
}
