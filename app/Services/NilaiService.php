<?php

namespace App\Services;

use App\Models\{Kelas, NilaiAkhir, PengumpulanTugas, KuisSesi, AbsensiMahasiswa};
use Illuminate\Support\Facades\DB;

/**
 * NilaiService — auto-compute dan simpan nilai akhir mahasiswa.
 *
 * Rumus:
 *   nilai_akhir = (nilai_tugas * bobot_tugas/100)
 *               + (nilai_kuis * bobot_kuis/100)
 *               + (nilai_kehadiran * bobot_kehadiran/100)
 *               + (nilai_uts * bobot_uts/100)
 *               + (nilai_uas * bobot_uas/100)
 *
 * Bobot default dari tabel kelas (bobot_tugas, bobot_kuis, etc.)
 */
class NilaiService
{
    /**
     * Hitung dan simpan nilai akhir seorang mahasiswa untuk sebuah kelas.
     * Dipanggil setelah: tugas dinilai, kuis selesai, absensi diinput.
     */
    public function hitungNilaiAkhir(int $mahasiswaId, int $kelasId): NilaiAkhir
    {
        $kelas = Kelas::findOrFail($kelasId);

        $nilaiTugas     = $this->nilaiTugas($mahasiswaId, $kelasId);
        $nilaiKuis      = $this->nilaiKuis($mahasiswaId, $kelasId);
        $nilaiKehadiran = $this->nilaiKehadiran($mahasiswaId, $kelasId);

        // UTS dan UAS bisa di-override manual oleh dosen
        $existing   = NilaiAkhir::where('mahasiswa_id', $mahasiswaId)->where('kelas_id', $kelasId)->first();
        $nilaiUts   = $existing?->nilai_uts ?? 0;
        $nilaiUas   = $existing?->nilai_uas ?? 0;

        // Normalisasi bobot (pastikan total = 100)
        $totalBobot = ($kelas->bobot_tugas    ?? 25)
                    + ($kelas->bobot_kuis      ?? 25)
                    + ($kelas->bobot_kehadiran ?? 10)
                    + ($kelas->bobot_uts       ?? 20)
                    + ($kelas->bobot_uas       ?? 20);

        $divisor = $totalBobot > 0 ? $totalBobot : 100;

        $nilaiAkhir = (
            ($nilaiTugas     * ($kelas->bobot_tugas    ?? 25)) +
            ($nilaiKuis      * ($kelas->bobot_kuis      ?? 25)) +
            ($nilaiKehadiran * ($kelas->bobot_kehadiran ?? 10)) +
            ($nilaiUts       * ($kelas->bobot_uts       ?? 20)) +
            ($nilaiUas       * ($kelas->bobot_uas       ?? 20))
        ) / $divisor;

        $nilaiAkhir = round($nilaiAkhir, 2);
        $grade      = NilaiAkhir::computeGrade($nilaiAkhir);

        return NilaiAkhir::updateOrCreate(
            ['mahasiswa_id' => $mahasiswaId, 'kelas_id' => $kelasId],
            [
                'nilai_tugas'     => round($nilaiTugas, 2),
                'nilai_kuis'      => round($nilaiKuis, 2),
                'nilai_kehadiran' => round($nilaiKehadiran, 2),
                'nilai_uts'       => $nilaiUts,
                'nilai_uas'       => $nilaiUas,
                'nilai_akhir'     => $nilaiAkhir,
                'grade'           => $grade,
            ]
        );
    }

    /**
     * Hitung dan update nilai akhir SEMUA mahasiswa di suatu kelas.
     * Berguna saat dosen selesai menilai semua tugas.
     */
    public function hitungSemuaMahasiswa(int $kelasId): void
    {
        $kelas = Kelas::with('mahasiswa')->findOrFail($kelasId);
        foreach ($kelas->mahasiswa as $mhs) {
            $this->hitungNilaiAkhir($mhs->id, $kelasId);
        }
    }

    /**
     * Rata-rata nilai tugas yang sudah dinilai di suatu kelas.
     */
    private function nilaiTugas(int $mahasiswaId, int $kelasId): float
    {
        $tugasIds = \App\Models\Tugas::where('kelas_id', $kelasId)
            ->where('is_published', true)
            ->pluck('id');

        if ($tugasIds->isEmpty()) return 0;

        $avg = PengumpulanTugas::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('tugas_id', $tugasIds)
            ->where('status', 'dinilai')
            ->whereNotNull('nilai')
            ->avg('nilai');

        return (float) ($avg ?? 0);
    }

    /**
     * Rata-rata nilai kuis (nilai final per sesi terbaik).
     */
    private function nilaiKuis(int $mahasiswaId, int $kelasId): float
    {
        $kuisIds = \App\Models\Kuis::where('kelas_id', $kelasId)
            ->where('is_published', true)
            ->pluck('id');

        if ($kuisIds->isEmpty()) return 0;

        // Ambil nilai terbaik per kuis
        $nilaiList = KuisSesi::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('kuis_id', $kuisIds)
            ->where('status', 'selesai')
            ->select('kuis_id', DB::raw('MAX(nilai) as nilai_terbaik'))
            ->groupBy('kuis_id')
            ->pluck('nilai_terbaik');

        if ($nilaiList->isEmpty()) return 0;
        return (float) $nilaiList->avg();
    }

    /**
     * Nilai kehadiran berdasarkan persentase hadir (scale 0-100).
     */
    private function nilaiKehadiran(int $mahasiswaId, int $kelasId): float
    {
        $totalAbsensi = \App\Models\Absensi::where('kelas_id', $kelasId)->count();
        if ($totalAbsensi === 0) return 100;

        $hadir = AbsensiMahasiswa::whereHas('absensi', fn($q) => $q->where('kelas_id', $kelasId))
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'hadir')
            ->count();

        // hadir + izin (dihitung 50% dari kehadiran)
        $izin = AbsensiMahasiswa::whereHas('absensi', fn($q) => $q->where('kelas_id', $kelasId))
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'izin')
            ->count();

        $efektif = $hadir + ($izin * 0.5);
        return min(100, round($efektif / $totalAbsensi * 100, 2));
    }
}
