<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{User, Kelas, Semester, MataKuliah, PengumpulanTugas};

#[Layout('components.layouts.admin', ['title' => 'Admin Dashboard'])]
class Dashboard extends Component
{
    public function render()
    {
        $totalMahasiswa  = User::where('role', 'mahasiswa')->where('is_active', true)->count();
        $totalDosen      = User::where('role', 'dosen')->where('is_active', true)->count();
        $totalKelas      = Kelas::where('status', 'aktif')->count();
        $totalMk         = MataKuliah::where('is_active', true)->count();
        $semesterAktif   = Semester::where('is_aktif', true)->first();

        // Kelas aktif dengan dosen
        $kelasList = Kelas::with(['mataKuliah', 'dosen', 'mahasiswa'])
            ->where('status', 'aktif')
            ->get();

        // Tugas belum dinilai global
        $belumDinilai = PengumpulanTugas::where('status', 'dikirim')->count();

        // Mahasiswa baru (7 hari terakhir)
        $mahasiswaBaru = User::where('role', 'mahasiswa')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return view('livewire.admin.dashboard', compact(
            'totalMahasiswa', 'totalDosen', 'totalKelas', 'totalMk',
            'semesterAktif', 'kelasList', 'belumDinilai', 'mahasiswaBaru'
        ));
    }
}
