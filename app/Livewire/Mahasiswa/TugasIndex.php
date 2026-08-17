<?php

namespace App\Livewire\Mahasiswa;

use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Tugas & Pengumpulan'])]
class TugasIndex extends Component
{
    public $filter = 'semua';

    public function render()
    {
        $user = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $query = Tugas::whereIn('kelas_id', $kelasIds)
            ->where('is_published', true)
            ->with(['kelas.mataKuliah', 'pengumpulanTugas' => function($q) use ($user) {
                $q->where('mahasiswa_id', $user->id);
            }])
            ->orderBy('deadline', 'asc');

        $tugas = $query->get()->filter(function($t) {
            $pengumpulan = $t->pengumpulanTugas->first();
            if ($this->filter === 'pending') {
                return !$pengumpulan;
            } elseif ($this->filter === 'dikumpulkan') {
                return $pengumpulan && $pengumpulan->status !== 'dinilai';
            } elseif ($this->filter === 'dinilai') {
                return $pengumpulan && $pengumpulan->status === 'dinilai';
            }
            return true;
        });

        return view('livewire.mahasiswa.tugas-index', [
            'tugas' => $tugas
        ]);
    }
}
