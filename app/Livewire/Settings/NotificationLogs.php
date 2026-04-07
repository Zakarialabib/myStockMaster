<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationLogs extends Component
{
    use WithPagination;

    public function render(): \Illuminate\Contracts\View\View
    {
        // We query the default Laravel notifications table
        $logs = DB::table('notifications')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.settings.notification-logs', [
            'logs' => $logs,
        ]);
    }
}
