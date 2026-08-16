<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProgramStudi;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\Pertemuan;
use App\Models\KalenderAkademik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Buat Roles ─────────────────────────────────────────
        $roles = ['mahasiswa', 'dosen', 'admin'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ── 2. Program Studi ──────────────────────────────────────
        $prodi = ProgramStudi::create([
            'kode'      => 'PTI',
            'nama'      => 'Pendidikan Teknologi Informasi',
            'jenjang'   => 'S1',
            'fakultas'  => 'Fakultas Keguruan dan Ilmu Pendidikan',
            'akreditasi'=> 'B',
            'is_active' => true,
        ]);

        // ── 3. Admin ──────────────────────────────────────────────
        $admin = User::create([
            'name'             => 'Administrator',
            'nim_nidn'         => 'ADMIN001',
            'email'            => 'admin@pti.unsulbar.ac.id',
            'password'         => Hash::make('admin123'),
            'role'             => 'admin',
            'program_studi_id' => $prodi->id,
            'is_active'        => true,
        ]);
        $admin->assignRole('admin');

        // ── 4. Dosen ──────────────────────────────────────────────
        $dosenData = [
            ['name' => 'Dr. Laode Muhammad Zul Fardinsyah, S.Pd., M.T.', 'nim_nidn' => '0012345678', 'email' => 'dosen@pti.unsulbar.ac.id'],
            ['name' => 'Dr. Ahmad Nurizal, S.Kom., M.Cs.',                'nim_nidn' => '0023456789', 'email' => 'ahmad.nurizal@pti.unsulbar.ac.id'],
            ['name' => 'Siti Rahmawati, S.Pd., M.Pd.',                   'nim_nidn' => '0034567890', 'email' => 'siti.rahmawati@pti.unsulbar.ac.id'],
        ];

        $dosenList = [];
        foreach ($dosenData as $d) {
            $dosen = User::create(array_merge($d, [
                'password'         => Hash::make('dosen123'),
                'role'             => 'dosen',
                'program_studi_id' => $prodi->id,
                'is_active'        => true,
            ]));
            $dosen->assignRole('dosen');
            $dosenList[] = $dosen;
        }

        // ── 5. Mahasiswa ──────────────────────────────────────────
        $mahasiswaData = [
            ['name' => 'Ahmad Rizky Pratama',    'nim_nidn' => '220101001', 'angkatan' => '2022', 'email' => 'ahmad.rizky@mhs.pti.unsulbar.ac.id'],
            ['name' => 'Siti Nurhaliza',          'nim_nidn' => '220101002', 'angkatan' => '2022', 'email' => 'siti.nurhaliza@mhs.pti.unsulbar.ac.id'],
            ['name' => 'Muhammad Fajri',          'nim_nidn' => '220101003', 'angkatan' => '2022', 'email' => 'mfajri@mhs.pti.unsulbar.ac.id'],
            ['name' => 'Nur Aisyah Ramadhan',    'nim_nidn' => '220101004', 'angkatan' => '2022', 'email' => 'nur.aisyah@mhs.pti.unsulbar.ac.id'],
            ['name' => 'Bagas Dwi Santoso',       'nim_nidn' => '220101005', 'angkatan' => '2022', 'email' => 'bagas.dwi@mhs.pti.unsulbar.ac.id'],
            ['name' => 'Reza Mahendra Putra',     'nim_nidn' => '230101001', 'angkatan' => '2023', 'email' => 'reza.mahendra@mhs.pti.unsulbar.ac.id'],
            ['name' => 'Dewi Kartika Sari',       'nim_nidn' => '230101002', 'angkatan' => '2023', 'email' => 'dewi.kartika@mhs.pti.unsulbar.ac.id'],
            ['name' => 'Farhan Aditya Nugraha',   'nim_nidn' => '230101003', 'angkatan' => '2023', 'email' => 'farhan.aditya@mhs.pti.unsulbar.ac.id'],
            // Demo account
            ['name' => 'Mahasiswa Demo',          'nim_nidn' => '999999999', 'angkatan' => '2023', 'email' => 'mahasiswa@pti.unsulbar.ac.id'],
        ];

        $mahasiswaList = [];
        foreach ($mahasiswaData as $m) {
            $mhs = User::create(array_merge($m, [
                'password'         => Hash::make('mhs123'),
                'role'             => 'mahasiswa',
                'program_studi_id' => $prodi->id,
                'is_active'        => true,
            ]));
            $mhs->assignRole('mahasiswa');
            $mahasiswaList[] = $mhs;
        }

        // ── 6. Semester Aktif ─────────────────────────────────────
        $semester = Semester::create([
            'nama'           => 'Ganjil 2024/2025',
            'tahun_akademik' => '2024/2025',
            'tipe'           => 'ganjil',
            'tanggal_mulai'  => '2024-09-02',
            'tanggal_selesai'=> '2025-01-31',
            'is_aktif'       => true,
        ]);

        // ── 7. Master Matakuliah ─────────────────────────────────
        $mkList = [
            ['kode' => 'PTI101', 'nama' => 'Pemrograman Web Dasar',        'sks' => 3, 'kategori' => 'wajib_prodi'],
            ['kode' => 'PTI201', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'kategori' => 'wajib_prodi'],
            ['kode' => 'PTI301', 'nama' => 'Basis Data',                   'sks' => 3, 'kategori' => 'wajib_prodi'],
        ];

        $mataKuliahList = [];
        foreach ($mkList as $mk) {
            $mataKuliahList[] = MataKuliah::create(array_merge($mk, ['is_active' => true]));
        }

        // ── 8. Kelas Perkuliahan ──────────────────────────────────
        $kelasList = [];
        foreach ($mataKuliahList as $i => $mk) {
            $kelas = Kelas::create([
                'mata_kuliah_id'  => $mk->id,
                'dosen_id'        => $dosenList[$i % count($dosenList)]->id,
                'semester_id'     => $semester->id,
                'nama_kelas'      => 'A',
                'deskripsi'       => 'Kelas ' . $mk->nama . ' semester Ganjil 2024/2025.',
                'hari_kuliah'     => ['senin', 'rabu', 'jumat'][$i],
                'jam_mulai'       => '08:00',
                'jam_selesai'     => '10:30',
                'ruangan'         => 'Lab Komputer ' . ($i + 1),
                'bobot_tugas'     => 20,
                'bobot_kuis'      => 10,
                'bobot_kehadiran' => 10,
                'bobot_uts'       => 30,
                'bobot_uas'       => 30,
                'batas_kehadiran' => 75,
                'mode_materi'     => 'semua',
                'status'          => 'aktif',
            ]);

            // Enroll semua mahasiswa ke kelas
            foreach ($mahasiswaList as $mhs) {
                $kelas->mahasiswa()->attach($mhs->id, ['enrolled_at' => now()]);
            }

            // Buat 3 pertemuan awal per kelas
            for ($p = 1; $p <= 3; $p++) {
                $pertemuan = Pertemuan::create([
                    'kelas_id'  => $kelas->id,
                    'nomor'     => $p,
                    'topik'     => 'Pertemuan ' . $p . ': ' . $mk->nama,
                    'tanggal'   => now()->addWeeks($p - 1)->toDateString(),
                    'deskripsi' => 'Materi pertemuan ke-' . $p,
                    'status'    => $p === 1 ? 'aktif' : 'draft',
                ]);
            }

            $kelasList[] = $kelas;
        }

        // ── 9. Kalender Akademik ──────────────────────────────────
        $kalenderData = [
            ['semester_id' => $semester->id, 'judul' => 'Mulai Perkuliahan',           'tanggal_mulai' => '2024-09-02', 'tanggal_selesai' => null,         'tipe' => 'kuliah',  'warna' => '#14a7a0'],
            ['semester_id' => $semester->id, 'judul' => 'Ujian Tengah Semester (UTS)', 'tanggal_mulai' => '2024-11-04', 'tanggal_selesai' => '2024-11-08', 'tipe' => 'uts',     'warna' => '#f59e0b'],
            ['semester_id' => $semester->id, 'judul' => 'Ujian Akhir Semester (UAS)',  'tanggal_mulai' => '2025-01-13', 'tanggal_selesai' => '2025-01-17', 'tipe' => 'uas',     'warna' => '#ef4444'],
            ['semester_id' => null,          'judul' => 'Hari Kemerdekaan RI',         'tanggal_mulai' => '2024-08-17', 'tanggal_selesai' => null,         'tipe' => 'libur',   'warna' => '#dc2626'],
        ];
        foreach ($kalenderData as $k) {
            KalenderAkademik::create($k);
        }

        $this->command->info('✅ Seeder selesai! Data demo berhasil dibuat.');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',      'admin@pti.unsulbar.ac.id',      'admin123'],
                ['Dosen',      'dosen@pti.unsulbar.ac.id',      'dosen123'],
                ['Mahasiswa',  'mahasiswa@pti.unsulbar.ac.id',  'mhs123'],
            ]
        );
    }
}
