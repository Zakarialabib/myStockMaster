<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Models\Notification;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Exception;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $recentNotifications = [];
    public $showDropdown = false;
    public $maxRecentNotifications = 5;
    public $autoRefresh = true;
    public $refreshInterval = 30; // seconds
    public $search = '';
    public $loading = false;

    protected $notificationService;

    #[On('notificationCreated')]
    #[On('notificationUpdated')]
    #[On('toggleNotificationDropdown')]
    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        try {
            $notificationService = new NotificationService();

            // Get unread count for current user
            $user = auth()->user();
            $this->unreadCount = $user ? $notificationService->getUnreadCount($user) : 0;

            // Get recent notifications for dropdown
            $this->recentNotifications = Notification::with('notifiable')
                ->orderBy('created_at', 'desc')
                ->limit($this->maxRecentNotifications)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id'         => $notification->id,
                        'type'       => $notification->type,
                        'data'       => $this->formatNotificationData($notification),
                        'read_at'    => $notification->read_at,
                        'created_at' => $notification->created_at,
                        'time_ago'   => $notification->created_at->diffForHumans(),
                        'icon'       => $this->getNotificationIcon($notification->type),
                        'color'      => $this->getNotificationColor($notification->type),
                    ];
                })
                ->toArray();
        } catch (Exception $e) {
            // Silently handle errors to avoid breaking the UI
            $this->unreadCount = 0;
            $this->recentNotifications = [];
        }
    }

    public function toggleDropdown()
    {
        $this->showDropdown = ! $this->showDropdown;

        if ($this->showDropdown) {
            $this->loadNotifications();
        }
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function markAsRead($notificationId)
    {
        try {
            $notification = Notification::findOrFail($notificationId);
            $notification->markAsRead();

            $this->loadNotifications();
            $this->dispatch('notificationUpdated');
        } catch (Exception $e) {
            // Silently handle errors
        }
    }

    public function markAllAsRead()
    {
        try {
            Notification::whereNull('read_at')
                ->update(['read_at' => now()]);

            $this->loadNotifications();
            $this->dispatch('notificationUpdated');
        } catch (Exception $e) {
            // Silently handle errors
        }
    }

    #[Computed]
    public function recentNotifications()
    {
        return collect($this->notifications)
            ->when($this->search, function ($collection) {
                return $collection->filter(function ($notification) {
                    return str_contains(strtolower($notification['title'] ?? ''), strtolower($this->search)) ||
                           str_contains(strtolower($notification['message'] ?? ''), strtolower($this->search));
                });
            })
            ->take(10);
    }

    #[Computed]
    public function totalUnreadCount()
    {
        return $this->unreadCount;
    }

    public function refreshNotifications()
    {
        $this->loadNotifications();
    }

    public function viewAllNotifications()
    {
        $this->closeDropdown();

        return redirect()->route('notifications.index');
    }

    public function handleNotificationClick($notificationId)
    {
        $this->markAsRead($notificationId);

        // You can add logic here to redirect to specific pages based on notification type
        $notification = collect($this->recentNotifications)
            ->firstWhere('id', $notificationId);

        if ($notification) {
            $this->handleNotificationRedirect($notification);
        }
    }

    private function handleNotificationRedirect($notification)
    {
        $data = $notification['data'];

        switch ($notification['type']) {
            case 'sale_created':
            case 'sale_updated':
                if (isset($data['sale_id'])) {
                    return redirect()->route('sales.show', $data['sale_id']);
                }

                break;

            case 'low_stock':
                if (isset($data['product_id'])) {
                    return redirect()->route('products.show', $data['product_id']);
                }

                break;

            case 'expense_created':
                if (isset($data['expense_id'])) {
                    return redirect()->route('expenses.show', $data['expense_id']);
                }

                break;

            default:
                // Default to notifications page
                return redirect()->route('notifications.index');
        }
    }

    public function getNotificationIcon($type)
    {
        $icons = [
            'sale_created'    => 'shopping-cart',
            'sale_updated'    => 'edit',
            'low_stock'       => 'exclamation-triangle',
            'product_updated' => 'package',
            'expense_created' => 'credit-card',
            'system'          => 'cog',
            'user'            => 'user',
            'default'         => 'bell',
        ];

        return $icons[$type] ?? $icons['default'];
    }

    public function getNotificationColor($type)
    {
        $colors = [
            'sale_created'    => 'green',
            'sale_updated'    => 'blue',
            'low_stock'       => 'red',
            'product_updated' => 'purple',
            'expense_created' => 'orange',
            'system'          => 'gray',
            'user'            => 'indigo',
            'default'         => 'gray',
        ];

        return $colors[$type] ?? $colors['default'];
    }

    public function formatNotificationData($notification)
    {
        $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;

        if ( ! is_array($data)) {
            return [
                'title'   => 'Notification',
                'message' => 'You have a new notification',
            ];
        }

        // Format based on notification type
        switch ($notification->type) {
            case 'sale_created':
                return [
                    'title'         => 'New Sale',
                    'message'       => "Sale #{$data['sale_id']} created",
                    'sale_id'       => $data['sale_id'] ?? null,
                    'customer_name' => $data['customer_name'] ?? null,
                    'total_amount'  => $data['total_amount'] ?? null,
                ];

            case 'sale_updated':
                return [
                    'title'   => 'Sale Updated',
                    'message' => "Sale #{$data['sale_id']} was updated",
                    'sale_id' => $data['sale_id'] ?? null,
                    'status'  => $data['status'] ?? null,
                ];

            case 'low_stock':
                return [
                    'title'         => 'Low Stock Alert',
                    'message'       => "'{$data['product_name']}' is running low",
                    'product_id'    => $data['product_id'] ?? null,
                    'product_name'  => $data['product_name'] ?? null,
                    'current_stock' => $data['current_stock'] ?? null,
                    'minimum_stock' => $data['minimum_stock'] ?? null,
                ];

            case 'product_updated':
                return [
                    'title'        => 'Product Updated',
                    'message'      => "Product '{$data['product_name']}' was updated",
                    'product_id'   => $data['product_id'] ?? null,
                    'product_name' => $data['product_name'] ?? null,
                ];

            case 'expense_created':
                return [
                    'title'      => 'New Expense',
                    'message'    => "Expense for {$data['category']} recorded",
                    'expense_id' => $data['expense_id'] ?? null,
                    'category'   => $data['category'] ?? null,
                    'amount'     => $data['amount'] ?? null,
                ];

            case 'system':
                return [
                    'title'   => 'System Notification',
                    'message' => $data['message'] ?? 'System notification received',
                ];

            case 'user':
                return [
                    'title'   => 'User Notification',
                    'message' => $data['message'] ?? 'User notification received',
                ];

            default:
                return [
                    'title'   => ucfirst(str_replace('_', ' ', $notification->type)),
                    'message' => $data['message'] ?? 'Notification received',
                ];
        }
    }

    public function getBadgeClass()
    {
        if ($this->unreadCount === 0) {
            return 'hidden';
        }

        if ($this->unreadCount > 99) {
            return 'bg-red-500 text-white text-xs rounded-full px-1 min-w-5 h-5 flex items-center justify-center';
        }

        return 'bg-red-500 text-white text-xs rounded-full px-1.5 min-w-5 h-5 flex items-center justify-center';
    }

    public function getBadgeText()
    {
        if ($this->unreadCount > 99) {
            return '99+';
        }

        return (string) $this->unreadCount;
    }

    public function render()
    {
        return view('livewire.notifications.notification-bell');
    }
}
