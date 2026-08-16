<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use App\Models\ForumThread;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Forum Kelas'])]
class ForumKelas extends Component
{
    public Kelas $kelas;
    public $search = '';
    public $sort = 'terbaru';
    
    public $showForm = false;
    public $judulThread = '';
    public $kontenThread = '';

    public function mount(Kelas $kelas)
    {
        $this->kelas = $kelas;
    }

    public function buatThread()
    {
        $this->showForm = true;
    }

    public function submitThread()
    {
        $this->validate([
            'judulThread' => 'required|string|max:255',
            'kontenThread' => 'required|string',
        ]);

        ForumThread::create([
            'kelas_id' => $this->kelas->id,
            'user_id' => Auth::id(),
            'judul' => $this->judulThread,
            'konten' => $this->kontenThread,
        ]);

        $this->reset(['showForm', 'judulThread', 'kontenThread']);
        session()->flash('success', 'Thread berhasil dibuat!');
    }

    public function render()
    {
        $threads = ForumThread::where('kelas_id', $this->kelas->id)
            ->when($this->search, function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('konten', 'like', '%' . $this->search . '%');
            })
            ->when($this->sort === 'terbaru', function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->when($this->sort === 'terpopuler', function ($query) {
                $query->orderBy('views', 'desc');
            })
            ->when($this->sort === 'belum_dijawab', function ($query) {
                $query->doesntHave('replies');
            })
            ->with(['user'])
            ->withCount('replies')
            ->get();

        return view('livewire.mahasiswa.forum-kelas', [
            'threads' => $threads
        ]);
    }
}
