<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;

/**
 * NotifikasiService — kirim notifikasi in-app ke user.
 */
class NotifikasiService
{
    /**
     * Kirim notifikasi ke satu user.
     */
    public function kirim(
        int    $userId,
        string $tipe,
        string $judul,
        string $pesan,
        string $icon  = '🔔',
        string $link  = ''
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'tipe'    => $tipe,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'icon'    => $icon,
            'link'    => $link,
            'is_read' => false,
        ]);
    }

    /**
     * Kirim ke banyak user sekaligus (bulk insert).
     */
    public function kirimBulk(
        array  $userIds,
        string $tipe,
        string $judul,
        string $pesan,
        string $icon = '🔔',
        string $link = ''
    ): void {
        $now  = now();
        $data = collect($userIds)->map(fn($id) => [
            'user_id'    => $id,
            'tipe'       => $tipe,
            'judul'      => $judul,
            'pesan'      => $pesan,
            'icon'       => $icon,
            'link'       => $link,
            'is_read'    => false,
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        Notification::insert($data);
    }

    /**
     * Tandai semua notifikasi user sebagai dibaca.
     */
    public function tandaiSemua(int $userId): void
    {
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    /**
     * Kirim notifikasi deadline tugas (dipanggil oleh scheduler).
     */
    public function notifDeadlineTugas(): void
    {
        $tugasList = \App\Models\Tugas::where('is_published', true)
            ->whereBetween('deadline', [now(), now()->addHours(24)])
            ->with('kelas.mahasiswa')
            ->get();

        foreach ($tugasList as $tugas) {
            $mahasiswaIds = $tugas->kelas->mahasiswa->pluck('id')->toArray();

            // Hanya yang belum mengumpulkan
            $sudahKumpul = $tugas->pengumpulan()->pluck('mahasiswa_id')->toArray();
            $belumKumpul = array_diff($mahasiswaIds, $sudahKumpul);

            if (empty($belumKumpul)) continue;

            $sisaJam = now()->diffInHours($tugas->deadline);
            $this->kirimBulk(
                userIds: array_values($belumKumpul),
                tipe   : 'deadline',
                judul  : "⏰ Deadline {$sisaJam} jam lagi!",
                pesan  : "Tugas \"{$tugas->judul}\" belum dikumpulkan. Deadline: {$tugas->deadline->format('d M Y, H:i')}",
                icon   : '⏰',
                link   : "/mahasiswa/tugas/{$tugas->id}"
            );
        }
    }

    /**
     * Kirim notifikasi kuis akan dibuka.
     */
    public function notifKuisAktif(): void
    {
        $kuisList = \App\Models\Kuis::where('is_published', true)
            ->whereBetween('buka_at', [now(), now()->addHours(1)])
            ->with('kelas.mahasiswa')
            ->get();

        foreach ($kuisList as $kuis) {
            $mahasiswaIds = $kuis->kelas->mahasiswa->pluck('id')->toArray();
            if (empty($mahasiswaIds)) continue;

            $this->kirimBulk(
                userIds: $mahasiswaIds,
                tipe   : 'kuis',
                judul  : "⚡ Kuis Segera Dibuka!",
                pesan  : "Kuis \"{$kuis->judul}\" akan dibuka pukul {$kuis->buka_at->format('H:i')}",
                icon   : '⚡',
                link   : "/mahasiswa/kuis"
            );
        }
    }
}
