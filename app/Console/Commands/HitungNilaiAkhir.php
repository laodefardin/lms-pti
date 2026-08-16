<?php

namespace App\Console\Commands;

use App\Models\Kelas;
use App\Services\NilaiService;
use Illuminate\Console\Command;

class HitungNilaiAkhir extends Command
{
    protected $signature   = 'nilai:hitung-semua {--kelas= : ID kelas spesifik}';
    protected $description = 'Hitung ulang nilai akhir semua mahasiswa di semua kelas aktif';

    public function handle(NilaiService $nilaiService): void
    {
        $query = Kelas::where('status', 'aktif')->with('mahasiswa');

        if ($this->option('kelas')) {
            $query->where('id', $this->option('kelas'));
        }

        $kelasList = $query->get();
        $this->info("Memproses {$kelasList->count()} kelas...");
        $bar = $this->output->createProgressBar($kelasList->count());
        $bar->start();

        foreach ($kelasList as $kelas) {
            foreach ($kelas->mahasiswa as $mhs) {
                try {
                    $nilaiService->hitungNilaiAkhir($mhs->id, $kelas->id);
                } catch (\Exception $e) {
                    $this->error("\nError kelas {$kelas->id} mhs {$mhs->id}: " . $e->getMessage());
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Nilai akhir berhasil dihitung: ' . now()->format('Y-m-d H:i:s'));
    }
}
