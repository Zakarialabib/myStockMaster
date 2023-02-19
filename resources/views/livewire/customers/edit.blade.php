<div>
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit') }} 
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap">
                    <div class="w-full sm:w-1/2 px-3 mb-6">
                        <x-label for="name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" type="text"
                            wire:model.lazy="customer.name" required />
                        <x-input-error :messages="$errors->get('customer.name')" class="mt-2" />
                    </div>

                    <div class="w-full sm:w-1/2 px-3 mb-6">
                        <x-label for="phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text"
                            wire:model.lazy="customer.phone" />
                        <x-input-error :messages="$errors->get('customer.phone')" class="mt-2" />
                    </div>
                    <x-accordion title="{{ __('Details') }}" >
                        <div class="flex flex-wrap">

                        
                        <div class="w-full sm:w-1/2 px-3 mb-6">
                            <x-label for="email" :value="__('Email')" />
                            <x-input id="email" class="block mt-1 w-full" type="email"
                                wire:model.lazy="customer.email" />
                            <x-input-error :messages="$errors->get('customer.email')" class="mt-2" />
                        </div>

                        <div class="w-full sm:w-1/2 px-3 mb-6">
                            <x-label for="address" :value="__('Address')" />
                            <x-input id="address" class="block mt-1 w-full" type="text"
                                wire:model.lazy="customer.address" />
                            <x-input-error :messages="$errors->get('customer.address')" class="mt-2" />
                        </div>

                        <div class="w-full sm:w-1/2 px-3 mb-6">
                            <x-label for="city" :value="__('City')" />
                            <x-input id="city" class="block mt-1 w-full" type="text"
                                wire:model.lazy="customer.city" />
                            <x-input-error :messages="$errors->get('customer.city')" class="mt-2" />
                        </div>

                        <div class="w-full sm:w-1/2 px-3 mb-6">
                            <x-label for="tax_number" :value="__('Tax Number')" />
                            <x-input id="tax_number" class="block mt-1 w-full" type="text"
                                wire:model.lazy="customer.tax_number" />
                            <x-input-error :messages="$errors->get('customer.tax_number')" for="" class="mt-2" />
                        </div>
                        </div>
                    </x-accordion>

                    <div class="w-full px-3">
                        <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>