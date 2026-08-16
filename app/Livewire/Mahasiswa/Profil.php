<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.mahasiswa', ['title' => 'Profil Saya'])]
class Profil extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $no_hp;
    public $bio;
    public $angkatan;
    public $foto;

    public $editMode = false;
    
    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirmation;
    public $showPasswordForm = false;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->no_hp = $user->no_hp ?? '';
        $this->bio = $user->bio ?? '';
        $this->angkatan = $user->angkatan ?? '';
    }

    public function edit()
    {
        $this->editMode = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'angkatan' => 'nullable|string|max:4',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'no_hp' => $this->no_hp,
            'bio' => $this->bio,
            'angkatan' => $this->angkatan,
        ]);

        $this->editMode = false;
        session()->flash('success_profile', 'Profil berhasil diperbarui!');
    }

    public function uploadFoto()
    {
        $this->validate([
            'foto' => 'image|max:2048', // 2MB Max
        ]);

        $user = Auth::user();
        
        if ($user->foto_path && Storage::exists($user->foto_path)) {
            Storage::delete($user->foto_path);
        }

        $path = $this->foto->store('profile-photos', 'public');
        
        $user->update([
            'foto_path' => $path,
            'foto_url' => Storage::url($path),
        ]);

        session()->flash('success_foto', 'Foto profil berhasil diunggah!');
    }

    public function changePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|same:newPasswordConfirmation',
            'newPasswordConfirmation' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Password saat ini tidak cocok.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation', 'showPasswordForm']);
        session()->flash('success_password', 'Password berhasil diubah!');
    }

    public function render()
    {
        $user = Auth::user();
        $stats = [
            'kelas_count' => $user->kelas()->count(),
            'tugas_selesai' => 0, // Mock for now, replace with actual relations
            'kuis_selesai' => 0,
            'materi_selesai' => 0,
        ];

        return view('livewire.mahasiswa.profil', [
            'user' => $user,
            'stats' => $stats
        ]);
    }
}
