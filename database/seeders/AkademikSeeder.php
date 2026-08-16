<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramStudi;
use App\Models\Semester;
use App\Models\MataKuliah;

class AkademikSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = ProgramStudi::firstOrCreate(
            ['kode' => 'PTI'],
            ['nama' => 'Pendidikan Teknologi Informasi', 'fakultas' => 'Keguruan dan Ilmu Pendidikan']
        );

        $semester = Semester::firstOrCreate(
            ['kode' => '20241'],
            ['nama' => 'Ganjil 2024/2025', 'is_active' => true, 'tanggal_mulai' => '2024-08-01', 'tanggal_selesai' => '2024-12-31']
        );

        $mkList = [
            ['kode' => 'PTI101', 'nama_mk' => 'Algoritma dan Pemrograman', 'sks' => 3, 'semester_plot' => 1],
            ['kode' => 'PTI102', 'nama_mk' => 'Pengantar Teknologi Informasi', 'sks' => 2, 'semester_plot' => 1],
            ['kode' => 'PTI301', 'nama_mk' => 'Pemrograman Web', 'sks' => 3, 'semester_plot' => 3],
        ];

        foreach ($mkList as $mk) {
            MataKuliah::firstOrCreate(
                ['kode' => $mk['kode']],
                array_merge($mk, ['program_studi_id' => $prodi->id])
            );
        }
    }
}
