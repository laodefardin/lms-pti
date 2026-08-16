<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model {
    protected $table = 'pengumuman';
    protected $fillable = ['judul','konten','tipe','user_id','kelas_id','publish_at'];
    protected $casts = ['publish_at' => 'datetime'];
    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function user()  { return $this->belongsTo(User::class); }
}
