<div>
    <x-modal wire:model="createWarehouse">
        <x-slot name="title">
            {{ __('Create Warehouse') }}
        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="create">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" wire:model="warehouse.name"
                            required />
                    </div>
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="phone" :value="__('Phone')" />
                        <x-input id="phone" class="block mt-1 w-full" type="text"
                            wire:model="warehouse.phone" />
                    </div>
                    <x-accordion title="{{ __('Details') }}" >
                        <div class="flex flex-wrap">
                            
                        
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="email" :value="__('Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="email" wire:model="warehouse.email" />
                    </div>
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text" wire:model="warehouse.city" />
                    </div>
                    <div class="hidden xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="country" :value="__('Country')" />
                        <x-input id="country" class="block mt-1 w-full" type="text"
                            wire:model="warehouse.country" />
                    </div>
                </div>
                    </x-accordion>
                    <div class="w-full flex justify-start px-3">
                        <x-button primary wire:click="create" wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
