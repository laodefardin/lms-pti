<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamifikasiPoin extends Model
{
    protected $table    = 'gamifikasi_poin';
    protected $fillable = ['user_id', 'kelas_id', 'tipe_aktivitas', 'poin', 'keterangan'];

    public function user()  { return $this->belongsTo(User::class); }
    public function kelas() { return $this->belongsTo(Kelas::class); }

    // Tipe aktivitas constants — harus sesuai enum di DB
    const MATERI_SELESAI    = 'baca_materi';
    const TUGAS_DIKUMPULKAN = 'kumpulkan_tugas';
    const KUIS_SELESAI      = 'kerjakan_kuis';
    const KUIS_LULUS        = 'nilai_sempurna';
    const FORUM_POST        = 'aktif_diskusi';
    const FORUM_SOLUSI      = 'aktif_diskusi';
    const ABSENSI_HADIR     = 'hadir_kuliah';
    const LOGIN_HARIAN      = 'streak_belajar';
}
