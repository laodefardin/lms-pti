<?php

namespace App\Services;

use App\Models\{User, Kelas, GamifikasiPoin, GamifikasiBadge};
use Illuminate\Support\Facades\DB;

/**
 * GamifikasiService — mengelola pemberian poin dan badge
 * kepada mahasiswa berdasarkan aktivitas belajar.
 */
class GamifikasiService
{
    // ─── Nilai poin per aktivitas ─────────────────────────────────────
    const POIN = [
        GamifikasiPoin::MATERI_SELESAI    => 10,
        GamifikasiPoin::TUGAS_DIKUMPULKAN => 15,
        GamifikasiPoin::KUIS_SELESAI      => 20,
        GamifikasiPoin::KUIS_LULUS        => 30,  // bonus jika >= KKM
        GamifikasiPoin::FORUM_POST        => 5,
        GamifikasiPoin::FORUM_SOLUSI      => 25,
        GamifikasiPoin::ABSENSI_HADIR     => 5,
        GamifikasiPoin::LOGIN_HARIAN      => 2,
    ];

    // ─── Badge definitions ────────────────────────────────────────────
    const BADGES = [
        'materi_pertama' => ['nama' => 'Penjelajah Materi',   'icon' => '🗺️',  'warna' => '#14a7a0'],
        'materi_10'      => ['nama' => 'Pembaca Rajin',       'icon' => '📚',  'warna' => '#3b82f6'],
        'materi_50'      => ['nama' => 'Maestro Materi',      'icon' => '🎓',  'warna' => '#8b5cf6'],
        'tugas_pertama'  => ['nama' => 'Pengumpul Pertama',   'icon' => '📤',  'warna' => '#f59e0b'],
        'kuis_lulus'     => ['nama' => 'Juara Kuis',          'icon' => '⚡',  'warna' => '#ef4444'],
        'forum_aktif'    => ['nama' => 'Diskusi Aktif',       'icon' => '💬',  'warna' => '#22c55e'],
        'poin_100'       => ['nama' => 'Centurion',           'icon' => '💯',  'warna' => '#f97316'],
        'poin_500'       => ['nama' => 'Elite Learner',       'icon' => '🏆',  'warna' => '#eab308'],
        'poin_1000'      => ['nama' => 'Grand Master',        'icon' => '👑',  'warna' => '#a855f7'],
        'streak_7'       => ['nama' => 'Konsisten 7 Hari',    'icon' => '🔥',  'warna' => '#ef4444'],
    ];

    public function __construct(
        private NotifikasiService $notifService
    ) {}

    /**
     * Tambahkan poin untuk aktivitas tertentu.
     * Duplikat untuk aktivitas one-time (materi, tugas per ID) dicegah.
     */
    public function berikanPoin(
        int    $userId,
        string $tipeAktivitas,
        int    $kelasId,
        string $keterangan = '',
        ?int   $referenceId = null,
        bool   $allowDuplicate = false
    ): GamifikasiPoin {
        if (!$allowDuplicate && $referenceId) {
            $exists = GamifikasiPoin::where('user_id', $userId)
                ->where('tipe_aktivitas', $tipeAktivitas)
                ->where('keterangan', 'like', "%[ref:{$referenceId}]%")
                ->exists();
            if ($exists) {
                return new GamifikasiPoin(); // poin sudah diberikan
            }
        }

        $poin = self::POIN[$tipeAktivitas] ?? 5;
        $ket  = $keterangan . ($referenceId ? " [ref:{$referenceId}]" : '');

        $record = GamifikasiPoin::create([
            'user_id'        => $userId,
            'kelas_id'       => $kelasId,
            'tipe_aktivitas' => $tipeAktivitas,
            'poin'           => $poin,
            'keterangan'     => $ket,
        ]);

        // Cek dan award badge
        $this->cekBadge($userId, $tipeAktivitas);

        return $record;
    }

    /**
     * Hitung total poin seorang mahasiswa.
     */
    public function totalPoin(int $userId): int
    {
        return GamifikasiPoin::where('user_id', $userId)->sum('poin');
    }

    /**
     * Ranking mahasiswa di suatu kelas.
     */
    public function rankingKelas(int $kelasId): \Illuminate\Support\Collection
    {
        return GamifikasiPoin::where('kelas_id', $kelasId)
            ->select('user_id', DB::raw('SUM(poin) as total_poin'))
            ->groupBy('user_id')
            ->orderByDesc('total_poin')
            ->with('user:id,name,foto_url,nim_nidn')
            ->get();
    }

    /**
     * Cek dan berikan badge jika syarat terpenuhi.
     */
    private function cekBadge(int $userId, string $tipeAktivitas): void
    {
        $totalPoin    = $this->totalPoin($userId);
        $materiSelesai = GamifikasiPoin::where('user_id', $userId)
            ->where('tipe_aktivitas', GamifikasiPoin::MATERI_SELESAI)
            ->count();
        $tugasCount   = GamifikasiPoin::where('user_id', $userId)
            ->where('tipe_aktivitas', GamifikasiPoin::TUGAS_DIKUMPULKAN)
            ->count();
        $forumCount   = GamifikasiPoin::where('user_id', $userId)
            ->where('tipe_aktivitas', GamifikasiPoin::FORUM_POST)
            ->count();
        $kuisLulus    = GamifikasiPoin::where('user_id', $userId)
            ->where('tipe_aktivitas', GamifikasiPoin::KUIS_LULUS)
            ->count();

        $shouldAward = [];

        if ($materiSelesai >= 1)  $shouldAward[] = 'materi_pertama';
        if ($materiSelesai >= 10) $shouldAward[] = 'materi_10';
        if ($materiSelesai >= 50) $shouldAward[] = 'materi_50';
        if ($tugasCount >= 1)     $shouldAward[] = 'tugas_pertama';
        if ($kuisLulus >= 1)      $shouldAward[] = 'kuis_lulus';
        if ($forumCount >= 5)     $shouldAward[] = 'forum_aktif';
        if ($totalPoin >= 100)    $shouldAward[] = 'poin_100';
        if ($totalPoin >= 500)    $shouldAward[] = 'poin_500';
        if ($totalPoin >= 1000)   $shouldAward[] = 'poin_1000';

        foreach ($shouldAward as $badgeKey) {
            $this->awardBadge($userId, $badgeKey);
        }
    }

    /**
     * Award badge jika belum dimiliki.
     */
    public function awardBadge(int $userId, string $badgeKey): void
    {
        if (!isset(self::BADGES[$badgeKey])) return;

        $alreadyHas = GamifikasiBadge::where('user_id', $userId)
            ->where('badge_key', $badgeKey)
            ->exists();

        if ($alreadyHas) return;

        $def = self::BADGES[$badgeKey];
        GamifikasiBadge::create([
            'user_id'    => $userId,
            'badge_key'  => $badgeKey,
            'nama_badge' => $def['nama'],
            'icon'       => $def['icon'],
            'warna'      => $def['warna'],
            'diraih_at'  => now(),
        ]);

        // Notifikasi badge baru
        $this->notifService->kirim(
            userId : $userId,
            tipe   : 'sistem',
            judul  : "Badge Baru: {$def['nama']}",
            pesan  : "Selamat! Kamu mendapatkan badge {$def['icon']} {$def['nama']}!",
            icon   : $def['icon']
        );
    }
}
