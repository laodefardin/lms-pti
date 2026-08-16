<?php

namespace App\Console\Commands;

use App\Models\{User, GamifikasiPoin};
use App\Services\GamifikasiService;
use Illuminate\Console\Command;

class AwardLoginHarian extends Command
{
    protected $signature   = 'gamifikasi:login-harian';
    protected $description = 'Award poin login harian untuk mahasiswa yang aktif hari ini';

    public function handle(GamifikasiService $gamifikasi): void
    {
        // Mahasiswa yang sudah login hari ini (session aktif = login dalam 24 jam)
        $mahasiswaAktif = User::role('mahasiswa')
            ->where('is_active', true)
            ->whereNotNull('last_login_at')
            ->where('last_login_at', '>=', today())
            ->get();

        $awarded = 0;
        foreach ($mahasiswaAktif as $mhs) {
            // Cek belum dapat poin hari ini
            $sudahDapat = GamifikasiPoin::where('user_id', $mhs->id)
                ->where('tipe_aktivitas', GamifikasiPoin::LOGIN_HARIAN)
                ->whereDate('created_at', today())
                ->exists();

            if (!$sudahDapat) {
                // Pakai kelas aktif pertama sebagai context
                $kelasId = $mhs->kelas()->where('status', 'aktif')->value('kelas_id') ?? 0;
                if ($kelasId) {
                    $gamifikasi->berikanPoin(
                        userId        : $mhs->id,
                        tipeAktivitas : GamifikasiPoin::LOGIN_HARIAN,
                        kelasId       : $kelasId,
                        keterangan    : 'Login harian ' . today()->format('d/m/Y'),
                        allowDuplicate: false
                    );
                    $awarded++;
                }
            }
        }

        $this->info("✅ Login harian: {$awarded} mahasiswa mendapat poin ({$mahasiswaAktif->count()} aktif)");
    }
}
