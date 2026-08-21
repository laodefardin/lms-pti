<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model {
    protected $table = 'absensi';
    protected $fillable = ['pertemuan_id', 'kelas_id', 'token', 'expired_at', 'is_aktif'];
    protected $casts = ['is_aktif' => 'boolean', 'expired_at' => 'datetime'];
    public function pertemuan()  { return $this->belongsTo(Pertemuan::class); }
    public function kelas()      { return $this->belongsTo(Kelas::class); }
    public function mahasiswa()  { return $this->hasMany(AbsensiMahasiswa::class); }
}
