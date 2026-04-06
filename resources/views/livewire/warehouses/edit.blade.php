<div>
    <x-modal wire:model="editModal" name="editModal">
        <x-slot name="title">
            {{ __('Edit Warehouse') }}
        </x-slot>
        <x-slot name="content">
            <form wire:submit="update">
                <div class="flex flex-wrap mb-3">
                    <div class="lg:w-1/2 sm:full px-3 mb-6">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" wire:model="form.name" required />
                        <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
                    </div>
                    <div class="lg:w-1/2 sm:full px-3 mb-6">
                        <x-label for="phone" :value="__('Phone')" />
                        <x-input id="phone" class="block mt-1 w-full" type="text" wire:model="form.phone" />
                        <x-input-error :messages="$errors->get('form.phone')" class="mt-2" />
                    </div>
                    <div class="lg:w-1/2 sm:full px-3 mb-6">
                        <x-label for="email" :value="__('Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="email" wire:model="form.email" />
                        <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                    </div>
                    <div class="lg:w-1/2 sm:full px-3 mb-6">
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text" wire:model="form.city" />
                        <x-input-error :messages="$errors->get('form.city')" class="mt-2" />
                    </div>
                    <div class="lg:w-1/2 sm:full px-3 mb-6">
                        <x-label for="country" :value="__('Country')" />
                        <x-input id="country" class="block mt-1 w-full" type="text" wire:model="form.country" />
                        <x-input-error :messages="$errors->get('form.country')" class="mt-2" />
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
