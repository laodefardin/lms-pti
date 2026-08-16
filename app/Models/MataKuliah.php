<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table    = 'mata_kuliah';
    protected $fillable = ['kode', 'nama', 'sks', 'semester', 'kategori', 'deskripsi', 'prasyarat_id', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function prasyarat() { return $this->belongsTo(MataKuliah::class, 'prasyarat_id'); }
    public function kelas()     { return $this->hasMany(Kelas::class); }
}
