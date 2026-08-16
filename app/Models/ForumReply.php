<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model {
    protected $table = 'forum_replies';
    protected $fillable = ['thread_id','user_id','konten','is_solution'];
    protected $casts = ['is_solution' => 'boolean'];
    public function thread() { return $this->belongsTo(ForumThread::class); }
    public function user()   { return $this->belongsTo(User::class); }
}
