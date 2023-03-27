<div>
    <!-- Create Modal -->
    <x-modal wire:model="syncModal">
        <x-slot name="title">
            {{ __('Sync Your Online Store with Products') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="recieveData">
                <div class="py-4">
                    <div class="mt-4 px-3">
                        <x-label for="type" :value="__('Type')" />
                        <select wire:model.lazy="type" id="type" name="type"
                            class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500">
                            <option value="">{{ 'Select way to sync' }}</option>
                            @foreach (\App\Enums\IntegrationType::cases() as $type)
                                <option value="{{ $type->value }}">
                                    {{ __($type->name) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" for="type" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-center space-x-2 px-3">
                        <x-button primary type="submit" wire:loading.attr="disabled">
                            {{ __('Recieve data') }}
                        </x-button>
                        <x-button primary wire:click="sendData" wire:loading.attr="disabled">
                            {{ __('Send Data') }}
                        </x-button>
                    </div>

                    {{-- Display Status informations --}}
                    <div class="mt-4 w-full flex-grow justify-end">

                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
