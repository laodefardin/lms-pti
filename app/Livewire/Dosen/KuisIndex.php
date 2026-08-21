<?php

namespace App\Livewire\Dosen;

use App\Models\Kelas;
use App\Models\Kuis;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class KuisIndex extends Component
{
    public Kelas $kelas;

    public function mount(Kelas $kelas)
    {
        abort_unless($kelas->dosen_id === Auth::id(), 403);
        $this->kelas = $kelas->load(['kuis' => function($q) {
            $q->withCount(['soal', 'sesi'])->with('pertemuan');
        }]);
    }

    public function hapusKuis(int $kuisId): void
    {
        $kuis = \App\Models\Kuis::find($kuisId);
        if ($kuis && $kuis->kelas_id === $this->kelas->id) {
            $kuis->delete();
            $this->kelas->load(['kuis' => fn($q) => $q->withCount(['soal', 'sesi'])->with('pertemuan')]);
            session()->flash('success', 'Kuis berhasil dihapus.');
        }
    }

    public function togglePublish(int $kuisId): void
    {
        $kuis = \App\Models\Kuis::find($kuisId);
        if ($kuis && $kuis->kelas_id === $this->kelas->id) {
            $kuis->update(['is_published' => !$kuis->is_published]);
            $this->kelas->load(['kuis' => fn($q) => $q->withCount(['soal', 'sesi'])->with('pertemuan')]);
        }
    }

    #[Layout('components.layouts.dosen', ['title' => 'Manajemen Kuis'])]
    public function render()
    {
        $totalKuis = $this->kelas->kuis->count();
        $totalSoal = $this->kelas->kuis->sum('soal_count');
        $kuisAktif = $this->kelas->kuis->filter(fn($k) => $k->isSedangBerlangsung())->count();

        return view('livewire.dosen.kuis-index', [
            'totalKuis' => $totalKuis,
            'totalSoal' => $totalSoal,
            'kuisAktif' => $kuisAktif
        ]);
    }
}
