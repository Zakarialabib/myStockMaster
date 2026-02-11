<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Services\EnvironmentService;

class DesktopNotification extends Component
{
    public $notifications = [];
    public $maxNotifications = 5;

    protected $listeners = [
        'showNotification' => 'addNotification',
        'clearNotifications' => 'clearAll',
        'removeNotification' => 'removeNotification'
    ];

    public function mount()
    {
        // Only initialize if in desktop mode
        if (!EnvironmentService::isDesktop()) {
            return;
        }

        // Load any persisted notifications
        $this->loadPersistedNotifications();
    }

    public function addNotification($title, $message, $type = 'info', $persistent = false, $duration = 5000)
    {
        if (!EnvironmentService::isDesktop()) {
            return;
        }

        $notification = [
            'id' => uniqid(),
            'title' => $title,
            'message' => $message,
            'type' => $type, // success, error, warning, info
            'persistent' => $persistent,
            'duration' => $duration,
            'timestamp' => now(),
            'read' => false
        ];

        // Add to the beginning of the array
        array_unshift($this->notifications, $notification);

        // Limit the number of notifications
        if (count($this->notifications) > $this->maxNotifications) {
            $this->notifications = array_slice($this->notifications, 0, $this->maxNotifications);
        }

        // Persist important notifications
        if ($persistent) {
            $this->persistNotification($notification);
        }

        // Auto-remove non-persistent notifications
        if (!$persistent && $duration > 0) {
            $this->dispatch('auto-remove-notification', [
                'id' => $notification['id'],
                'duration' => $duration
            ]);
        }

        $this->dispatch('notification-added', $notification);
    }

    public function removeNotification($notificationId)
    {
        $this->notifications = array_filter($this->notifications, function ($notification) use ($notificationId) {
            return $notification['id'] !== $notificationId;
        });

        // Re-index array
        $this->notifications = array_values($this->notifications);

        // Remove from persistence if it was persistent
        $this->removePersistedNotification($notificationId);

        $this->dispatch('notification-removed', ['id' => $notificationId]);
    }

    public function markAsRead($notificationId)
    {
        foreach ($this->notifications as &$notification) {
            if ($notification['id'] === $notificationId) {
                $notification['read'] = true;
                break;
            }
        }

        $this->updatePersistedNotification($notificationId, ['read' => true]);
    }

    public function clearAll()
    {
        $this->notifications = [];
        $this->clearPersistedNotifications();
        $this->dispatch('all-notifications-cleared');
    }

    public function getUnreadCount()
    {
        return count(array_filter($this->notifications, function ($notification) {
            return !$notification['read'];
        }));
    }

    public function getNotificationsByType($type)
    {
        return array_filter($this->notifications, function ($notification) use ($type) {
            return $notification['type'] === $type;
        });
    }

    private function loadPersistedNotifications()
    {
        $persistedFile = storage_path('app/desktop/notifications.json');
        
        if (file_exists($persistedFile)) {
            $persisted = json_decode(file_get_contents($persistedFile), true);
            
            if (is_array($persisted)) {
                // Filter out old notifications (older than 24 hours)
                $cutoff = now()->subHours(24);
                
                $this->notifications = array_filter($persisted, function ($notification) use ($cutoff) {
                    return $notification['persistent'] && 
                           isset($notification['timestamp']) && 
                           \Carbon\Carbon::parse($notification['timestamp'])->isAfter($cutoff);
                });
            }
        }
    }

    private function persistNotification($notification)
    {
        $persistedFile = storage_path('app/desktop/notifications.json');
        $directory = dirname($persistedFile);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $existing = [];
        if (file_exists($persistedFile)) {
            $existing = json_decode(file_get_contents($persistedFile), true) ?: [];
        }

        $existing[] = $notification;

        // Keep only the last 50 persistent notifications
        if (count($existing) > 50) {
            $existing = array_slice($existing, -50);
        }

        file_put_contents($persistedFile, json_encode($existing, JSON_PRETTY_PRINT));
    }

    private function removePersistedNotification($notificationId)
    {
        $persistedFile = storage_path('app/desktop/notifications.json');
        
        if (file_exists($persistedFile)) {
            $existing = json_decode(file_get_contents($persistedFile), true) ?: [];
            
            $existing = array_filter($existing, function ($notification) use ($notificationId) {
                return $notification['id'] !== $notificationId;
            });

            file_put_contents($persistedFile, json_encode(array_values($existing), JSON_PRETTY_PRINT));
        }
    }

    private function updatePersistedNotification($notificationId, $updates)
    {
        $persistedFile = storage_path('app/desktop/notifications.json');
        
        if (file_exists($persistedFile)) {
            $existing = json_decode(file_get_contents($persistedFile), true) ?: [];
            
            foreach ($existing as &$notification) {
                if ($notification['id'] === $notificationId) {
                    $notification = array_merge($notification, $updates);
                    break;
                }
            }

            file_put_contents($persistedFile, json_encode($existing, JSON_PRETTY_PRINT));
        }
    }

    private function clearPersistedNotifications()
    {
        $persistedFile = storage_path('app/desktop/notifications.json');
        
        if (file_exists($persistedFile)) {
            unlink($persistedFile);
        }
    }

    public function render()
    {
        return view('livewire.components.desktop-notification');
    }
}