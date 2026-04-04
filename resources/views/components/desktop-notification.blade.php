<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\EnvironmentService;
use Illuminate\Support\Facades\File;

new class extends Component
{
    public $notifications = [];
    public $maxNotifications = 5;

    public function mount()
    {
        // Only initialize if in desktop mode
        if (!EnvironmentService::isDesktop()) {
            return;
        }

        // Load any persisted notifications
        $this->loadPersistedNotifications();
    }

    #[On('showNotification')]
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

    #[On('removeNotification')]
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

    #[On('clearNotifications')]
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
            File::delete($persistedFile);
        }
    }
};
?>

<div>
    <div class="fixed top-4 right-4 z-50 space-y-2" x-data="{
        notifications: @entangle('notifications'),
        autoRemove(id, duration) {
            setTimeout(() => {
                $wire.removeNotification(id);
            }, duration);
        }
    }" x-init="$wire.on('auto-remove-notification', (data) => {
        autoRemove(data.id, data.duration);
    });">
        <template x-for="notification in notifications" :key="notification.id">
            <div class="desktop-notification max-w-sm w-full shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-300 ease-in-out"
                :class="{
                    'bg-green-50 border-green-200': notification.type === 'success',
                    'bg-red-50 border-red-200': notification.type === 'error',
                    'bg-yellow-50 border-yellow-200': notification.type === 'warning',
                    'bg-blue-50 border-blue-200': notification.type === 'info',
                    'opacity-60': notification.read
                }"
                x-show="true" x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="shrink-0">
                            <!-- Success Icon -->
                            <template x-if="notification.type === 'success'">
                                <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </template>

                            <!-- Error Icon -->
                            <template x-if="notification.type === 'error'">
                                <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </template>

                            <!-- Warning Icon -->
                            <template x-if="notification.type === 'warning'">
                                <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </template>

                            <!-- Info Icon -->
                            <template x-if="notification.type === 'info'">
                                <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </template>
                        </div>

                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium"
                                :class="{
                                    'text-green-900': notification.type === 'success',
                                    'text-red-900': notification.type === 'error',
                                    'text-yellow-900': notification.type === 'warning',
                                    'text-blue-900': notification.type === 'info'
                                }"
                                x-text="notification.title">
                            </p>
                            <p class="mt-1 text-sm"
                                :class="{
                                    'text-green-700': notification.type === 'success',
                                    'text-red-700': notification.type === 'error',
                                    'text-yellow-700': notification.type === 'warning',
                                    'text-blue-700': notification.type === 'info'
                                }"
                                x-text="notification.message">
                            </p>

                            <!-- Timestamp -->
                            <p class="mt-1 text-xs text-gray-500"
                                x-text="new Date(notification.timestamp).toLocaleTimeString()"></p>
                        </div>

                        <div class="ml-4 shrink-0 flex">
                            <!-- Mark as Read Button (if unread) -->
                            <template x-if="!notification.read">
                                <button @click="$wire.markAsRead(notification.id)"
                                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2"
                                    title="{{ __('desktop.notifications.mark_read') }}">
                                    <span class="sr-only">{{ __('desktop.notifications.mark_read') }}</span>
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </template>

                            <!-- Close Button -->
                            <button @click="$wire.removeNotification(notification.id)"
                                class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                title="{{ __('desktop.notifications.close') }}">
                                <span class="sr-only">{{ __('desktop.notifications.close') }}</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Progress bar for auto-dismiss -->
                <template x-if="!notification.persistent && notification.duration > 0">
                    <div class="h-1 bg-gray-200">
                        <div class="h-full transition-all ease-linear"
                            :class="{
                                'bg-green-400': notification.type === 'success',
                                'bg-red-400': notification.type === 'error',
                                'bg-yellow-400': notification.type === 'warning',
                                'bg-blue-400': notification.type === 'info'
                            }"
                            :style="`width: 100%; animation: shrink ${notification.duration}ms linear forwards;`"></div>
                    </div>
                </template>
            </div>
        </template>

        <!-- Clear All Button (when there are notifications) -->
        <template x-if="notifications.length > 0">
            <div class="text-center">
                <button @click="$wire.clearAll()"
                    class="text-xs text-gray-500 hover:text-gray-700 underline focus:outline-none">
                    {{ __('desktop.notifications.clear_all') }}
                </button>
            </div>
        </template>
    </div>

    <style>
        @keyframes shrink {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for global notification events
            window.addEventListener('desktop-notification', function(event) {
                if (window.Livewire) {
                    const component = window.Livewire.find('desktop-notification');
                    if (component) {
                        component.call('addNotification',
                            event.detail.title,
                            event.detail.message,
                            event.detail.type || 'info',
                            event.detail.persistent || false,
                            event.detail.duration || 5000
                        );
                    }
                }
            });

            // Integrate with DesktopApp if available
            if (window.DesktopApp) {
                const originalShowNotification = window.DesktopApp.showNotification;
                window.DesktopApp.showNotification = function(title, message, type = 'info', duration = 5000) {
                    // Use Livewire component if available
                    if (window.Livewire) {
                        const component = window.Livewire.find('desktop-notification');
                        if (component) {
                            component.call('addNotification', title, message, type, false, duration);
                            return;
                        }
                    }

                    // Fallback to original method
                    originalShowNotification.call(this, title, message, type, duration);
                };
            }
        });
    </script>
</div>
