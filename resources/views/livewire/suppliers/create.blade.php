<div>
    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('Create Supplier') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit.prevent="create">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="md:w-1/2 sm:w-full px-3 mb-2 lg:mb-0">
                        <x-label for="supplier.name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model.lazy="supplier.name" />
                        <x-input-error :messages="$errors->get('supplier.name')" class="mt-2" />
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3 mb-2 lg:mb-0">
                        <x-label for="supplier.phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text"
                            wire:model.lazy="supplier.phone" />
                        <x-input-error :messages="$errors->get('supplier.phone')" class="mt-2" />
                    </div>
                    <x-accordion title="{{ __('Details') }}">
                        <div class="flex flex-wrap -mx-2 mb-3">
                            <div class="md:w-1/2 sm:w-full px-3 mb-2 lg:mb-0">
                                <x-label for="email" :value="__('Email')" />
                                <x-input id="email" class="block mt-1 w-full" type="email"
                                    wire:model.lazy="supplier.email" />
                                <x-input-error :messages="$errors->get('supplier.email')" class="mt-2" />
                            </div>

                            <div class="md:w-1/2 sm:w-full px-3 mb-2 lg:mb-0">
                                <x-label for="address" :value="__('Address')" />
                                <x-input id="address" class="block mt-1 w-full" type="text"
                                    wire:model.lazy="supplier.address" />
                                <x-input-error :messages="$errors->get('supplier.address')" class="mt-2" />
                            </div>

                            <div class="md:w-1/2 sm:w-full px-3 mb-2 lg:mb-0">
                                <x-label for="city" :value="__('City')" />
                                <x-input id="city" class="block mt-1 w-full" type="text"
                                    wire:model.lazy="supplier.city" />
                                <x-input-error :messages="$errors->get('supplier.city')" class="mt-2" />
                            </div>

                            <div class="md:w-1/2 sm:w-full px-3 mb-2 lg:mb-0">
                                <x-label for="tax_number" :value="__('Tax Number')" />
                                <x-input id="tax_number" class="block mt-1 w-full" type="text"
                                    wire:model.lazy="supplier.tax_number" />
                                <x-input-error :messages="$errors->get('supplier.tax_number')" class="mt-2" />
                            </div>
                        </div>
                    </x-accordion>


                    <div class="w-full px-3">
                        <x-button primary type="submit" class="w-full text-center" 
                             wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
