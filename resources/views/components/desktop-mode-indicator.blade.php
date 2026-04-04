<?php

declare(strict_types=1);

use App\Services\EnvironmentService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component
{
    public bool $isDesktop = false;

    public bool $isOffline = false;

    public ?string $lastSync = null;

    public bool $showDetails = false;

    public function mount(): void
    {
        $this->refresh();
    }

    public function toggleDetails(): void
    {
        $this->showDetails = !$this->showDetails;
    }

    public function goToSync()
    {
        return redirect()->route('admin.database-sync');
    }

    public function refresh(): void
    {
        $this->isDesktop = EnvironmentService::isDesktop();
        $this->isOffline = EnvironmentService::isOfflineMode();
        $lastSync = Cache::get('database_sync.last_sync');
        $this->lastSync = is_string($lastSync) ? $lastSync : null;
    }

    #[Computed]
    public function lastSyncLabel(): string
    {
        return $this->lastSync ?? (string) __('desktop.mode_indicator.never');
    }
};
?>

<div>
    @if ($isDesktop)
        <div class="fixed bottom-4 right-4 z-50">
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Main Indicator -->
                <div class="flex items-center p-3 cursor-pointer" wire:click="toggleDetails">
                    <div class="flex items-center space-x-2">
                        <!-- Desktop Icon -->
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>

                        <!-- Status Text -->
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('desktop.mode_indicator.desktop_mode') }}
                        </span>

                        <!-- Online/Offline Status -->
                        <div class="flex items-center">
                            @if ($isOffline)
                                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                <span class="ml-1 text-xs text-red-600 dark:text-red-400">
                                    {{ __('desktop.mode_indicator.offline') }}
                                </span>
                            @else
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="ml-1 text-xs text-green-600 dark:text-green-400">
                                    {{ __('desktop.mode_indicator.online') }}
                                </span>
                            @endif
                        </div>

                        <!-- Expand/Collapse Icon -->
                        <svg class="w-4 h-4 text-gray-400 transform transition-transform {{ $showDetails ? 'rotate-180' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Details Panel -->
                @if ($showDetails)
                    <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 p-3">
                        <div class="space-y-2">
                            <!-- Last Sync Info -->
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600 dark:text-gray-400">
                                    {{ __('desktop.mode_indicator.last_sync') }}:
                                </span>
                                <span class="text-gray-900 dark:text-white">
                                    {{ $this->lastSyncLabel }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2 pt-2">
                                <button wire:click="goToSync"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-1.5 px-3 rounded transition duration-200">
                                    {{ __('desktop.mode_indicator.sync_data') }}
                                </button>

                                <button wire:click="refresh"
                                    class="bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium py-1.5 px-3 rounded transition duration-200"
                                    title="{{ __('desktop.mode_indicator.refresh') }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
