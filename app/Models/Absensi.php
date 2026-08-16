<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model {
    protected $table = 'absensi';
    protected $fillable = ['pertemuan_id','kelas_id','tanggal','is_locked'];
    protected $casts = ['tanggal' => 'date', 'is_locked' => 'boolean'];
    public function pertemuan()  { return $this->belongsTo(Pertemuan::class); }
    public function kelas()      { return $this->belongsTo(Kelas::class); }
    public function mahasiswa()  { return $this->hasMany(AbsensiMahasiswa::class); }
}
