<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NilaiAkhir extends Model {
    protected $table = 'nilai_akhir';
    protected $fillable = ['kelas_id','mahasiswa_id','nilai_tugas','nilai_kuis','nilai_kehadiran','nilai_uts','nilai_uas','nilai_akhir','grade'];
    public function kelas()      { return $this->belongsTo(Kelas::class); }
    public function mahasiswa()  { return $this->belongsTo(User::class, 'mahasiswa_id'); }

    public static function computeGrade(float $nilai): string {
        return match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 75 => 'B+',
            $nilai >= 70 => 'B',
            $nilai >= 65 => 'C+',
            $nilai >= 55 => 'C',
            $nilai >= 40 => 'D',
            default      => 'E',
        };
    }
}
