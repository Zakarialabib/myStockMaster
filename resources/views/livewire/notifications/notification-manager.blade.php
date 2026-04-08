<div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                {{ __('Notification Manager') }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                {{ __('View and manage all your system alerts and notifications') }}
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            <button type="button" 
                    wire:click="markAllAsRead"
                    class="flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 font-bold text-sm rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md hover:border-primary-500 transition-all group">
                <i class="fas fa-check-double text-primary-500 group-hover:scale-110 transition-transform"></i>
                <span>{{ __('Mark All as Read') }}</span>
            </button>
            
            <button type="button" 
                    wire:click="deleteSelected"
                    @if(empty($selectedNotifications)) disabled @endif
                    class="flex items-center gap-2 px-4 py-2.5 bg-error-50 dark:bg-error-900/20 text-error-700 dark:text-error-400 font-bold text-sm rounded-xl border border-error-100 dark:border-error-800 shadow-sm hover:shadow-md hover:bg-error-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all group">
                <i class="fas fa-trash-alt group-hover:shake transition-transform"></i>
                <span>{{ __('Delete Selected') }}</span>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center shrink-0">
                <i class="fas fa-bell text-primary-600 dark:text-primary-400 text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Total Notifications') }}</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white leading-none mt-1">{{ $notifications->total() }}</p>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-error-50 dark:bg-error-900/30 flex items-center justify-center shrink-0">
                <i class="fas fa-exclamation-triangle text-error-600 dark:text-error-400 text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Stock Alerts') }}</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white leading-none mt-1">
                    {{ $this->lowQuantity->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-success-50 dark:bg-success-900/30 flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle text-success-600 dark:text-success-400 text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Read Today') }}</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white leading-none mt-1">
                    {{ auth()->user()->notifications()->where('read_at', '>=', now()->startOfDay())->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-gray-800/50 flex items-center justify-center shrink-0">
                <i class="fas fa-history text-gray-600 dark:text-gray-400 text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('System Alerts') }}</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white leading-none mt-1">
                    {{ auth()->user()->unreadNotifications->count() }}
                </p>
            </div>
        </div>
    </div>

    @if($this->lowQuantity->count() > 0)
        <!-- Stock Alerts Section -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-soft border border-error-100 dark:border-error-900/30 overflow-hidden">
            <div class="px-6 py-4 border-b border-error-50 dark:border-error-900/20 flex items-center justify-between bg-error-50/30 dark:bg-error-900/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-error-100 dark:bg-error-900/40 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-error-600 dark:text-error-400"></i>
                    </div>
                    <h3 class="font-bold text-error-900 dark:text-error-200 tracking-tight">{{ __('Urgent Stock Alerts') }}</h3>
                </div>
                <span class="text-[10px] font-black text-error-600 dark:text-error-400 uppercase tracking-widest">
                    {{ $this->lowQuantity->count() }} {{ __('Items require attention') }}
                </span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($this->lowQuantity as $productWarehouse)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800 group hover:border-error-200 dark:hover:border-error-800 transition-all">
                            <div class="w-12 h-12 rounded-xl bg-white dark:bg-gray-900 flex items-center justify-center shadow-sm shrink-0">
                                <i class="fas fa-package text-gray-400 group-hover:text-error-500 transition-colors"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $productWarehouse->product->name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">{{ __('Stock') }}:</span>
                                    <span class="text-xs font-black text-error-600 dark:text-error-400">{{ $productWarehouse->qty }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase ml-2">{{ __('Limit') }}:</span>
                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ $productWarehouse->stock_alert }}</span>
                                </div>
                            </div>
                            {{--<a href="{{ route('products.edit', $productWarehouse->product->id) }}" 
                               wire:navigate
                               class="p-2 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                <i class="fas fa-external-link-alt text-xs"></i>
                            </a>--}}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-800">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">{{ __('Search Content') }}</label>
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-500 transition-colors"></i>
                    <input type="text" 
                           wire:model.live.debounce.300ms="searchTerm" 
                           placeholder="{{ __('Find notification...') }}"
                           class="w-full pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary-500/20 transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">{{ __('Filter by Type') }}</label>
                <select wire:model.live="filterType" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary-500/20 transition-all appearance-none cursor-pointer">
                    <option value="all">{{ __('All Types') }}</option>
                    @foreach($notificationTypes as $type)
                        <option value="{{ $type }}">{{ __(ucfirst(str_replace('_', ' ', $type))) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">{{ __('Status') }}</label>
                <select wire:model.live="filterRead" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary-500/20 transition-all appearance-none cursor-pointer">
                    <option value="all">{{ __('All Status') }}</option>
                    <option value="unread">{{ __('Unread Only') }}</option>
                    <option value="read">{{ __('Read Only') }}</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="button" 
                        wire:click="$set('searchTerm', ''); $set('filterType', 'all'); $set('filterRead', 'all');"
                        class="w-full py-2.5 text-gray-500 hover:text-primary-600 font-bold text-xs uppercase tracking-widest transition-colors">
                    <i class="fas fa-undo mr-2"></i> {{ __('Reset Filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/30">
            <div class="flex items-center gap-3">
                <input type="checkbox" 
                       wire:model.live="selectAll"
                       class="w-5 h-5 rounded-lg border-gray-300 dark:border-gray-700 text-primary-600 focus:ring-primary-500/20 transition-all cursor-pointer">
                <h3 class="font-bold text-gray-900 dark:text-white tracking-tight">{{ __('Notification List') }}</h3>
            </div>
            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                {{ $notifications->count() }} {{ __('Notifications on this page') }}
            </div>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                    $type = $notification->type ?? 'system';
                @endphp
                <div class="group relative flex items-center gap-4 p-6 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-all duration-300 {{ $isUnread ? 'bg-primary-50/20 dark:bg-primary-900/10' : '' }}">
                    <div class="flex items-center gap-4 shrink-0">
                        <input type="checkbox" 
                               wire:model.live="selectedNotifications" 
                               value="{{ $notification->id }}"
                               class="w-5 h-5 rounded-lg border-gray-300 dark:border-gray-700 text-primary-600 focus:ring-primary-500/20 transition-all cursor-pointer">
                        
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 {{ $isUnread ? 'bg-primary-100 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500' }}">
                            <i class="fas {{ $type === 'stock_alert' ? 'fa-package' : 'fa-bell' }} text-lg"></i>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                {{ $data['title'] ?? __('System Notification') }}
                            </h4>
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium leading-relaxed max-w-2xl">
                            {{ $data['message'] ?? 'New system alert received.' }}
                        </p>
                        <div class="flex items-center gap-4 mt-3">
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-tight">
                                <i class="far fa-clock"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-800 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest border border-gray-200 dark:border-gray-700">
                                {{ str_replace('_', ' ', $type) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all">
                        <div class="flex justify-start space-x-2">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-button primary type="button" class="text-white flex items-center">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </x-button>
                                </x-slot>
                                <x-slot name="content">
                                    @if($isUnread)
                                        <x-dropdown-link wire:click="markAsRead('{{ $notification->id }}')" wire:loading.attr="disabled">
                                            <i class="fas fa-eye"></i>
                                            {{ __('Mark as Read') }}
                                        </x-dropdown-link>
                                    @else
                                        <x-dropdown-link wire:click="markAsUnread('{{ $notification->id }}')" wire:loading.attr="disabled">
                                            <i class="fas fa-eye-slash"></i>
                                            {{ __('Mark as Unread') }}
                                        </x-dropdown-link>
                                    @endif
                                    
                                    <x-dropdown-link wire:click="deleteNotification('{{ $notification->id }}')" wire:loading.attr="disabled">
                                        <i class="fas fa-trash-alt"></i>
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 bg-gray-50/30 dark:bg-gray-800/10">
                    <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-6">
                        <i class="fas fa-bell-slash text-gray-300 dark:text-gray-600 text-4xl"></i>
                    </div>
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">{{ __('No notifications found') }}</h5>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                        {{ __('Try adjusting your filters or check back later.') }}
                    </p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
