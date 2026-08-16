<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    protected $table    = 'pengumpulan_tugas';
    protected $fillable = [
        'tugas_id', 'mahasiswa_id', 'file_path', 'link_url',
        'teks_jawaban', 'dikumpulkan_at', 'is_terlambat',
        'nilai', 'feedback', 'dinilai_at', 'status',
    ];
    protected $casts = [
        'dikumpulkan_at' => 'datetime',
        'dinilai_at'     => 'datetime',
        'is_terlambat'   => 'boolean',
    ];

    // status: draft | dikumpulkan | dinilai

    public function tugas()     { return $this->belongsTo(Tugas::class); }
    public function mahasiswa() { return $this->belongsTo(User::class, 'mahasiswa_id'); }

    public function getGradeAttribute(): string
    {
        if ($this->nilai === null) return '-';
        return NilaiAkhir::computeGrade($this->nilai);
    }
}
