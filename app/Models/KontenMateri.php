<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KontenMateri extends Model
{
    use HasFactory;

    protected $table    = 'konten_materi';
    protected $fillable = [
        'pertemuan_id', 'tipe', 'judul', 'konten', 'file_path',
        'url', 'urutan', 'is_published', 'estimasi_menit',
    ];
    protected $casts = ['is_published' => 'boolean'];

    public function pertemuan() { return $this->belongsTo(Pertemuan::class); }
    public function progress()  { return $this->hasMany(MateriProgress::class, 'konten_id'); }
    public function catatan()   { return $this->hasMany(CatatanMahasiswa::class, 'konten_id'); }

    /** Ikon default berdasarkan tipe */
    public function getIkonAttribute(): string
    {
        return match($this->tipe) {
            'video'   => '🎬',
            'pdf'     => '📄',
            'artikel' => '📝',
            'kode'    => '💻',
            'link'    => '🔗',
            default   => '📁',
        };
    }

    /** Apakah mahasiswa sudah menyelesaikan konten ini */
    public function isSelesaiOleh(int $mahasiswaId): bool
    {
        return $this->progress()
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('is_selesai', true)
            ->exists();
    }
}
