<?php

namespace App\Services;

use App\Models\User;
use App\Models\Kelas;
use App\Models\NilaiAkhir;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportService
{
    /**
     * Export nilai akhir per kelas ke Excel
     */
    public function exportNilaiKelasExcel(int $kelasId)
    {
        $kelas = Kelas::with(['mataKuliah', 'dosen'])->findOrFail($kelasId);
        $data = NilaiAkhir::with('mahasiswa')
            ->where('kelas_id', $kelasId)
            ->get()
            ->map(function ($item) {
                return [
                    'NIM' => $item->mahasiswa->nim ?? '-',
                    'Nama' => $item->mahasiswa->name,
                    'Tugas' => $item->nilai_tugas,
                    'Kuis' => $item->nilai_kuis,
                    'Kehadiran' => $item->nilai_kehadiran,
                    'UTS' => $item->nilai_uts,
                    'UAS' => $item->nilai_uas,
                    'Nilai Akhir' => $item->nilai_akhir,
                    'Grade' => $item->grade
                ];
            });

        // We use a simple array export from maatwebsite/excel if we have the class
        // but it's easier to create a lightweight export class inline or just use fromArray
        
        $exportClass = new class($data, $kelas) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithTitle {
            private $data;
            private $kelas;
            public function __construct($data, $kelas) { $this->data = $data; $this->kelas = $kelas; }
            public function array(): array { return $this->data->toArray(); }
            public function headings(): array { return ['NIM', 'Nama', 'Tugas', 'Kuis', 'Kehadiran', 'UTS', 'UAS', 'Nilai Akhir', 'Grade']; }
            public function title(): string { return 'Nilai ' . substr($this->kelas->mataKuliah->nama_mk, 0, 20); }
        };

        return Excel::download($exportClass, "Nilai_{$kelas->mataKuliah->kode}_" . date('Ymd') . ".xlsx");
    }

    /**
     * Export transkrip mahasiswa ke PDF
     */
    public function exportTranskripPdf(int $mahasiswaId)
    {
        $mahasiswa = User::role('mahasiswa')->findOrFail($mahasiswaId);
        $nilai = NilaiAkhir::with(['kelas.mataKuliah'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->get();

        // Calculate IPK
        $totalSks = 0;
        $totalBobot = 0;

        foreach ($nilai as $n) {
            $sks = $n->kelas->mataKuliah->sks;
            $bobot = match($n->grade) {
                'A' => 4,
                'B' => 3,
                'C' => 2,
                'D' => 1,
                default => 0
            };
            
            $totalSks += $sks;
            $totalBobot += ($sks * $bobot);
        }

        $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

        $pdf = Pdf::loadView('exports.transkrip', [
            'mahasiswa' => $mahasiswa,
            'nilai' => $nilai,
            'ipk' => $ipk,
            'totalSks' => $totalSks
        ]);

        return $pdf->download("Transkrip_{$mahasiswa->nim}_{$mahasiswa->name}.pdf");
    }
}
