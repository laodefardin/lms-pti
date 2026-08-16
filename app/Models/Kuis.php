<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    protected $table    = 'kuis';
    protected $fillable = [
        'kelas_id', 'judul', 'deskripsi', 'instruksi', 'tipe',
        'durasi_menit', 'buka_at', 'tutup_at', 'nilai_max',
        'maks_percobaan', 'acak_soal', 'acak_pilihan',
        'tampilkan_pembahasan', 'is_published',
    ];
    protected $casts = [
        'buka_at'              => 'datetime',
        'tutup_at'             => 'datetime',
        'acak_soal'            => 'boolean',
        'acak_pilihan'         => 'boolean',
        'tampilkan_pembahasan' => 'boolean',
        'is_published'         => 'boolean',
    ];

    public function kelas()  { return $this->belongsTo(Kelas::class); }
    public function soal()   { return $this->hasMany(KuisSoal::class, 'kuis_id')->orderBy('urutan'); }
    public function sesi()   { return $this->hasMany(KuisSesi::class, 'kuis_id'); }

    public function isSedangBerlangsung(): bool
    {
        return $this->is_published &&
               $this->buka_at <= now() &&
               $this->tutup_at >= now();
    }

    public function statusLabel(): string
    {
        if (!$this->is_published) return 'draft';
        if (now() < $this->buka_at) return 'terjadwal';
        if (now() > $this->tutup_at) return 'selesai';
        return 'aktif';
    }
}
