<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamifikasiPoin extends Model
{
    protected $table    = 'gamifikasi_poin';
    protected $fillable = ['user_id', 'kelas_id', 'tipe_aktivitas', 'poin', 'keterangan'];

    public function user()  { return $this->belongsTo(User::class); }
    public function kelas() { return $this->belongsTo(Kelas::class); }

    // Tipe aktivitas constants
    const MATERI_SELESAI    = 'materi_selesai';
    const TUGAS_DIKUMPULKAN = 'tugas_dikumpulkan';
    const KUIS_SELESAI      = 'kuis_selesai';
    const KUIS_LULUS        = 'kuis_lulus';
    const FORUM_POST        = 'forum_post';
    const FORUM_SOLUSI      = 'forum_solusi';
    const ABSENSI_HADIR     = 'absensi_hadir';
    const LOGIN_HARIAN      = 'login_harian';
}
