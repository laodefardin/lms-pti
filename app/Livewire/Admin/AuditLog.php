<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Spatie\Activitylog\Models\Activity;

#[Layout('components.layouts.admin', ['title' => 'Audit Log'])]
class AuditLog extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = Activity::with('causer')->latest()->paginate(20);
        return view('livewire.admin.audit-log', compact('logs'));
    }
}
