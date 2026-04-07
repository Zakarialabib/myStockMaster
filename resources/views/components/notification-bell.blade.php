<?php

declare(strict_types=1);

use App\Models\ProductWarehouse;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithAlert;
    use WithPagination;

    public $how_many = 5;

    public $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    #[Computed]
    public function lowQuantity()
    {
        return ProductWarehouse::with('product')
            ->select('product_id', 'qty', 'stock_alert')
            ->whereColumn('qty', '<=', 'stock_alert')
            ->take($this->how_many)
            ->get();
    }

    #[Computed]
    public function unreadNotifications()
    {
        return $this->user->unreadNotifications()
            ->take($this->how_many)
            ->get();
    }

    #[Computed]
    public function totalUnreadCount()
    {
        return $this->user->unreadNotifications->count() + $this->lowQuantity->count();
    }

    public function loadMore(): void
    {
        $this->how_many += 5;
    }

    public function markAsRead($id): void
    {
        $notification = $this->user->unreadNotifications()->findOrFail($id);
        $notification->markAsRead();
    }

    public function readAll(): void
    {
        $this->user->unreadNotifications->markAsRead();
    }

    public function clear(): void
    {
        $this->user->notifications()->delete();
    }

    public function handleNotificationClick($id)
    {
        $this->markAsRead($id);

        $notification = $this->user->notifications()->findOrFail($id);
        $data = $notification->data;

        if (isset($data['route'])) {
            return redirect()->to($data['route']);
        }

        return redirect()->route('notifications.manager');
    }
};
?>

<div>
    <div x-data="{ showNotifications: false }" class="relative">
        <!-- Bell Icon Trigger -->
        <button type="button"
            class="relative p-2.5 rounded-xl text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all focus:outline-none"
            @click="showNotifications = true">
            <i class="fas fa-bell text-xl" aria-hidden="true"></i>
            @if($this->totalUnreadCount > 0)
            <span class="absolute top-1.5 right-1.5 flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-error-500 rounded-full ring-2 ring-white dark:ring-gray-900 animate-bounce">
                {{ $this->totalUnreadCount > 99 ? '99+' : $this->totalUnreadCount }}
            </span>
            @endif
        </button>

        <!-- Slide-over Drawer -->
        <div x-show="showNotifications"
            x-cloak
            class="fixed inset-0 z-[100] overflow-hidden"
            aria-labelledby="slide-over-title"
            role="dialog"
            aria-modal="true">

            <!-- Backdrop -->
            <div x-show="showNotifications"
                x-transition:enter="ease-in-out duration-500"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in-out duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                @click="showNotifications = false"
                aria-hidden="true"></div>

            <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                <!-- Panel Content -->
                <div x-show="showNotifications"
                    x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="w-screen max-w-md"
                    @click.away="showNotifications = false">

                    <div class="h-full flex flex-col bg-white dark:bg-gray-900 shadow-2xl border-l border-gray-100 dark:border-gray-800">
                        <!-- Header -->
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/30">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
                                    <i class="fas fa-bell text-primary-600 dark:text-primary-400 text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900 dark:text-white" id="slide-over-title">
                                        {{ __('Notifications') }}
                                    </h2>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                        {{ $this->totalUnreadCount }} {{ __('unread items') }}
                                    </p>
                                </div>
                            </div>
                            <button type="button"
                                class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all focus:outline-none"
                                @click="showNotifications = false">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>

                        <!-- Scrollable Area -->
                        <div class="flex-1 overflow-y-auto custom-scrollbar">
                            <!-- Stock Alerts Section -->
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-6 bg-error-500 rounded-full"></span>
                                        <h3 class="font-bold text-gray-900 dark:text-white uppercase tracking-wider text-sm">{{ __('Stock Alerts') }}</h3>
                                    </div>
                                    <span class="bg-error-100 text-error-700 dark:bg-error-900/30 dark:text-error-400 text-[10px] font-bold px-2 py-1 rounded-full uppercase">
                                        {{ $this->lowQuantity->count() }} {{ __('Items') }}
                                    </span>
                                </div>

                                <div class="space-y-3">
                                    @forelse($this->lowQuantity as $productWarehouse)
                                    <div class="group relative flex items-start p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 rounded-2xl hover:border-error-200 dark:hover:border-error-800 transition-all duration-300">
                                        <div class="w-10 h-10 rounded-xl bg-error-100 dark:bg-error-900/30 flex items-center justify-center shrink-0">
                                            <i class="fas fa-exclamation-triangle text-error-600 dark:text-error-400"></i>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex justify-between items-start">
                                                <p class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1">
                                                    {{ $productWarehouse->product->name }}
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-3 mt-2">
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-tight">{{ __('In Stock') }}</span>
                                                    <span class="text-sm font-black text-error-600 dark:text-error-400">{{ $productWarehouse->qty }}</span>
                                                </div>
                                                <div class="w-px h-6 bg-gray-200 dark:bg-gray-700"></div>
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-tight">{{ __('Alert At') }}</span>
                                                    <span class="text-sm font-bold text-gray-600 dark:text-gray-300">{{ $productWarehouse->stock_alert }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-8 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                                        <div class="w-12 h-12 rounded-full bg-success-100 dark:bg-success-900/30 mx-auto mb-3 flex items-center justify-center">
                                            <i class="fas fa-check-circle text-success-500 text-xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('All stock levels are optimal') }}</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- System Notifications Section -->
                            <div class="px-6 pb-6">
                                <div class="flex items-center justify-between mb-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-6 bg-primary-500 rounded-full"></span>
                                        <h3 class="font-bold text-gray-900 dark:text-white uppercase tracking-wider text-sm">{{ __('System Alerts') }}</h3>
                                    </div>
                                    <button type="button"
                                        wire:click="readAll"
                                        class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase hover:underline">
                                        {{ __('Mark All Read') }}
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    @forelse ($this->unreadNotifications as $notification)
                                    <div class="group relative flex items-start p-4 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 shadow-sm hover:shadow-md cursor-pointer"
                                        wire:click="handleNotificationClick('{{ $notification->id }}')">
                                        <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center shrink-0">
                                            <i class="fas fa-info-circle text-primary-600 dark:text-primary-400"></i>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <p class="font-bold text-gray-900 dark:text-white text-sm">
                                                {{ $notification->data['title'] ?? __('System Alert') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                                {{ $notification->data['message'] ?? 'New notification received' }}
                                            </p>
                                            <span class="text-[10px] text-gray-400 mt-2 block font-medium">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <button type="button"
                                            wire:click.stop="markAsRead('{{ $notification->id }}')"
                                            class="absolute top-4 right-4 p-1.5 text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 opacity-0 group-hover:opacity-100 transition-all">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </div>
                                    @empty
                                    <div class="text-center py-8 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 mx-auto mb-3 flex items-center justify-center">
                                            <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('No new system alerts') }}</p>
                                    </div>
                                    @endforelse
                                </div>

                                @if($this->totalUnreadCount > $this->how_many)
                                <button type="button"
                                    wire:click="loadMore"
                                    class="w-full mt-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all uppercase tracking-widest">
                                    {{ __('Load More Items') }}
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="px-6 py-6 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30 flex flex-col gap-3">
                            <a href="{{ route('notifications.manager') }}"
                                wire:navigate
                                class="w-full py-3 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold text-sm rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-primary-500 dark:hover:border-primary-500 transition-all text-center"
                                @click="showNotifications = false">
                                <i class="fas fa-list-ul mr-2 text-primary-500"></i> {{ __('View Notification Manager') }}
                            </a>
                            <button type="button"
                                wire:click="clear"
                                class="w-full py-3 text-error-600 dark:text-error-400 font-bold text-sm hover:bg-error-50 dark:hover:bg-error-900/20 rounded-xl transition-all uppercase tracking-wider">
                                <i class="fas fa-trash-alt mr-2"></i> {{ __('Clear History') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>