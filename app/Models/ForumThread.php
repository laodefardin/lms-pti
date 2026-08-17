<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ForumThread extends Model {
    protected $table = 'forum_thread';
    protected $fillable = ['kelas_id','user_id','judul','konten','is_pinned','is_closed','views'];
    protected $casts = ['is_pinned' => 'boolean', 'is_closed' => 'boolean'];
    public function kelas()   { return $this->belongsTo(Kelas::class); }
    public function user()    { return $this->belongsTo(User::class); }
    public function replies() { return $this->hasMany(ForumReply::class, 'thread_id'); }
}
