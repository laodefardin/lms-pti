<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisSesi extends Model
{
    protected $table    = 'kuis_sesi';
    protected $fillable = [
        'kuis_id', 'mahasiswa_id', 'percobaan_ke',
        'mulai_at', 'selesai_at', 'sisa_detik', 'nilai', 'status', 'urutan_soal',
    ];
    protected $casts = [
        'mulai_at'    => 'datetime',
        'selesai_at'  => 'datetime',
        'urutan_soal' => 'array',
    ];

    // status: berlangsung | selesai | timeout

    public function kuis()      { return $this->belongsTo(Kuis::class); }
    public function mahasiswa() { return $this->belongsTo(User::class, 'mahasiswa_id'); }
    public function jawaban()   { return $this->hasMany(KuisJawaban::class, 'sesi_id'); }

    // Alias untuk nilai agar kompatibel dengan kode lama
    public function getNilaiAkhirAttribute(): ?float
    {
        return $this->nilai;
    }
}
