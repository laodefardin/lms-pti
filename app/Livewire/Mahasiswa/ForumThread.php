<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ForumThread as ThreadModel;
use App\Models\ForumReply;
use App\Services\{GamifikasiService, NotifikasiService};
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

        $reply = ForumReply::create([
            'forum_thread_id' => $this->thread->id,
            'user_id' => Auth::id(),
            'konten' => $this->replyKonten,
        ]);

        $this->reset('replyKonten');
        
        $gamifikasi = app(GamifikasiService::class);
        $gamifikasi->berikanPoin(
            userId: Auth::id(),
            tipeAktivitas: \App\Models\GamifikasiPoin::FORUM_POST,
            kelasId: $this->thread->kelas_id,
            keterangan: "Membalas thread forum: {$this->thread->judul}",
            referenceId: $reply->id,
            allowDuplicate: true
        );

        if ($this->thread->user_id !== Auth::id()) {
            app(NotifikasiService::class)->kirim(
                userId: $this->thread->user_id,
                tipe: 'forum',
                judul: '💬 Balasan Baru di Thread Anda',
                pesan: Auth::user()->name . " membalas thread \"{$this->thread->judul}\".",
                icon: '💬',
                link: "/mahasiswa/forum/thread/{$this->thread->id}"
            );
        }
        
        session()->flash('success', 'Balasan berhasil dikirim!');
    }

    public function markSolution($replyId)
    {
        if (Auth::id() === $this->thread->user_id) {
            ForumReply::where('forum_thread_id', $this->thread->id)->update(['is_solution' => false]);
            $reply = ForumReply::find($replyId);
            if ($reply) {
                $reply->update(['is_solution' => true]);

                $gamifikasi = app(GamifikasiService::class);
                $gamifikasi->berikanPoin(
                    userId: $reply->user_id,
                    tipeAktivitas: \App\Models\GamifikasiPoin::FORUM_SOLUSI,
                    kelasId: $this->thread->kelas_id,
                    keterangan: "Balasan Anda ditandai sebagai solusi pada thread: {$this->thread->judul}",
                    referenceId: $reply->id,
                    allowDuplicate: false
                );

                if ($reply->user_id !== Auth::id()) {
                    app(NotifikasiService::class)->kirim(
                        userId: $reply->user_id,
                        tipe: 'forum_solusi',
                        judul: '✅ Solusi Terbaik!',
                        pesan: "Balasan Anda ditandai sebagai solusi di thread \"{$this->thread->judul}\".",
                        icon: '✅',
                        link: "/mahasiswa/forum/thread/{$this->thread->id}"
                    );
                }
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
