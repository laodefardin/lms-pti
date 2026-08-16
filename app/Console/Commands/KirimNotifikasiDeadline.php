<?php

namespace App\Console\Commands;

use App\Services\NotifikasiService;
use Illuminate\Console\Command;

class KirimNotifikasiDeadline extends Command
{
    protected $signature   = 'notifikasi:deadline';
    protected $description = 'Kirim notifikasi pengingat deadline tugas dan kuis yang akan datang';

    public function handle(NotifikasiService $service): void
    {
        $this->info('Mengirim notifikasi deadline tugas...');
        $service->notifDeadlineTugas();

        $this->info('Mengirim notifikasi kuis akan aktif...');
        $service->notifKuisAktif();

        $this->info('✅ Notifikasi berhasil dikirim: ' . now()->format('Y-m-d H:i:s'));
    }
}
