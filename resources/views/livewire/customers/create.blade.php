<div>
    <x-modal wire:model="createCustomer">
        <x-slot name="title">
            {{ __('Create Customer') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit.prevent="create">
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="customer.name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model.defer="customer.name" />
                        <x-input-error :messages="$errors->get('customer.name')" for="customer.name" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="customer.phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text"
                            wire:model.defer="customer.phone" />
                        <x-input-error :messages="$errors->get('customer.phone')" for="customer.phone" class="mt-2" />
                    </div>
                    <x-accordion title="{{ __('Details') }}"  class="flex flex-wrap">

                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="email" :value="__('Email')" />
                            <x-input id="email" class="block mt-1 w-full" type="email"
                                wire:model.defer="customer.email" />
                            <x-input-error :messages="$errors->get('customer.email')" class="mt-2" />
                        </div>

                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="address" :value="__('Address')" />
                            <x-input id="address" class="block mt-1 w-full" type="text"
                                wire:model.defer="customer.address" />
                            <x-input-error :messages="$errors->get('customer.address')" class="mt-2" />
                        </div>

                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="city" :value="__('City')" />
                            <x-input id="city" class="block mt-1 w-full" type="text"
                                wire:model.defer="customer.city" />
                            <x-input-error :messages="$errors->get('customer.city')" class="mt-2" />
                        </div>

                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="tax_number" :value="__('Tax Number')" />
                            <x-input id="tax_number" class="block mt-1 w-full" type="text"
                                wire:model.defer="customer.tax_number" />
                            <x-input-error :messages="$errors->get('customer.tax_number')" class="mt-2" />
                        </div>
                    </x-accordion>

                    <div class="w-full flex items-center justify-start space-x-2 mt-4">
                        <x-button primary wire:click="create" wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                        <x-button secondary wire:click="$set('createCustomer', false)" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
