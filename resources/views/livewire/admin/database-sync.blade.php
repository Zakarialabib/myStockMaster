<div>
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Database sync') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Database sync status') }}
                </p>
            </div>

            <div class="p-6">
                <!-- Sync Status -->
                <div class="mb-6">
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ __('admin.database_sync.status.title') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                @if ($isOnline)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ __('Online') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ __('Offline') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Last sync') }}
                            </p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $lastSync ? $lastSync->format('M j, Y g:i A') : __('Never') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sync Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sync to Offline -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ __('admin.database_sync.sync_to_offline.title') }}
                            </h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ __('admin.database_sync.sync_to_offline.description') }}
                        </p>
                        <button wire:click="syncToOffline" wire:loading.attr="disabled" wire:target="syncToOffline"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
                            @disabled(!$isOnline)>
                            <span wire:loading.remove wire:target="syncToOffline">
                                {{ __('admin.database_sync.sync_to_offline.button') }}
                            </span>
                            <span wire:loading wire:target="syncToOffline" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                {{ __('admin.database_sync.syncing') }}
                            </span>
                        </button>
                    </div>

                    <!-- Sync to Online -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ __('admin.database_sync.sync_to_online.title') }}
                            </h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ __('admin.database_sync.sync_to_online.description') }}
                        </p>
                        <button wire:click="syncToOnline" wire:loading.attr="disabled" wire:target="syncToOnline"
                            class="w-full bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
                            @disabled(!$isOnline)>
                            <span wire:loading.remove wire:target="syncToOnline">
                                {{ __('admin.database_sync.sync_to_online.button') }}
                            </span>
                            <span wire:loading wire:target="syncToOnline" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                {{ __('admin.database_sync.syncing') }}
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Sync Log -->
                @if (count($syncLog) > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('admin.database_sync.log.title') }}
                        </h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 max-h-64 overflow-y-auto">
                            @foreach ($syncLog as $entry)
                                <div class="flex items-start space-x-3 mb-2 last:mb-0">
                                    <div class="shrink-0">
                                        @if ($entry['type'] === 'success')
                                            <svg class="w-4 h-4 text-green-500 mt-0.5" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @elseif($entry['type'] === 'error')
                                            <svg class="w-4 h-4 text-red-500 mt-0.5" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-blue-500 mt-0.5" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $entry['message'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $entry['timestamp'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
