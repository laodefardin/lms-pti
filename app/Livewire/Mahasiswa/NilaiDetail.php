<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use App\Models\NilaiAkhir;
use App\Models\PengumpulanTugas;
use App\Models\KuisSesi;
use App\Models\AbsensiMahasiswa;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Rincian Nilai'])]
class NilaiDetail extends Component
{
    public Kelas $kelas;

    public function mount(Kelas $kelas)
    {
        $this->kelas = $kelas->load('mataKuliah', 'dosen');
        
        // Verifikasi apakah mahasiswa terdaftar di kelas ini
        $isEnrolled = $this->kelas->mahasiswa()->where('mahasiswa_id', Auth::id())->exists();
        if (!$isEnrolled) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }
    }

    public function render()
    {
        $user = Auth::user();

        // 1. Nilai Akhir
        $nilaiAkhir = NilaiAkhir::where('kelas_id', $this->kelas->id)
                                ->where('mahasiswa_id', $user->id)
                                ->first();

        // 2. Kehadiran
        $totalPertemuan = $this->kelas->pertemuan()->count();
        $absensi = AbsensiMahasiswa::whereHas('absensi', function($q) {
                        $q->where('kelas_id', $this->kelas->id);
                    })
                    ->where('mahasiswa_id', $user->id)
                    ->get();
                    
        $hadir = $absensi->where('status', 'hadir')->count();
        $persenHadir = $totalPertemuan > 0 ? round(($hadir / $totalPertemuan) * 100) : 0;

        // 3. Tugas
        $tugas = $this->kelas->tugas;
        $pengumpulanTugas = PengumpulanTugas::whereIn('tugas_id', $tugas->pluck('id'))
                                ->where('mahasiswa_id', $user->id)
                                ->get()
                                ->keyBy('tugas_id');

        // 4. Kuis
        $kuis = $this->kelas->kuis;
        $kuisSesi = KuisSesi::whereIn('kuis_id', $kuis->pluck('id'))
                            ->where('mahasiswa_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->get()
                            ->groupBy('kuis_id');

        return view('livewire.mahasiswa.nilai-detail', [
            'nilaiAkhir' => $nilaiAkhir,
            'totalPertemuan' => $totalPertemuan,
            'hadir' => $hadir,
            'persenHadir' => $persenHadir,
            'tugas' => $tugas,
            'pengumpulanTugas' => $pengumpulanTugas,
            'kuis' => $kuis,
            'kuisSesi' => $kuisSesi,
        ]);
    }
}
