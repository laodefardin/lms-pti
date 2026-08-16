<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'tahun_akademik', 'tipe', 'tanggal_mulai', 'tanggal_selesai', 'is_aktif'];
    protected $casts    = ['is_aktif' => 'boolean', 'tanggal_mulai' => 'date', 'tanggal_selesai' => 'date'];

    public function kelas()    { return $this->hasMany(Kelas::class); }
    public function kalender() { return $this->hasMany(KalenderAkademik::class); }

    public static function aktif(): ?self
    {
        return static::where('is_aktif', true)->first();
    }
}
