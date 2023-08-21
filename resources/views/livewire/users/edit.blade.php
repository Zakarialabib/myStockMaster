<div>
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit User') }}
        </x-slot>

        <x-slot name="content">
            <x-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" type="text" wire:model.defer="user.name"
                            required />
                        <x-input-error :messages="$errors->get('user.name')" class="mt-2" />
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text"
                            wire:model.defer="user.phone" />
                        <x-input-error :messages="$errors->get('user.phone')" class="mt-2" />
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3">
                        <label for="role">{{ __('Role') }} <span class="text-red-500">*</span></label>
                        <select wire:model.defer="user.role"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="role" id="role" required>
                            <option value="" selected disabled>{{ __('Select Role') }}</option>

                        </select>
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="password" :value="__('Password')" />
                        <x-input id="password" class="block mt-1 w-full" type="password"
                            wire:model.defer="user.password" />
                        <x-input-error :messages="$errors->get('user.password')" class="mt-2" />
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            wire:model.defer="user.password_confirmation" />
                        <x-input-error :messages="$errors->get('user.password_confirmation')" class="mt-2" />
                    </div>

                    <x-accordion>
                        <x-slot name="title">
                            {{ __('Details') }}
                        </x-slot>

                        <x-slot name="content">
                            <div class="md:w-1/2 sm:w-full px-3">
                                <x-label for="email" :value="__('Email')" />
                                <x-input id="email" class="block mt-1 w-full" type="email"
                                    wire:model.defer="user.email" />
                                <x-input-error :messages="$errors->get('user.email')" class="mt-2" />
                            </div>

                        </x-slot>
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
