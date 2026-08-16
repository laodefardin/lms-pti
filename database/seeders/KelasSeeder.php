<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Kelas, User, Semester, MataKuliah, Pertemuan, KontenMateri, Tugas, Kuis, BankSoal};

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $semester = Semester::where('is_active', true)->first();
        if (!$semester) return;

        $mkPemrogramanWeb = MataKuliah::where('kode', 'PTI301')->first();
        $dosen = User::role('dosen')->first();

        if ($mkPemrogramanWeb && $dosen) {
            // 1. Buat Kelas
            $kelas = Kelas::firstOrCreate(
                ['mata_kuliah_id' => $mkPemrogramanWeb->id, 'semester_id' => $semester->id, 'nama_kelas' => 'A'],
                [
                    'dosen_id' => $dosen->id,
                    'hari' => 'Senin',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '10:30',
                    'bobot_tugas' => 30,
                    'bobot_kuis' => 20,
                    'bobot_kehadiran' => 10,
                    'bobot_uts' => 20,
                    'bobot_uas' => 20,
                    'status' => 'aktif'
                ]
            );

            // 2. Assign semua mahasiswa ke kelas ini
            $mahasiswas = User::role('mahasiswa')->pluck('id')->toArray();
            $kelas->mahasiswa()->syncWithoutDetaching($mahasiswas);

            // 3. Buat Pertemuan 1
            $pertemuan1 = Pertemuan::firstOrCreate(
                ['kelas_id' => $kelas->id, 'pertemuan_ke' => 1],
                ['judul' => 'Pengenalan Pemrograman Web', 'tanggal' => '2024-08-05']
            );

            // Konten Materi: Video
            KontenMateri::firstOrCreate(
                ['pertemuan_id' => $pertemuan1->id, 'judul' => 'Video Pengenalan'],
                ['tipe' => 'video', 'konten' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'urutan' => 1]
            );

            // Konten Materi: Artikel
            KontenMateri::firstOrCreate(
                ['pertemuan_id' => $pertemuan1->id, 'judul' => 'Sejarah HTML'],
                ['tipe' => 'artikel', 'konten' => '<p>HTML adalah bahasa markup standar untuk membuat halaman web.</p>', 'urutan' => 2]
            );

            // 4. Buat Tugas
            Tugas::firstOrCreate(
                ['kelas_id' => $kelas->id, 'judul' => 'Tugas 1: Membuat Halaman Biodata'],
                [
                    'deskripsi' => 'Buat halaman biodata diri menggunakan HTML murni.',
                    'deadline' => now()->addDays(7),
                    'is_published' => true
                ]
            );

            // 5. Buat Bank Soal & Kuis
            $bankSoal = BankSoal::firstOrCreate(
                ['kelas_id' => $kelas->id, 'pertanyaan' => 'Apa kepanjangan dari HTML?'],
                [
                    'dosen_id' => $dosen->id,
                    'tipe' => 'pg',
                    'opsi' => json_encode([
                        ['id' => 'a', 'teks' => 'Hyper Text Markup Language', 'is_benar' => true],
                        ['id' => 'b', 'teks' => 'High Text Markup Language', 'is_benar' => false],
                    ]),
                    'bobot' => 10
                ]
            );

            $kuis = Kuis::firstOrCreate(
                ['kelas_id' => $kelas->id, 'judul' => 'Kuis 1: Dasar HTML'],
                [
                    'deskripsi' => 'Kuis pertama mengenai HTML',
                    'durasi_menit' => 30,
                    'buka_at' => now(),
                    'tutup_at' => now()->addDays(3),
                    'is_published' => true
                ]
            );

            $kuis->soal()->syncWithoutDetaching([$bankSoal->id => ['urutan' => 1]]);
        }
    }
}
