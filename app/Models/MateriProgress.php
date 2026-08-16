<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MateriProgress extends Model
{
    use HasFactory;

    protected $table    = 'materi_progress';
    protected $fillable = ['konten_id', 'mahasiswa_id', 'is_selesai', 'selesai_at', 'durasi_detik'];
    protected $casts    = ['is_selesai' => 'boolean', 'selesai_at' => 'datetime'];

    public function konten()    { return $this->belongsTo(KontenMateri::class, 'konten_id'); }
    public function mahasiswa() { return $this->belongsTo(User::class, 'mahasiswa_id'); }
}
