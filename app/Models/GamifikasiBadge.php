<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamifikasiBadge extends Model
{
    protected $table    = 'gamifikasi_badge';
    public    $timestamps = false;    // tabel hanya punya diraih_at
    protected $fillable = ['user_id', 'badge_key', 'nama_badge', 'icon', 'warna', 'diraih_at'];
    protected $casts    = ['diraih_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
}
