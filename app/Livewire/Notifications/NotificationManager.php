<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Models\Notification;
use App\Models\ProductWarehouse;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Notifications')]
class NotificationManager extends Component
{
    use WithAlert;
    use WithPagination;

    #[Url(except: 'all')]
    public $filterType = 'all';

    #[Url(except: 'all')]
    public $filterRead = 'all';

    #[Url(except: '')]
    public $searchTerm = '';

    public $selectedNotifications = [];

    public $selectAll = false;

    #[Computed]
    public function lowQuantity()
    {
        return ProductWarehouse::with('product')
            ->select('product_id', 'qty', 'stock_alert')
            ->whereColumn('qty', '<=', 'stock_alert')
            ->get();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterRead()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedNotifications = $this->getNotificationsQuery()->pluck('id')->toArray();
        } else {
            $this->selectedNotifications = [];
        }
    }

    public function updatedSelectedNotifications()
    {
        $this->selectAll = count($this->selectedNotifications) === $this->getNotificationsQuery()->count() && $this->selectedNotifications !== [];
    }

    public function getNotificationsQuery()
    {
        $query = Notification::query()
            ->where('notifiable_id', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        if ($this->filterRead === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($this->filterRead === 'unread') {
            $query->whereNull('read_at');
        }

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('data', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('type', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $query;
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
        $this->success(__('Notification marked as read.'));
    }

    public function markAsUnread($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read_at = null;
        $notification->save();
        $this->success(__('Notification marked as unread.'));
    }

    public function deleteNotification($id)
    {
        Notification::findOrFail($id)->delete();
        $this->success(__('Notification deleted.'));
    }

    public function markAllAsRead()
    {
        $this->getNotificationsQuery()->whereNull('read_at')->update(['read_at' => now()]);
        $this->success(__('All filtered notifications marked as read.'));
    }

    public function deleteSelected()
    {
        Notification::whereIn('id', $this->selectedNotifications)->delete();
        $this->selectedNotifications = [];
        $this->selectAll = false;
        $this->success(__('Selected notifications deleted.'));
    }

    public function render()
    {
        $notifications = $this->getNotificationsQuery()->paginate(15);

        $notificationTypes = Notification::where('notifiable_id', auth()->id())
            ->select('type')
            ->distinct()
            ->pluck('type');

        return view('livewire.notifications.notification-manager', [
            'notifications' => $notifications,
            'notificationTypes' => $notificationTypes,
        ]);
    }
}
