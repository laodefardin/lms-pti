<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $fillable = [
        'kelas_id', 'pertemuan_id', 'judul', 'deskripsi',
        'file_soal', 'tipe_pengumpulan', 'format_file',
        'maks_ukuran_mb', 'nilai_max', 'deadline', 'is_published',
    ];
    protected $casts = [
        'deadline'     => 'datetime',
        'is_published' => 'boolean',
        'format_file'  => 'array',
    ];

    public function kelas()           { return $this->belongsTo(Kelas::class); }
    public function pertemuan()       { return $this->belongsTo(Pertemuan::class); }
    public function pengumpulan()     { return $this->hasMany(PengumpulanTugas::class); }

    public function pengumpulanMahasiswa(int $mahasiswaId): ?PengumpulanTugas
    {
        return $this->pengumpulan()->where('mahasiswa_id', $mahasiswaId)->first();
    }

    public function isDeadlineLewat(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    public function sisaHari(): int
    {
        if (!$this->deadline) return 999;
        return max(0, (int) now()->diffInDays($this->deadline, false));
    }
}
