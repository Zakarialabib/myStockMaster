<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('System') }}
                    </div>
                    <h2 class="page-title">
                        {{ __('Notification Manager') }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button type="button" class="btn btn-outline-primary" wire:click="markAllAsRead">
                            <i class="ti ti-check-all me-1"></i>
                            {{ __('Mark All Read') }}
                        </button>
                        <button type="button" class="btn btn-outline-danger" wire:click="deleteSelected" @if(empty($selectedNotifications)) disabled @endif>
                            <i class="ti ti-trash me-1"></i>
                            {{ __('Delete Selected') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Notification Type') }}</label>
                                    <select class="form-select" wire:model.live="filterType">
                                        <option value="">{{ __('All Types') }}</option>
                                        @foreach($notificationTypes as $type)
                                            <option value="{{ $type }}">{{ __(ucfirst(str_replace('_', ' ', $type))) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Status') }}</label>
                                    <select class="form-select" wire:model.live="filterRead">
                                        <option value="">{{ __('All Notifications') }}</option>
                                        <option value="unread">{{ __('Unread Only') }}</option>
                                        <option value="read">{{ __('Read Only') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Priority') }}</label>
                                    <select class="form-select" wire:model.live="filterPriority">
                                        <option value="">{{ __('All Priorities') }}</option>
                                        <option value="high">{{ __('High Priority') }}</option>
                                        <option value="medium">{{ __('Medium Priority') }}</option>
                                        <option value="low">{{ __('Low Priority') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Search') }}</label>
                                    <input type="text" class="form-control" wire:model.live.debounce.300ms="searchTerm" placeholder="{{ __('Search notifications...') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Statistics -->
            <div class="row mb-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-primary text-white avatar">
                                        <i class="ti ti-bell"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ __('Total Notifications') }}
                                    </div>
                                    <div class="text-muted">
                                        {{ $notifications->total() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-warning text-white avatar">
                                        <i class="ti ti-bell-ringing"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ __('Unread') }}
                                    </div>
                                    <div class="text-muted">
                                        {{ $notifications->where('read_at', null)->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-danger text-white avatar">
                                        <i class="ti ti-alert-triangle"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ __('High Priority') }}
                                    </div>
                                    <div class="text-muted">
                                        {{ $notifications->where('data.priority', 'high')->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-success text-white avatar">
                                        <i class="ti ti-check"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ __('Read Today') }}
                                    </div>
                                    <div class="text-muted">
                                        {{ $notifications->where('read_at', '>=', now()->startOfDay())->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-list me-2"></i>
                                {{ __('Notifications') }}
                            </h3>
                            <div class="card-actions">
                                <label class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model.live="selectAll">
                                    <span class="form-check-label">{{ __('Select All') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($notifications->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($notifications as $notification)
                                        @php
                                            $data = $notification->data;
                                            $isUnread = is_null($notification->read_at);
                                            $priority = $data['priority'] ?? 'medium';
                                            $type = $data['type'] ?? 'info';
                                            
                                            $priorityClass = match($priority) {
                                                'high' => 'border-danger',
                                                'medium' => 'border-warning',
                                                'low' => 'border-info',
                                                default => 'border-secondary'
                                            };
                                            
                                            $typeIcon = match($type) {
                                                'stock_alert' => 'ti-package',
                                                'low_stock' => 'ti-alert-triangle',
                                                'sale_completed' => 'ti-shopping-cart',
                                                'purchase_received' => 'ti-truck',
                                                'expense_added' => 'ti-receipt',
                                                'system' => 'ti-settings',
                                                default => 'ti-bell'
                                            };
                                        @endphp
                                        <div class="list-group-item {{ $isUnread ? 'bg-light' : '' }} {{ $priorityClass }}" 
                                             style="border-left-width: 4px;">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <label class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               wire:model.live="selectedNotifications" 
                                                               value="{{ $notification->id }}">
                                                    </label>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="avatar avatar-sm" 
                                                          style="background-color: {{ $isUnread ? '#206bc4' : '#6c757d' }}">
                                                        <i class="ti {{ $typeIcon }} text-white"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h4 class="mb-1 {{ $isUnread ? 'fw-bold' : '' }}">
                                                                {{ $data['title'] ?? __('Notification') }}
                                                            </h4>
                                                            <p class="text-muted mb-1">
                                                                {{ $data['message'] ?? __('No message available') }}
                                                            </p>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <small class="text-muted">
                                                                    <i class="ti ti-clock me-1"></i>
                                                                    {{ $notification->created_at->diffForHumans() }}
                                                                </small>
                                                                <span class="badge bg-{{ $priority === 'high' ? 'danger' : ($priority === 'medium' ? 'warning' : 'info') }}">
                                                                    {{ __(ucfirst($priority)) }}
                                                                </span>
                                                                <span class="badge bg-secondary">
                                                                    {{ __(ucfirst(str_replace('_', ' ', $type))) }}
                                                                </span>
                                                                @if($isUnread)
                                                                    <span class="badge bg-primary">{{ __('New') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-ghost-secondary btn-sm" 
                                                                    type="button" 
                                                                    data-bs-toggle="dropdown" 
                                                                    aria-expanded="false">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
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
                            @else
                                <div class="empty">
                                    <div class="empty-img">
                                        <img src="{{ asset('assets/img/undraw_mailbox_re_dvds.svg') }}" 
                                             height="128" 
                                             alt="{{ __('No notifications') }}">
                                    </div>
                                    <p class="empty-title">{{ __('No notifications found') }}</p>
                                    <p class="empty-subtitle text-muted">
                                        @if($this->search || $this->filterType || $this->filterStatus || $this->filterPriority)
                                            {{ __('No notifications match your current filters.') }}
                                        @else
                                            {{ __('You have no notifications at the moment.') }}
                                        @endif
                                    </p>
                                    @if($this->search || $this->filterType || $this->filterStatus || $this->filterPriority)
                                        <div class="empty-action">
                                            <button type="button" class="btn btn-primary" wire:click="clearFilters">
                                                <i class="ti ti-filter-off me-1"></i>
                                                {{ __('Clear Filters') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @if($notifications->hasPages())
                            <div class="card-footer">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Bulk Actions') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Select an action to perform on the selected notifications:') }}</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" wire:click="bulkMarkAsRead" data-bs-dismiss="modal">
                            <i class="ti ti-check me-1"></i>
                            {{ __('Mark as Read') }}
                        </button>
                        <button type="button" class="btn btn-outline-primary" wire:click="bulkMarkAsUnread" data-bs-dismiss="modal">
                            <i class="ti ti-mail me-1"></i>
                            {{ __('Mark as Unread') }}
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="bulkDelete" data-bs-dismiss="modal">
                            <i class="ti ti-trash me-1"></i>
                            {{ __('Delete Selected') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-refresh notifications every 30 seconds
                setInterval(function() {
                    @this.call('refreshNotifications');
                }, 30000);

                // Handle bulk actions
                window.addEventListener('show-bulk-actions', function() {
                    const modal = new bootstrap.Modal(document.getElementById('bulkActionsModal'));
                    modal.show();
                });

                // Confirm delete actions
                window.addEventListener('confirm-delete', function(event) {
                    if (confirm(event.detail.message)) {
                        @this.call(event.detail.method, event.detail.id);
                    }
                });
            });
        </script>
    @endpush
</div>
