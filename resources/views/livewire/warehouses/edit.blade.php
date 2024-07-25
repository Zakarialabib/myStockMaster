<div>
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Warehouse') }}
        </x-slot>
        <x-slot name="content">
            <form wire:submit="update">
                <div class="flex flex-wrap mb-3">
                    <div class="lg:w-1/2 sm:full px-3 mb-6">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" wire:model="name" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="lg:w-1/2 sm:full px-3 mb-6">
                        <x-label for="phone" :value="__('Phone')" />
                        <x-input id="phone" class="block mt-1 w-full" type="text" wire:model="phone" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    <div class="lg:w-1/2 sm:full px-3 mb-6">
                        <x-label for="email" :value="__('Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="email" wire:model="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="lg:w-1/2 sm:full px-3 mb-6">
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text" wire:model="city" />
                        <x-input-error :messages="$errors->get('city')" class="mt-2" />
                    </div>
                    <div class="lg:w-1/2 sm:full px-3 mb-6">
                        <x-label for="country" :value="__('Country')" />
                        <x-input id="country" class="block mt-1 w-full" type="text" wire:model="country" />
                        <x-input-error :messages="$errors->get('country')" class="mt-2" />
                    </div>
                </div>

                <div class="w-full px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
