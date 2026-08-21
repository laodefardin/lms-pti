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

    public $editMode        = false;
    public $showPasswordForm = false;

    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirmation;

    public function mount()
    {
        $user           = Auth::user();
        $this->name     = $user->name;
        $this->email    = $user->email;
        $this->no_hp    = $user->no_hp ?? '';
        $this->bio      = $user->bio ?? '';
        $this->angkatan = $user->angkatan ?? '';
    }

    public function save()
    {
        $this->validate([
            'name'    => 'required|string|max:255',
            'no_hp'   => 'nullable|string|max:20',
            'bio'     => 'nullable|string|max:500',
            'angkatan'=> 'nullable|string|max:4',
        ]);

        Auth::user()->update([
            'name'    => $this->name,
            'no_hp'   => $this->no_hp,
            'bio'     => $this->bio,
            'angkatan'=> $this->angkatan,
        ]);

        $this->editMode = false;
        session()->flash('success_profile', 'Profil berhasil diperbarui!');
    }

    public function uploadFoto()
    {
        $this->validate(['foto' => 'image|max:2048']);

        $user = Auth::user();

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $this->foto->store('profile-photos', 'public');
        $user->update(['foto' => $path]);

        $this->foto = null;
        session()->flash('success_foto', 'Foto profil berhasil diperbarui!');
    }

    public function changePassword()
    {
        $this->validate([
            'currentPassword'         => 'required',
            'newPassword'             => 'required|min:8|confirmed',
            'newPasswordConfirmation' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Password saat ini tidak cocok.');
            return;
        }

        $user->update(['password' => Hash::make($this->newPassword)]);
        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation', 'showPasswordForm']);
        session()->flash('success_password', 'Password berhasil diubah!');
    }

    public function render()
    {
        $user = Auth::user()->load('kelas.mataKuliah', 'pengumpulanTugas', 'kuisSesi');

        $stats = [
            'kelas_count'  => $user->kelas->count(),
            'tugas_selesai'=> $user->pengumpulanTugas->count(),
            'kuis_selesai' => $user->kuisSesi->where('status', 'selesai')->count(),
            'total_poin'   => $user->totalPoin(),
        ];

        return view('livewire.mahasiswa.profil', compact('user', 'stats'));
    }
}
