<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KuisJawaban extends Model {
    protected $table = 'kuis_jawaban';
    protected $fillable = ['sesi_id','soal_id','pilihan_id','jawaban_text'];
    public function sesi()  { return $this->belongsTo(KuisSesi::class, 'sesi_id'); }
    public function soal()  { return $this->belongsTo(KuisSoal::class, 'soal_id'); }
}
