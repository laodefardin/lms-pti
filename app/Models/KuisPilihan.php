<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KuisPilihan extends Model {
    protected $table = 'kuis_pilihan';
    protected $fillable = ['soal_id','teks','is_benar','urutan'];
    protected $casts = ['is_benar' => 'boolean'];
    public function soal() { return $this->belongsTo(KuisSoal::class, 'soal_id'); }
}
