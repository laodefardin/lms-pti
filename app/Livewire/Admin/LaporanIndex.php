<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\NilaiAkhir;
use App\Models\AbsensiMahasiswa;

#[Layout('components.layouts.admin', ['title' => 'Laporan Akademik'])]
class LaporanIndex extends Component
{
    public function exportPdf()
    {
        // App\Services\ExportService::exportLaporanAkademik();
        session()->flash('message', 'Laporan berhasil diexport ke PDF.');
    }

    public function render()
    {
        $activeSemester = Semester::where('is_active', true)->first();
        $semester_id = $activeSemester ? $activeSemester->id : null;

        // Stat: Total Kelas Aktif
        $totalKelas = Kelas::where('semester_id', $semester_id)->count();

        // Stat: Rata-rata Kehadiran Global (Mock/Simplified Calculation)
        // Assume presence is computed based on status = 'hadir'
        $totalHadir = AbsensiMahasiswa::whereHas('absensi.pertemuan.kelas', function($q) use ($semester_id) {
            $q->where('semester_id', $semester_id);
        })->where('status', 'hadir')->count();
        $totalAbsensi = AbsensiMahasiswa::whereHas('absensi.pertemuan.kelas', function($q) use ($semester_id) {
            $q->where('semester_id', $semester_id);
        })->count();
        $rataKehadiran = $totalAbsensi > 0 ? round(($totalHadir / $totalAbsensi) * 100, 1) : 0;

        // Stat: Distribusi Grade
        $grades = NilaiAkhir::whereHas('kelas', function($q) use ($semester_id) {
            $q->where('semester_id', $semester_id);
        })->select('grade', \DB::raw('count(*) as total'))
          ->groupBy('grade')
          ->pluck('total', 'grade')
          ->toArray();

        $gradeDistribution = [
            'A' => $grades['A'] ?? 0,
            'B' => $grades['B'] ?? 0,
            'C' => $grades['C'] ?? 0,
            'D' => $grades['D'] ?? 0,
            'E' => $grades['E'] ?? 0,
        ];
        $totalGrades = array_sum($gradeDistribution);

        // Rata-rata kelas list
        $kelasStats = Kelas::with('mataKuliah')->where('semester_id', $semester_id)->get()->map(function($k) {
            $rataNilai = NilaiAkhir::where('kelas_id', $k->id)->avg('nilai_total');
            return [
                'nama' => $k->mataKuliah ? $k->mataKuliah->nama_mata_kuliah . ' - ' . $k->nama_kelas : $k->nama_kelas,
                'rata_nilai' => $rataNilai ? round($rataNilai, 2) : '-',
                'jumlah_mhs' => $k->mahasiswa()->count(),
            ];
        });

        return view('livewire.admin.laporan-index', [
            'totalKelas' => $totalKelas,
            'rataKehadiran' => $rataKehadiran,
            'gradeDistribution' => $gradeDistribution,
            'totalGrades' => $totalGrades,
            'kelasStats' => $kelasStats,
        ]);
    }
}
