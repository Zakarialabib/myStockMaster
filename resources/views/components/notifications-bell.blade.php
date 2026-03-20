<?php

declare(strict_types=1);

use App\Models\ProductWarehouse;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\WithAlert;

new class extends Component
{
    use WithAlert;
    use WithPagination;

    public $how_many = 5;
    public $user;

    public function mount(): void
    {
        $this->user = auth()->user();
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

    public function loadMore(): void
    {
        $this->how_many += 5;
    }

    public function markAsRead($key): void
    {
        $notification = $this->user->unreadNotifications[$key];
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
};
?>

<div>
    <div x-data="{ showNotifications: false }">
        <button type="button"
            class="relative p-2 rounded-xl text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all"
            x-on:click="showNotifications = true">
            <i class="fas fa-bell text-lg" aria-hidden="true"></i>
            @if($this->lowQuantity->count() > 0)
                <span class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-error-500 rounded-full ring-2 ring-white dark:ring-gray-900">
                    {{ $this->lowQuantity->count() }}
                </span>
            @endif
        </button>

        <div x-show="showNotifications" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50"
            x-on:keydown.escape.window="showNotifications = false">

            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" x-on:click="showNotifications = false" aria-hidden="true"></div>

            <div class="absolute inset-y-0 right-0 pl-10 max-w-full flex">
                <div class="w-screen max-w-sm" x-show="showNotifications"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full">
                    <div class="h-full flex flex-col bg-white dark:bg-gray-900 rounded-l-2xl shadow-soft overflow-y-scroll border-l border-gray-200 dark:border-gray-800">
                        <div class="flex justify-between items-center py-5 px-6 border-b border-gray-200 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center">
                                    <i class="fas fa-bell text-primary-600 dark:text-primary-400"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Notifications') }}</h3>
                            </div>
                            <button type="button" x-on:click="showNotifications = false" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:text-gray-300 dark:hover:bg-gray-800 transition-all">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle text-warning-500"></i>
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">{{ __('Stock Alerts') }}</h4>
                                </div>
                                <x-button type="button" size="sm" secondary wire:click="loadMore">
                                    <i class="fas fa-plus mr-1"></i> {{ __('Load More') }}
                                </x-button>
                            </div>

                            <div class="space-y-3">
                                @forelse($this->lowQuantity as $productWarehouse)
                                    <div class="flex items-start p-4 bg-error-50 dark:bg-error-900/20 border border-error-200 dark:border-error-800 rounded-xl">
                                        <div class="w-10 h-10 rounded-lg bg-error-100 dark:bg-error-900/30 flex items-center justify-center shrink-0">
                                            <i class="fas fa-exclamation-triangle text-error-600 dark:text-error-400"></i>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="font-bold text-error-800 dark:text-error-200">{{ $productWarehouse->product->name }}</p>
                                            <p class="text-sm text-error-600 dark:text-error-400 mt-1">
                                                {{ __('Stock') }}: {{ $productWarehouse->qty }} / {{ __('Alert') }}: {{ $productWarehouse->stock_alert }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 rounded-full bg-success-100 dark:bg-success-900/30 mx-auto mb-3 flex items-center justify-center">
                                            <i class="fas fa-check-circle text-success-500 text-2xl"></i>
                                        </div>
                                        <p class="font-medium text-gray-600 dark:text-gray-400">{{ __('No stock alerts') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        @if($user->unreadNotifications->isNotEmpty())
                            <div class="px-6 py-5 border-t border-gray-200 dark:border-gray-800">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-info-circle text-primary-500"></i>
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white">{{ __('System Notifications') }}</h4>
                                    </div>
                                    <button wire:click="readAll" class="text-sm text-primary-600 dark:text-primary-400 hover:underline font-medium">{{ __('Read All') }}</button>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($user->unreadNotifications as $key => $notification)
                                        <div class="flex items-start p-4 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-xl">
                                            <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center shrink-0">
                                                <i class="fas fa-info-circle text-primary-600 dark:text-primary-400"></i>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="font-medium text-primary-800 dark:text-primary-200">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                            </div>
                                            <button type="button" wire:click="markAsRead('{{ $key }}')" class="p-1 text-gray-400 hover:text-primary-600 transition-colors">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-auto p-6 border-t border-gray-200 dark:border-gray-800">
                            <x-button type="button" danger class="w-full justify-center" wire:click="clear">
                                <i class="fas fa-trash-alt mr-2"></i> {{ __('Clear All Notifications') }}
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
