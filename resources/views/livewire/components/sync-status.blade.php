<div>
    <div x-data="{
        open: @entangle('showModal'),
        checkConnection() {
            $wire.checkConnection();
        }
    }" x-init="setInterval(() => checkConnection(), 30000)" class="fixed bottom-6 right-6 z-50">
        <!-- Floating Action Button -->
        <button @click="open = true"
            class="flex items-center justify-center w-14 h-14 rounded-full shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2"
            :class="$wire.isOnline ? 'bg-green-500 hover:bg-green-600 focus:ring-green-500' :
                'bg-red-500 hover:bg-red-600 focus:ring-red-500'">
            <span class="sr-only">Sync Status</span>
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>

            <!-- Pulse Effect for Online -->
            <span x-show="$wire.isOnline" class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
        </button>

        <!-- Modal -->
        <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"
                    aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="open" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                            :class="$wire.isOnline ? 'bg-green-100' : 'bg-red-100'">
                            <svg class="h-6 w-6" :class="$wire.isOnline ? 'text-green-600' : 'text-red-600'"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                System Synchronization
                            </h3>
                            <div class="mt-2 space-y-3">
                                <p class="text-sm text-gray-500">
                                    Status:
                                    <span class="font-bold" :class="$wire.isOnline ? 'text-green-600' : 'text-red-600'">
                                        {{ $isOnline ? 'Online' : 'Offline' }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-500">
                                    Last Synced: <span class="font-medium text-gray-900">{{ $lastSyncedAt }}</span>
                                </p>

                                @if ($syncMessage)
                                    <div class="p-2 rounded bg-gray-100 text-sm text-gray-700">
                                        {{ $syncMessage }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="triggerSync" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            <span wire:loading.remove>Sync Now</span>
                            <span wire:loading>Syncing...</span>
                        </button>
                        <button type="button" @click="open = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
