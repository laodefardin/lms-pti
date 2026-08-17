<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Forum Diskusi'])]
class ForumIndex extends Component
{
    public $search = '';

    public function render()
    {
        $user = Auth::user();
        
        $kelasList = $user->kelas()
            ->when($this->search, function ($query) {
                $query->whereHas('mataKuliah', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('kode', 'like', '%' . $this->search . '%');
                });
            })
            ->with(['mataKuliah', 'dosen'])
            ->withCount(['forumThread'])
            ->get();

        return view('livewire.mahasiswa.forum-index', [
            'kelasList' => $kelasList
        ]);
    }
}
