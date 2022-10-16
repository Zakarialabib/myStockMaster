<div>
    <x-modal wire:model="createWarehouse">
        <x-slot name="title">
            {{ __('Create Warehouse') }}
        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="create">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" wire:model="warehouse.name"
                            required />
                    </div>
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="phone" :value="__('Phone')" />
                        <x-input id="phone" class="block mt-1 w-full" type="text"
                            wire:model="warehouse.mobile" />
                    </div>
                    <x-accordion title="{{ __('Details') }}" class="flex flex-wrap">
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="email" :value="__('Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="email" wire:model="warehouse.email" />
                    </div>
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text" wire:model="warehouse.city" />
                    </div>
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="country" :value="__('Country')" />
                        <x-input id="country" class="block mt-1 w-full" type="text"
                            wire:model="warehouse.country" />
                    </div>
                    </x-accordion>
                </div>
                <div class="w-full flex justify-end">
                    <x-button secondary wire:click="$set('createWarehouse', false)">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button primary class="ml-3" wire:click="create">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
