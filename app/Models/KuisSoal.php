<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisSoal extends Model
{
    protected $table    = 'kuis_soal';
    public $timestamps  = false;
    // kuis_soal only has: id, kuis_id, bank_soal_id, urutan
    protected $fillable = ['kuis_id', 'bank_soal_id', 'urutan'];

    public function kuis()    { return $this->belongsTo(Kuis::class); }
    public function bankSoal(){ return $this->belongsTo(BankSoal::class, 'bank_soal_id'); }
    public function jawaban() { return $this->hasMany(KuisJawaban::class, 'soal_id'); }

    // Delegate pertanyaan/tipe/bobot to BankSoal
    public function getPertanyaanAttribute(): string
    {
        return $this->bankSoal?->pertanyaan ?? '';
    }
    public function getTipeAttribute(): string
    {
        return $this->bankSoal?->tipe ?? 'pg';
    }
    public function getBobotAttribute(): int
    {
        return $this->bankSoal?->bobot ?? 1;
    }
    public function getPilihanAttribute()
    {
        return $this->bankSoal?->pilihan ?? collect();
    }
}
