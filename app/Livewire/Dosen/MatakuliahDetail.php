<?php

namespace App\Livewire\Dosen;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class MatakuliahDetail extends Component
{
    public Kelas $kelas;
    public $activeTab = 'materi';

    public function mount(Kelas $kelas)
    {
        if ($kelas->dosen_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this class.');
        }
        
        $this->kelas = $kelas->load([
            'mataKuliah', 
            'semester', 
            'mahasiswa' => function($q) {
                // optional: add logic for progress etc if needed
            },
            'pertemuan.konten',
            'tugas.pengumpulan',
            'kuis'
        ]);
        
        if (request()->has('tab')) {
            $this->activeTab = request('tab');
        }
    }
    
    #[Layout('components.layouts.dosen')]
    public function render()
    {
        return view('livewire.dosen.matakuliah-detail')
            ->title($this->kelas->mataKuliah->nama ?? 'Detail Matakuliah');
    }
}
