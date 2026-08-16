<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ForumThread as ThreadModel;
use App\Models\ForumReply;
use Illuminate\Support\Facades\Auth;

class ForumThread extends Component
{
    public ThreadModel $thread;
    public $replyKonten = '';

    public function mount(ThreadModel $thread)
    {
        $this->thread = $thread;
        $this->thread->increment('views');
    }

    public function getTitle()
    {
        return $this->thread->judul;
    }

    public function postReply()
    {
        $this->validate([
            'replyKonten' => 'required|string',
        ]);

        ForumReply::create([
            'forum_thread_id' => $this->thread->id,
            'user_id' => Auth::id(),
            'konten' => $this->replyKonten,
        ]);

        $this->reset('replyKonten');
        // Award gamification points logic can be added here
        
        session()->flash('success', 'Balasan berhasil dikirim!');
    }

    public function markSolution($replyId)
    {
        if (Auth::id() === $this->thread->user_id) {
            ForumReply::where('forum_thread_id', $this->thread->id)->update(['is_solution' => false]);
            $reply = ForumReply::find($replyId);
            if ($reply) {
                $reply->update(['is_solution' => true]);
            }
        }
    }

    #[Layout('components.layouts.mahasiswa')]
    public function render()
    {
        $this->dispatch('title-updated', title: $this->getTitle());

        $replies = $this->thread->replies()->with('user')->orderBy('created_at', 'asc')->get();

        return view('livewire.mahasiswa.forum-thread', [
            'replies' => $replies
        ])->title($this->getTitle());
    }
}
