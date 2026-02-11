<div class="nav-item dropdown" wire:poll.30s="refreshNotifications">
    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
        <span class="avatar avatar-sm" style="background-image: url({{ asset('assets/img/bell.svg') }})">
            @if($this->totalUnreadCount > 0)
                <span class="badge bg-red">{{ $this->totalUnreadCount > 99 ? '99+' : $this->totalUnreadCount }}</span>
            @endif
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="min-width: 350px;">
        <div class="dropdown-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ __('Notifications') }}</h6>
                @if($this->totalUnreadCount > 0)
                    <button type="button" class="btn btn-sm btn-ghost-primary" wire:click="markAllAsRead">
                        {{ __('Mark all read') }}
                    </button>
                @endif
            </div>
            @if($this->totalUnreadCount > 0)
                <small class="text-muted">{{ trans_choice('You have :count unread notification|You have :count unread notifications', $this->totalUnreadCount, ['count' => $this->totalUnreadCount]) }}</small>
            @else
                <small class="text-muted">{{ __('All caught up!') }}</small>
            @endif
        </div>
        
        <div class="dropdown-divider"></div>
        
        @if($this->notifications->count() > 0)
            <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                @foreach($this->recentNotifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                        $priority = $data['priority'] ?? 'medium';
                        $type = $data['type'] ?? 'info';
                        
                        $typeIcon = match($type) {
                            'stock_alert' => 'ti-package',
                            'low_stock' => 'ti-alert-triangle',
                            'sale_completed' => 'ti-shopping-cart',
                            'purchase_received' => 'ti-truck',
                            'expense_added' => 'ti-receipt',
                            'system' => 'ti-settings',
                            'user_registered' => 'ti-user-plus',
                            'payment_received' => 'ti-credit-card',
                            'report_generated' => 'ti-file-text',
                            default => 'ti-bell'
                        };
                        
                        $priorityColor = match($priority) {
                            'high' => 'danger',
                            'medium' => 'warning',
                            'low' => 'info',
                            default => 'secondary'
                        };
                    @endphp
                    <div class="list-group-item list-group-item-action {{ $isUnread ? 'bg-light' : '' }}" 
                         style="cursor: pointer;" 
                         wire:click="handleNotificationClick('{{ $notification->id }}')">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-sm" 
                                      style="background-color: {{ $isUnread ? '#206bc4' : '#6c757d' }}">
                                    <i class="ti {{ $typeIcon }} text-white"></i>
                                </span>
                            </div>
                            <div class="col text-truncate">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="text-truncate">
                                        <div class="text-body d-block {{ $isUnread ? 'fw-bold' : '' }}">
                                            {{ Str::limit($data['title'] ?? __('Notification'), 30) }}
                                        </div>
                                        <div class="d-block text-muted text-truncate mt-n1" style="font-size: 0.75rem;">
                                            {{ Str::limit($data['message'] ?? __('No message available'), 50) }}
                                        </div>
                                        <div class="d-flex align-items-center gap-1 mt-1">
                                            <small class="text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                            @if($priority === 'high')
                                                <span class="badge bg-{{ $priorityColor }} badge-sm">{{ __('High') }}</span>
                                            @endif
                                            @if($isUnread)
                                                <span class="badge bg-primary badge-sm">{{ __('New') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="dropdown" onclick="event.stopPropagation();">
                                        <button class="btn btn-ghost-secondary btn-sm" 
                                                type="button" 
                                                data-bs-toggle="dropdown" 
                                                aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($isUnread)
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="#" 
                                                       wire:click.prevent="markAsRead('{{ $notification->id }}')">
                                                        <i class="ti ti-check me-2"></i>
                                                        {{ __('Mark as Read') }}
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="#" 
                                                       wire:click.prevent="markAsUnread('{{ $notification->id }}')">
                                                        <i class="ti ti-mail me-2"></i>
                                                        {{ __('Mark as Unread') }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if(isset($data['action_url']))
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ $data['action_url'] }}" 
                                                       wire:click="markAsRead('{{ $notification->id }}')">
                                                        <i class="ti ti-external-link me-2"></i>
                                                        {{ __('View Details') }}
                                                    </a>
                                                </li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" 
                                                   href="#" 
                                                   wire:click.prevent="deleteNotification('{{ $notification->id }}')">
                                                    <i class="ti ti-trash me-2"></i>
                                                    {{ __('Delete') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="dropdown-divider"></div>
            
            <div class="dropdown-footer">
                <div class="d-grid">
                    <a href="{{ route('notifications.index') }}" class="btn btn-primary">
                        <i class="ti ti-bell me-1"></i>
                        {{ __('View All Notifications') }}
                    </a>
                </div>
            </div>
        @else
            <div class="dropdown-item-text text-center py-4">
                <div class="empty">
                    <div class="empty-img">
                        <i class="ti ti-bell-off" style="font-size: 3rem; color: #6c757d;"></i>
                    </div>
                    <p class="empty-title h6">{{ __('No notifications') }}</p>
                    <p class="empty-subtitle text-muted small">
                        {{ __('You\'re all caught up! No new notifications.') }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle notification sound (optional)
                let lastUnreadCount = {{ $this->totalUnreadCount }};
                
                Livewire.on('notification-updated', function(data) {
                    const newUnreadCount = data.unreadCount;
                    
                    // Play notification sound if new notifications arrived
                    if (newUnreadCount > lastUnreadCount) {
                        // You can add a notification sound here
                        // const audio = new Audio('/assets/sounds/notification.mp3');
                        // audio.play().catch(e => console.log('Could not play notification sound'));
                        
                        // Show browser notification if permission granted
                        if (Notification.permission === 'granted' && data.latestNotification) {
                            new Notification(data.latestNotification.title, {
                                body: data.latestNotification.message,
                                icon: '/favicon.ico',
                                tag: 'stockmaster-notification'
                            });
                        }
                    }
                    
                    lastUnreadCount = newUnreadCount;
                });
                
                // Request notification permission
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
                
                // Handle notification clicks
                window.addEventListener('notification-clicked', function(event) {
                    const notificationId = event.detail.notificationId;
                    const actionUrl = event.detail.actionUrl;
                    
                    if (actionUrl) {
                        window.location.href = actionUrl;
                    }
                });
                
                // Auto-close dropdown after action
                window.addEventListener('notification-action-completed', function() {
                    const dropdown = bootstrap.Dropdown.getInstance(document.querySelector('[data-bs-toggle="dropdown"]'));
                    if (dropdown) {
                        dropdown.hide();
                    }
                });
            });
        </script>
    @endpush
</div>