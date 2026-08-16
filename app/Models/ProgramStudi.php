<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'program_studi';
    protected $fillable = ['kode', 'nama', 'jenjang', 'fakultas', 'akreditasi', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function mahasiswa() { return $this->hasMany(User::class)->where('role', 'mahasiswa'); }
}
