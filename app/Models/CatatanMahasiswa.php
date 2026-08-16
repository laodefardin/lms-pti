<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatatanMahasiswa extends Model
{
    use HasFactory;

    protected $table    = 'catatan_mahasiswa';
    protected $fillable = ['mahasiswa_id', 'konten_id', 'catatan'];

    public function mahasiswa() { return $this->belongsTo(User::class, 'mahasiswa_id'); }
    public function konten()    { return $this->belongsTo(KontenMateri::class, 'konten_id'); }
}
