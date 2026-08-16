<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\{Kelas, PengumpulanTugas, KuisSesi, Tugas};

#[Layout('components.layouts.dosen', ['title' => 'Dashboard Dosen'])]
class Dashboard extends Component
{
    public function render()
    {
        $dosen = Auth::user();

        $kelasList = Kelas::where('dosen_id', $dosen->id)
            ->with(['mataKuliah', 'semester', 'mahasiswa'])
            ->where('status', 'aktif')
            ->get();

        // Tugas yang belum dinilai
        $tugasBelumDinilai = PengumpulanTugas::whereHas('tugas', fn($q) =>
                $q->whereIn('kelas_id', $kelasList->pluck('id'))
            )
            ->where('status', 'dikirim')
            ->with(['tugas.kelas.mataKuliah', 'mahasiswa'])
            ->orderBy('dikumpulkan_at', 'desc')
            ->take(5)
            ->get();

        // Total mahasiswa unik
        $totalMahasiswa = $kelasList->sum(fn($k) => $k->mahasiswa->count());

        // Tugas aktif (belum deadline)
        $tugasAktif = Tugas::whereIn('kelas_id', $kelasList->pluck('id'))
            ->where('is_published', true)
            ->where('deadline', '>', now())
            ->count();

        // Kuis aktif
        $kuisAktif = \App\Models\Kuis::whereIn('kelas_id', $kelasList->pluck('id'))
            ->where('is_published', true)
            ->where('buka_at', '<=', now())
            ->where('tutup_at', '>=', now())
            ->count();

        return view('livewire.dosen.dashboard', compact(
            'dosen', 'kelasList', 'tugasBelumDinilai',
            'totalMahasiswa', 'tugasAktif', 'kuisAktif'
        ));
    }
}
