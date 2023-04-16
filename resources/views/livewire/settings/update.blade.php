<div>
    <div x-data="{ update: false }">
        <x-button wire:click="updateProject" primary type="button" @click="update = true">
            {{ __('System Update') }}
        </x-button>

        <div x-show="update" class="h-screen w-screen fixed top-0 left-0" x-show="show"
            x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            style="background-color: rgba(255, 255, 255, 0.5);">
            <div class="flex flex-col justify-center items-center h-full w-full">
                <div class="w-10 h-10 rounded-full border-4 border-dashed border-gray-400 animate-pulse"></div>
                @if ($message)
                    <div class="text-gray-600">
                        {{ $message }}
                    </div>
                @endif
                <x-button type="button" x-on:click="update = false" primary>
                    {{ 'Close' }}
                </x-button>
            </div>
        </div>
    </div>
</div>
