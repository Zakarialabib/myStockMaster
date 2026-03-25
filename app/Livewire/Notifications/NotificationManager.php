<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Models\Notification;
use App\Services\NotificationService;
use Exception;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationManager extends Component
{
    use WithPagination;

    #[Url(except: 'all')]
    public $filterType = 'all';

    #[Url(except: 'all')]
    public $filterRead = 'all';

    #[Url(except: '')]
    public $filterPriority = '';

    #[Url(except: '')]
    public $searchTerm = '';

    public $selectedNotifications = [];

    public $selectAll = false;

    public $showFilters = false;

    public $notificationTypes = [];

    public $loading = false;

    public function mount()
    {
        $this->loadNotificationTypes();
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

    public function updatedFilterPriority()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedNotifications = $this->getNotifications()->pluck('id')->toArray();
        } else {
            $this->selectedNotifications = [];
        }
    }

    public function updatedSelectedNotifications()
    {
        $totalNotifications = $this->getNotifications()->count();
        $this->selectAll = count($this->selectedNotifications) === $totalNotifications;
    }

    public function loadNotificationTypes()
    {
        $this->notificationTypes = Notification::select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type')
            ->toArray();
    }

    public function getNotifications()
    {
        $query = Notification::query()
            ->with('notifiable')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        if ($this->filterRead === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($this->filterRead === 'unread') {
            $query->whereNull('read_at');
        }

        if ($this->filterPriority !== '') {
            $query->where('data->priority', $this->filterPriority);
        }

        // Apply search
        if (! empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('type', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('data', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $query->paginate(20);
    }

    public function getNotificationsProperty()
    {
        return $this->getNotifications();
    }

    public function markAsRead($notificationId)
    {
        try {
            $notification = Notification::findOrFail($notificationId);
            $notification->markAsRead();

            session()->flash('success', 'Notification marked as read.');
            $this->dispatch('notificationUpdated');
        } catch (Exception $e) {
            session()->flash('error', 'Failed to mark notification as read: ' . $e->getMessage());
        }
    }

    public function markAsUnread($notificationId)
    {
        try {
            $notification = Notification::findOrFail($notificationId);
            $notification->markAsUnread();

            session()->flash('success', 'Notification marked as unread.');
            $this->dispatch('notificationUpdated');
        } catch (Exception $e) {
            session()->flash('error', 'Failed to mark notification as unread: ' . $e->getMessage());
        }
    }

    public function markSelectedAsRead()
    {
        if (empty($this->selectedNotifications)) {
            session()->flash('warning', 'No notifications selected.');

            return;
        }

        try {
            $notificationService = new NotificationService;
            $notificationService->markNotificationsAsRead($this->selectedNotifications);

            $count = count($this->selectedNotifications);
            session()->flash('success', "Marked {$count} notification(s) as read.");

            $this->selectedNotifications = [];
            $this->selectAll = false;
            $this->dispatch('notificationUpdated');
        } catch (Exception $e) {
            session()->flash('error', 'Failed to mark notifications as read: ' . $e->getMessage());
        }
    }

    public function deleteSelected()
    {
        if (empty($this->selectedNotifications)) {
            session()->flash('warning', 'No notifications selected.');

            return;
        }

        try {
            Notification::whereIn('id', $this->selectedNotifications)->delete();

            $count = count($this->selectedNotifications);
            session()->flash('success', "Deleted {$count} notification(s).");

            $this->selectedNotifications = [];
            $this->selectAll = false;
            $this->dispatch('notificationUpdated');
        } catch (Exception $e) {
            session()->flash('error', 'Failed to delete notifications: ' . $e->getMessage());
        }
    }

    public function deleteNotification($notificationId)
    {
        try {
            $notification = Notification::findOrFail($notificationId);
            $notification->delete();

            session()->flash('success', 'Notification deleted successfully.');
            $this->dispatch('notificationUpdated');
        } catch (Exception $e) {
            session()->flash('error', 'Failed to delete notification: ' . $e->getMessage());
        }
    }

    public function markAllAsRead()
    {
        try {
            $query = Notification::whereNull('read_at');

            // Apply same filters as the current view
            if ($this->filterType !== 'all') {
                $query->where('type', $this->filterType);
            }

            if ($this->filterPriority !== '') {
                $query->where('data->priority', $this->filterPriority);
            }

            if (! empty($this->searchTerm)) {
                $query->where(function ($q) {
                    $q->where('type', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('data', 'like', '%' . $this->searchTerm . '%');
                });
            }

            $count = $query->count();
            $query->update(['read_at' => now()]);

            session()->flash('success', "Marked {$count} notification(s) as read.");
            $this->dispatch('notificationUpdated');
        } catch (Exception $e) {
            session()->flash('error', 'Failed to mark all notifications as read: ' . $e->getMessage());
        }
    }

    public function clearAllRead()
    {
        try {
            $query = Notification::whereNotNull('read_at');

            // Apply same filters as the current view
            if ($this->filterType !== 'all') {
                $query->where('type', $this->filterType);
            }

            if ($this->filterPriority !== '') {
                $query->where('data->priority', $this->filterPriority);
            }

            if (! empty($this->searchTerm)) {
                $query->where(function ($q) {
                    $q->where('type', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('data', 'like', '%' . $this->searchTerm . '%');
                });
            }

            $count = $query->count();
            $query->delete();

            session()->flash('success', "Deleted {$count} read notification(s).");
            $this->dispatch('notificationUpdated');
        } catch (Exception $e) {
            session()->flash('error', 'Failed to clear read notifications: ' . $e->getMessage());
        }
    }

    public function toggleFilters()
    {
        $this->showFilters = ! $this->showFilters;
    }

    public function resetFilters()
    {
        $this->filterType = 'all';
        $this->filterRead = 'all';
        $this->filterPriority = '';
        $this->searchTerm = '';
        $this->selectedNotifications = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    #[On('notificationCreated')]
    #[On('notificationUpdated')]
    public function refreshNotifications()
    {
        $this->loadNotificationTypes();
        $this->selectedNotifications = [];
        $this->selectAll = false;
    }

    public function getNotificationIcon($type)
    {
        $icons = [
            'sale_created' => 'shopping-cart',
            'sale_updated' => 'edit',
            'low_stock' => 'exclamation-triangle',
            'product_updated' => 'package',
            'expense_created' => 'credit-card',
            'system' => 'cog',
            'user' => 'user',
            'default' => 'bell',
        ];

        return $icons[$type] ?? $icons['default'];
    }

    public function getNotificationColor($type)
    {
        $colors = [
            'sale_created' => 'green',
            'sale_updated' => 'blue',
            'low_stock' => 'red',
            'product_updated' => 'purple',
            'expense_created' => 'orange',
            'system' => 'gray',
            'user' => 'indigo',
            'default' => 'gray',
        ];

        return $colors[$type] ?? $colors['default'];
    }

    public function formatNotificationData($notification)
    {
        $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;

        if (! is_array($data)) {
            return [];
        }

        // Format based on notification type
        switch ($notification->type) {
            case 'sale_created':
                return [
                    'title' => 'New Sale Created',
                    'message' => "Sale #{$data['sale_id']} created for " . ($data['customer_name'] ?? 'Unknown Customer'),
                    'amount' => $data['total_amount'] ?? null,
                ];

            case 'low_stock':
                return [
                    'title' => 'Low Stock Alert',
                    'message' => "Product '{$data['product_name']}' is running low",
                    'current_stock' => $data['current_stock'] ?? null,
                    'minimum_stock' => $data['minimum_stock'] ?? null,
                ];

            case 'expense_created':
                return [
                    'title' => 'New Expense Recorded',
                    'message' => "Expense for {$data['category']} recorded",
                    'amount' => $data['amount'] ?? null,
                ];

            default:
                return [
                    'title' => ucfirst(str_replace('_', ' ', $notification->type)),
                    'message' => $data['message'] ?? 'Notification received',
                ];
        }
    }

    public function render()
    {
        $notifications = $this->getNotifications();

        return view('livewire.notifications.notification-manager', [
            'notifications' => $notifications,
        ]);
    }
}
