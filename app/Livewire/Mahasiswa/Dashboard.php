<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Dashboard'])]
class Dashboard extends Component
{
    public function render()
    {
        $user     = Auth::user();
        $kelasList = $user->kelas()
            ->with(['mataKuliah', 'dosen', 'pertemuan.konten'])
            ->where('status', 'aktif')
            ->get();

        // Progress per kelas
        $progress = $kelasList->map(fn($k) => [
            'kelas'    => $k,
            'persen'   => $k->progressMahasiswa($user->id),
        ])->sortByDesc('persen');

        // Tugas mendekati deadline (7 hari ke depan)
        $tugas = \App\Models\Tugas::whereIn('kelas_id', $kelasList->pluck('id'))
            ->where('is_published', true)
            ->where('deadline', '>', now())
            ->where('deadline', '<', now()->addDays(7))
            ->whereDoesntHave('pengumpulan', fn($q) => $q->where('mahasiswa_id', $user->id))
            ->orderBy('deadline')
            ->with('kelas.mataKuliah')
            ->take(5)
            ->get();

        // Kuis yang buka hari ini
        $kuisTerbuka = \App\Models\Kuis::whereIn('kelas_id', $kelasList->pluck('id'))
            ->where('is_published', true)
            ->where('buka_at', '<=', now())
            ->where('tutup_at', '>=', now())
            ->with('kelas.mataKuliah')
            ->take(3)
            ->get();

        // Total materi selesai
        $totalSelesai = \App\Models\MateriProgress::where('mahasiswa_id', $user->id)
            ->where('is_selesai', true)
            ->count();

        return view('livewire.mahasiswa.dashboard', [
            'user'          => $user,
            'kelasList'     => $kelasList,
            'progress'      => $progress,
            'tugas'         => $tugas,
            'kuisTerbuka'   => $kuisTerbuka,
            'totalSelesai'  => $totalSelesai,
            'jumlahKelas'   => $kelasList->count(),
            'jumlahTugas'   => $tugas->count(),
        ]);
    }
}
