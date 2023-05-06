<div>
    <div x-data="{ update: false, showProgressBar: false, showConfirmationDialog: false }">
        @if ($updateAvailable)
            <x-button wire:click="showConfirmationDialog" primary type="button" @click="showConfirmationDialog = true">
                {{ __('System Update') }}
            </x-button>
        @else
            <x-button wire:click="checkForUpdates" primary type="button" @click="showProgressBar = true">
                {{ __('Check For Updates') }}
            </x-button>
        @endif

        <div x-show="showConfirmationDialog" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-4">
                <div class="text-xl font-bold mb-2">{{ __('Confirm Update') }}</div>
                <div class="mb-4">{{ __('Are you sure you want to update the system?') }}</div>
                <div class="flex justify-end">
                    <x-button type="button" x-on:click="showConfirmationDialog = false" class="mr-2">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button type="button" x-on:click="update = true; showConfirmationDialog = false" primary>
                        {{ __('Update') }}
                    </x-button>
                </div>
            </div>
        </div>

        <div x-show="showProgressBar" class="h-screen w-screen fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            style="background-color: rgba(255, 255, 255, 0.5);">
            <div class="bg-white rounded-lg p-10">
                <div class="text-xl font-bold mb-2">{{ __('Updating System') }}</div>
                <div class="w-10 h-10 rounded-full border-4 border-dashed border-gray-400 animate-pulse"></div>
                @if ($message)
                    <div class="text-gray-600">
                        {{ $message }}
                    </div>
                @endif
                <div class="flex justify-end mt-4">
                    <x-button type="button" x-on:click="showProgressBar = false" primary>
                        {{ __('Close') }}
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>
