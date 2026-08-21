<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'nim_nidn', 'email', 'password', 'foto',
        'role', 'angkatan', 'no_hp', 'bio', 'is_active', 'program_studi_id',
        'google_id', 'google_avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ─── Scopes ─────────────────────────────────────────────
    public function scopeMahasiswa($query) { return $query->where('role', 'mahasiswa'); }
    public function scopeDosen($query)     { return $query->where('role', 'dosen'); }
    public function scopeAdmin($query)     { return $query->where('role', 'admin'); }

    // ─── Helpers ────────────────────────────────────────────
    public function isMahasiswa(): bool { return $this->role === 'mahasiswa'; }
    public function isDosen(): bool     { return $this->role === 'dosen'; }
    public function isAdmin(): bool     { return $this->role === 'admin'; }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=004b93&background=e8f2fc';
    }

    // ─── Relationships ───────────────────────────────────────
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    // Mahasiswa: kelas yang diikuti
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa', 'mahasiswa_id', 'kelas_id')
            ->withPivot('enrolled_at');
    }

    // Dosen: kelas yang diampu
    public function kelasYangDiampu()
    {
        return $this->hasMany(Kelas::class, 'dosen_id');
    }

    public function materiProgress()
    {
        return $this->hasMany(MateriProgress::class, 'mahasiswa_id');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'mahasiswa_id');
    }

    public function kuisSesi()
    {
        return $this->hasMany(KuisSesi::class, 'mahasiswa_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }

    public function gamifikasiPoin()
    {
        // DB uses user_id not mahasiswa_id
        return $this->hasMany(GamifikasiPoin::class, 'user_id');
    }

    public function badges()
    {
        return $this->hasMany(GamifikasiBadge::class, 'user_id');
    }

    public function totalPoin(): int
    {
        return $this->gamifikasiPoin()->sum('poin');
    }
}
