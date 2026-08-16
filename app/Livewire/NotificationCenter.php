<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use App\Services\NotifikasiService;
use Illuminate\Support\Facades\Auth;

class NotificationCenter extends Component
{
    public $isOpen = false;
    public $unreadCount = 0;

    protected $listeners = ['echo:notifikasi,NotifikasiBaru' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            $this->unreadCount = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }
    }

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->loadNotifications();
        }
    }

    public function markAsRead($id)
    {
        $notif = Notification::where('user_id', Auth::id())->find($id);
        if ($notif && !$notif->is_read) {
            $notif->update(['is_read' => true, 'read_at' => now()]);
            $this->loadNotifications();
            if ($notif->link) {
                return redirect($notif->link);
            }
        }
    }

    public function markAllAsRead(NotifikasiService $service)
    {
        $service->tandaiSemua(Auth::id());
        $this->loadNotifications();
    }

    public function render()
    {
        $notifications = Auth::check() 
            ? Notification::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
            : collect();

        return view('livewire.notification-center', [
            'notifications' => $notifications
        ]);
    }
}
