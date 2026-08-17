<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\AuditLog as Activity;

#[Layout('components.layouts.admin', ['title' => 'Audit Log'])]
class AuditLog extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = Activity::latest()->paginate(20);
        return view('livewire.admin.audit-log', compact('logs'));
    }
}
