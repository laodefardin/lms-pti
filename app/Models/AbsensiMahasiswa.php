<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AbsensiMahasiswa extends Model {
    protected $table = 'absensi_mahasiswa';
    protected $fillable = ['absensi_id','mahasiswa_id','status','keterangan'];
    public function absensi()   { return $this->belongsTo(Absensi::class); }
    public function mahasiswa() { return $this->belongsTo(User::class, 'mahasiswa_id'); }
}
