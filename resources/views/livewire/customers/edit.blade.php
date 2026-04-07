<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Edit') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit="update" class="space-y-4">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="w-full">
                        <x-label for="name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" type="text" wire:model="form.name" required />
                        <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
                    </div>

                    <div class="w-full">
                        <x-label for="phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text" wire:model="form.phone" />
                        <x-input-error :messages="$errors->get('form.phone')" class="mt-2" />
                    </div>

                    <div class="w-full">
                        <x-label for="email" :value="__('Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="email" wire:model="form.email" />
                        <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                    </div>

                    <div class="w-full">
                        <x-label for="address" :value="__('Address')" />
                        <x-input id="address" class="block mt-1 w-full" type="text" wire:model="form.address" />
                        <x-input-error :messages="$errors->get('form.address')" class="mt-2" />
                    </div>

                    <div class="w-full">
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text" wire:model="form.city" />
                        <x-input-error :messages="$errors->get('form.city')" class="mt-2" />
                    </div>

                    <div class="w-full">
                        <x-label for="tax_number" :value="__('Tax Number')" />
                        <x-input id="tax_number" class="block mt-1 w-full" type="text" wire:model="form.tax_number" />
                        <x-input-error :messages="$errors->get('form.tax_number')" for="" class="mt-2" />
                    </div>
                </div>

                <div class="w-full">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
