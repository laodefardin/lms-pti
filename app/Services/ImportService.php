<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ImportService
{
    /**
     * Import mahasiswa dari file Excel/CSV
     * 
     * @param string $filePath
     * @param int|null $assignToKelasId Jika ada, otomatis daftarkan ke kelas ini
     * @return array ['success' => int, 'failed' => int, 'errors' => array]
     */
    public function importMahasiswa($filePath, $assignToKelasId = null): array
    {
        $result = ['success' => 0, 'failed' => 0, 'errors' => []];

        try {
            // Kita pakai anonymous class import dari maatwebsite/excel
            $importClass = new class($result, $assignToKelasId) implements \Maatwebsite\Excel\Concerns\ToCollection, \Maatwebsite\Excel\Concerns\WithHeadingRow {
                private $result;
                private $kelasId;

                public function __construct(&$result, $kelasId)
                {
                    $this->result = &$result;
                    $this->kelasId = $kelasId;
                }

                public function collection(\Illuminate\Support\Collection $rows)
                {
                    foreach ($rows as $index => $row) {
                        try {
                            $nim = $row['nim'] ?? null;
                            $nama = $row['nama'] ?? $row['name'] ?? null;
                            $email = $row['email'] ?? null;
                            $angkatan = $row['angkatan'] ?? date('Y');

                            if (!$nim || !$nama || !$email) {
                                $this->result['failed']++;
                                $this->result['errors'][] = "Baris " . ($index + 2) . ": Data tidak lengkap (NIM/Nama/Email kosong).";
                                continue;
                            }

                            $user = User::firstOrCreate(
                                ['email' => $email],
                                [
                                    'name' => $nama,
                                    'nim' => $nim,
                                    'angkatan' => $angkatan,
                                    'password' => Hash::make($nim), // Default password is NIM
                                    'is_active' => true,
                                ]
                            );

                            if ($user->wasRecentlyCreated) {
                                $user->assignRole('mahasiswa');
                            }

                            // Assign ke kelas jika diminta
                            if ($this->kelasId) {
                                $user->mahasiswaKelas()->syncWithoutDetaching([$this->kelasId]);
                            }

                            $this->result['success']++;
                        } catch (\Exception $e) {
                            $this->result['failed']++;
                            $this->result['errors'][] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                        }
                    }
                }
            };

            Excel::import($importClass, $filePath);
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            $result['errors'][] = "Gagal memproses file. Pastikan format benar.";
        }

        return $result;
    }
}
