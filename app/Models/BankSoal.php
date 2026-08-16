<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    protected $table    = 'bank_soal';
    protected $fillable = [
        'kelas_id', 'dosen_id', 'tipe', 'pertanyaan',
        'opsi', 'jawaban', 'pembahasan', 'bobot', 'topik',
    ];
    protected $casts = ['opsi' => 'array'];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function dosen() { return $this->belongsTo(User::class, 'dosen_id'); }
    public function soalKuis() { return $this->hasMany(KuisSoal::class, 'bank_soal_id'); }

    /**
     * Get pilihan sebagai collection [{teks, is_benar}]
     * DB store: opsi = ['A','B','C','D'], jawaban = 'A'
     */
    public function getPilihanAttribute()
    {
        if (!$this->opsi) return collect();
        return collect($this->opsi)->map(fn($teks, $key) => [
            'id'       => $key,
            'teks'     => $teks,
            'is_benar' => ($key === $this->jawaban || $teks === $this->jawaban),
        ])->values();
    }

    /** Check apakah jawaban mahasiswa benar */
    public function isJawabanBenar(string $jawaban): bool
    {
        return strtolower(trim($jawaban)) === strtolower(trim($this->jawaban ?? ''));
    }
}
