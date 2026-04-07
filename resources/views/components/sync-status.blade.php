<?php

declare(strict_types=1);

use App\Jobs\SyncDatabaseJob;
use App\Services\DatabaseSyncService;
use App\Services\EnvironmentService;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Async;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public bool $isOnline = false;

    public bool $isSyncing = false;

    public bool $showModal = false;

    public string $syncMessage = '';

    public ?string $lastSyncedAt = null;

    public function mount(): void
    {
        $this->refreshStatus();
    }

    public function refreshStatus(): void
    {
        if (! EnvironmentService::isDesktop()) {
            return;
        }

        $this->isOnline = app(DatabaseSyncService::class)->isOnlineAvailable();
        $lastSyncedAt = Cache::get('database_sync.last_sync');
        $this->lastSyncedAt = is_string($lastSyncedAt) ? $lastSyncedAt : null;
    }

    #[Computed]
    public function lastSyncLabel(): string
    {
        return $this->lastSyncedAt ?? (string) __('Never');
    }

    #[On('syncData')]
    #[Async]
    public function triggerSync(): void
    {
        if (! EnvironmentService::isDesktop()) {
            return;
        }

        $this->refreshStatus();

        if (! $this->isOnline) {
            $this->syncMessage = (string) __('Server database is not reachable.');

            return;
        }

        $this->isSyncing = true;
        $this->syncMessage = (string) __('Synchronization started.');

        SyncDatabaseJob::dispatch('both');

        $this->isSyncing = false;
        $this->syncMessage = (string) __('Synchronization job queued.');
    }
};
?>

<div wire:poll.120s>
    @if (\App\Services\EnvironmentService::isDesktop())
        <div x-data="{ open: @entangle('showModal') }" class="fixed bottom-6 right-6 z-50">
            <button @click="open = true"
                class="flex h-14 w-14 items-center justify-center rounded-full shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2"
                :class="$wire.isOnline ? 'bg-green-500 hover:bg-green-600 focus:ring-green-500' :
                    'bg-red-500 hover:bg-red-600 focus:ring-red-500'">
                <span class="sr-only">{{ __('Sync status') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span x-show="$wire.isOnline" class="absolute -mr-1 -mt-1 flex h-3 w-3">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex h-3 w-3 rounded-full bg-green-500"></span>
                </span>
            </button>

            <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog"
                aria-modal="true">
                <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"
                        aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

                    <div x-show="open" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
                        x-transition:leave-end="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                        class="inline-block w-full transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left align-bottom shadow-xl transition-all sm:my-8 sm:max-w-lg sm:p-6 sm:align-middle">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                :class="$wire.isOnline ? 'bg-green-100' : 'bg-red-100'">
                                <svg class="h-6 w-6" :class="$wire.isOnline ? 'text-green-600' : 'text-red-600'"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <div class="mt-3 w-full text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">
                                    {{ __('Synchronization') }}
                                </h3>
                                <div class="mt-3 space-y-3">
                                    <p class="text-sm text-gray-600">
                                        {{ __('Status') }}:
                                        <span class="font-semibold"
                                            :class="$wire.isOnline ? 'text-green-600' : 'text-red-600'">
                                            {{ $isOnline ? __('Online') : __('Offline') }}
                                        </span>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ __('Last sync') }}:
                                        <span class="font-medium text-gray-900">{{ $this->lastSyncLabel }}</span>
                                    </p>
                                    @if ($syncMessage !== '')
                                        <div class="rounded bg-gray-100 p-2 text-sm text-gray-700">
                                            {{ $syncMessage }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click="triggerSync" wire:loading.attr="disabled"
                                wire:target="triggerSync"
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 sm:ml-3 sm:w-auto sm:text-sm">
                                <span wire:loading.remove wire:target="triggerSync">{{ __('Sync now') }}</span>
                                <span wire:loading wire:target="triggerSync">{{ __('Syncing...') }}</span>
                            </button>
                            <button type="button" wire:click="refreshStatus"
                                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                                {{ __('Refresh') }}
                            </button>
                            <button type="button" @click="open = false"
                                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mr-3 sm:mt-0 sm:w-auto sm:text-sm">
                                {{ __('Close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
