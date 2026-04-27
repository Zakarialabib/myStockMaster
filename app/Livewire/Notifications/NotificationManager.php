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
    public string $filterType = 'all';

    #[Url(except: 'all')]
    public string $filterRead = 'all';

    #[Url(except: '')]
    public string $searchTerm = '';

    public array $selectedNotifications = [];

    public bool $selectAll = false;

    #[Computed(persist: true)]
    public function lowQuantityCount(): int
    {
        return ProductWarehouse::whereColumn('qty', '<=', 'stock_alert')->count();
    }

    public function updatedSearchTerm(): void
    {
        $this->resetPage();
    }

    public function updatedFilterType(): void
    {
        $this->resetPage();
    }

    public function updatedFilterRead(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(mixed $value): void
    {
        $this->selectedNotifications = $value ? $this->getNotificationsQuery()->pluck('id')->toArray() : [];
    }

    public function updatedSelectedNotifications(): void
    {
        $this->selectAll = count($this->selectedNotifications) === $this->getNotificationsQuery()->count() && $this->selectedNotifications !== [];
    }

    public function getNotificationsQuery()
    {
        $builder = Notification::query()
            ->where('notifiable_id', auth()->id())->latest();

        if ($this->filterType !== 'all') {
            $builder->where('type', $this->filterType);
        }

        if ($this->filterRead === 'read') {
            $builder->whereNotNull('read_at');
        } elseif ($this->filterRead === 'unread') {
            $builder->whereNull('read_at');
        }

        if ($this->searchTerm) {
            $builder->where(function (\Illuminate\Contracts\Database\Query\Builder $builder): void {
                $builder->whereLike('data', '%' . $this->searchTerm . '%')
                    ->orWhereLike('type', '%' . $this->searchTerm . '%');
            });
        }

        return $builder;
    }

    public function markAsRead(mixed $id): void
    {
        $notification = Notification::query()->findOrFail($id);
        $notification->markAsRead();
        $this->success(__('Notification marked as read.'));
    }

    public function markAsUnread(mixed $id): void
    {
        $notification = Notification::query()->findOrFail($id);
        $notification->read_at = null;
        $notification->save();
        $this->success(__('Notification marked as unread.'));
    }

    public function deleteNotification(mixed $id): void
    {
        Notification::query()->findOrFail($id)->delete();
        $this->success(__('Notification deleted.'));
    }

    public function markAllAsRead(): void
    {
        $this->getNotificationsQuery()->whereNull('read_at')->update(['read_at' => now()]);
        $this->success(__('All filtered notifications marked as read.'));
    }

    public function deleteSelected(): void
    {
        Notification::query()->whereIn('id', $this->selectedNotifications)->delete();
        $this->selectedNotifications = [];
        $this->selectAll = false;
        $this->success(__('Selected notifications deleted.'));
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $notifications = $this->getNotificationsQuery()->paginate(15);

        $notificationTypes = Notification::query()->where('notifiable_id', auth()->id())
            ->select('type')
            ->distinct()
            ->pluck('type');

        return view('livewire.notifications.notification-manager', [
            'notifications' => $notifications,
            'notificationTypes' => $notificationTypes,
        ]);
    }
}
