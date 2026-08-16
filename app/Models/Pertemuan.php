<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    protected $table = 'pertemuan';
    protected $fillable = ['kelas_id', 'nomor', 'topik', 'tanggal', 'deskripsi', 'status'];
    protected $casts    = ['tanggal' => 'date'];

    public function kelas()   { return $this->belongsTo(Kelas::class); }
    public function konten()  { return $this->hasMany(KontenMateri::class)->orderBy('urutan'); }
    public function absensi() { return $this->hasOne(Absensi::class); }
}
