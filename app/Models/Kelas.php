<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'mata_kuliah_id', 'dosen_id', 'semester_id', 'nama_kelas',
        'thumbnail', 'deskripsi', 'hari_kuliah', 'jam_mulai', 'jam_selesai',
        'ruangan', 'bobot_tugas', 'bobot_kuis', 'bobot_kehadiran',
        'bobot_uts', 'bobot_uas', 'batas_kehadiran', 'mode_materi', 'status',
    ];

    protected $casts = [
        'jam_mulai'  => 'string',
        'jam_selesai'=> 'string',
    ];

    // ─── Route Binding via slug ──────────────────────────────────────────
    public function getRouteKeyName(): string
    {
        return 'id'; // tetap ID, tapi kita custom resolve di route
    }

    /** Slug dari nama matakuliah + nama kelas */
    public function getSlugAttribute(): string
    {
        return Str::slug(($this->mataKuliah->nama ?? '') . '-' . ($this->nama_kelas ?? ''));
    }

    // ─── Relations ──────────────────────────────────────────────────────
    public function mataKuliah()  { return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id'); }
    public function dosen()       { return $this->belongsTo(User::class, 'dosen_id'); }
    public function semester()    { return $this->belongsTo(Semester::class); }
    public function pertemuan()   { return $this->hasMany(Pertemuan::class)->orderBy('nomor'); }
    public function tugas()       { return $this->hasMany(Tugas::class); }
    public function kuis()        { return $this->hasMany(Kuis::class); }
    public function absensi()     { return $this->hasMany(Absensi::class); }
    public function nilaiAkhir()  { return $this->hasMany(NilaiAkhir::class); }
    public function pengumuman()  { return $this->hasMany(Pengumuman::class); }
    public function forumThread() { return $this->hasMany(ForumThread::class); }

    public function mahasiswa()
    {
        return $this->belongsToMany(User::class, 'kelas_mahasiswa', 'kelas_id', 'mahasiswa_id')
            ->withPivot('enrolled_at');
    }

    // ─── Computed Helpers ────────────────────────────────────────────────

    /** Hitung progress materi seorang mahasiswa (0-100) */
    public function progressMahasiswa(int $mahasiswaId): int
    {
        $allKonten = $this->pertemuan()
            ->with('konten')
            ->get()
            ->flatMap(fn($p) => $p->konten->where('is_published', true));

        $total = $allKonten->count();
        if ($total === 0) return 0;

        $selesai = MateriProgress::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('konten_id', $allKonten->pluck('id'))
            ->where('is_selesai', true)
            ->count();

        return (int) round($selesai / $total * 100);
    }

    /** Total konten materi yang dipublish */
    public function totalKonten(): int
    {
        return $this->pertemuan()
            ->withCount(['konten as published_konten_count' => fn($q) => $q->where('is_published', true)])
            ->get()
            ->sum('published_konten_count');
    }

    /** Persentase kehadiran seorang mahasiswa */
    public function kehadiranMahasiswa(int $mahasiswaId): int
    {
        $totalAbsensi = $this->absensi()->count();
        if ($totalAbsensi === 0) return 100;

        $hadir = AbsensiMahasiswa::whereHas('absensi', fn($q) => $q->where('kelas_id', $this->id))
            ->where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['hadir', 'izin'])
            ->count();

        return (int) round($hadir / $totalAbsensi * 100);
    }
}
