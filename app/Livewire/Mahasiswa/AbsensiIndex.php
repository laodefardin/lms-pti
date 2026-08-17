<?php

namespace App\Livewire\Mahasiswa;

use App\Models\Kelas;
use App\Models\AbsensiMahasiswa;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Riwayat Absensi'])]
class AbsensiIndex extends Component
{
    public function render()
    {
        $user = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $kelasList = Kelas::whereIn('id', $kelasIds)
            ->with(['mataKuliah', 'dosen', 'pertemuan.absensi'])
            ->get();
            
        $absensiMahasiswa = AbsensiMahasiswa::where('mahasiswa_id', $user->id)->get()->keyBy('absensi_id');
            
        $dataKehadiran = [];

        foreach ($kelasList as $k) {
            $hadir = 0; $sakit = 0; $izin = 0; $alpha = 0;
            $totalPertemuan = $k->pertemuan->count();
            
            $pertemuanDetail = [];

            foreach ($k->pertemuan as $p) {
                $abs = $p->absensi;
                $status = 'belum_ada';
                
                if ($abs && isset($absensiMahasiswa[$abs->id])) {
                    $status = $absensiMahasiswa[$abs->id]->status;
                }
                
                if ($status === 'hadir') $hadir++;
                elseif ($status === 'sakit') $sakit++;
                elseif ($status === 'izin') $izin++;
                elseif ($status === 'alpha') $alpha++;

                $pertemuanDetail[] = [
                    'pertemuan_ke' => $p->pertemuan_ke,
                    'tanggal' => $p->tanggal,
                    'materi' => $p->judul,
                    'status' => $status
                ];
            }

            $persentase = $totalPertemuan > 0 ? ($hadir / $totalPertemuan) * 100 : 0;

            $dataKehadiran[] = [
                'kelas' => $k,
                'total' => $totalPertemuan,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpha' => $alpha,
                'persentase' => $persentase,
                'detail' => $pertemuanDetail
            ];
        }

        return view('livewire.mahasiswa.absensi-index', [
            'dataKehadiran' => $dataKehadiran
        ]);
    }
}
