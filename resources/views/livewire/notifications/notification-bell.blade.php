<div wire:poll.60s>
    <x-dropdown align="right" width="80">
        <x-slot name="trigger">
            <button type="button" class="relative text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors focus:outline-none">
                <i class="fas fa-bell text-[20px]"></i>
                @if($this->totalUnreadCount > 0)
                    <span class="absolute -top-1.5 -right-1.5 bg-error-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-sm">
                        {{ $this->totalUnreadCount > 99 ? '99+' : $this->totalUnreadCount }}
                    </span>
                @endif
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h6 class="font-bold text-gray-900 dark:text-white">{{ __('Notifications') }}</h6>
                @if($this->totalUnreadCount > 0)
                    <button type="button" class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium" wire:click="markAllAsRead">
                        {{ __('Mark all read') }}
                    </button>
                @endif
            </div>

            <div class="max-h-[300px] overflow-y-auto">
                @forelse($this->recentNotifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    <div class="px-4 py-3 border-b border-gray-50 dark:border-gray-800/50 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors cursor-pointer {{ $isUnread ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }}"
                         wire:click="handleNotificationClick('{{ $notification->id }}')">
                        <div class="flex gap-3">
                            <div class="shrink-0 mt-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isUnread ? 'bg-primary-100 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}">
                                    <i class="fas fa-bell text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate {{ $isUnread ? 'font-bold' : '' }}">
                                    {{ Str::limit($data['title'] ?? __('Notification'), 40) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                    {{ Str::limit($data['message'] ?? __('No message available'), 60) }}
                                </p>
                                <p class="text-[10px] text-gray-400 mt-1 font-medium">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center">
                        <i class="fas fa-bell-slash text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No notifications') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ __('You\'re all caught up!') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="p-2 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                <a href="{{ route('notifications.index') }}" class="block w-full text-center text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 py-1.5 transition-colors">
                    {{ __('View All') }}
                </a>
            </div>
        </x-slot>
    </x-dropdown>
</div>
