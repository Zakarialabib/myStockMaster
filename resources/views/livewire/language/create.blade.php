<div>
    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('Create Language') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="create">

                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" :errors="$errors" />

                <div class="flex flex-wrap justify-center">
                    <div class="lg:w-1/2 sm:w-full px-3">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" type="text" class="block mt-1 w-full" wire:model.lazy="language.name" />
                        <x-input-error :messages="$errors->get('language.name')" for="name" class="mt-2" />
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-3">
                        <x-label for="code" :value="__('Code')" />
                        <x-input id="code" type="text" class="block mt-1 w-full"
                            wire:model.lazy="language.code" />
                        <x-input-error :messages="$errors->get('language.code')" for="code" class="mt-2" />
                    </div>
                    <div class="w-full px-3 my-2">
                        <x-button type="submit" primary class="w-full">
                            {{ __('Save') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
