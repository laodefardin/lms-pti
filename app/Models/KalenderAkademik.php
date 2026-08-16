<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KalenderAkademik extends Model {
    protected $table = 'kalender_akademik';
    protected $fillable = ['semester_id','judul','deskripsi','tipe','mulai_at','selesai_at','warna'];
    protected $casts = ['mulai_at' => 'datetime', 'selesai_at' => 'datetime'];
    public function semester() { return $this->belongsTo(Semester::class); }
}
