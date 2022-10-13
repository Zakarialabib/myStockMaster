<div>
    <x-modal wire:model="createUser">
        <x-slot name="title">
            {{ __('Create User') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" type="text" wire:model.defer="user.name"
                            required />
                        <x-input-error :messages="$errors->get('user.name')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text"
                            wire:model.defer="user.phone" />
                        <x-input-error :messages="$errors->get('user.phone')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <label for="role">{{__('Role')}} <span class="text-red-500">*</span></label>
                        <select wire:model.defer="user.role"
                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                            name="role" id="role" required>
                            <option value="" selected disabled>{{ __('Select Role') }}</option>
                            @foreach (\Spatie\Permission\Models\Role::where('name', '!=', 'Super Admin')->get() as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="password" :value="__('Password')" />
                        <x-input id="password" class="block mt-1 w-full" type="password"
                            wire:model.defer="user.password" />
                        <x-input-error :messages="$errors->get('user.password')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
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
                            <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                                <x-label for="email" :value="__('Email')" />
                                <x-input id="email" class="block mt-1 w-full" type="email"
                                    wire:model.defer="user.email" />
                                <x-input-error :messages="$errors->get('user.email')" class="mt-2" />
                            </div>

                            <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                                <x-label for="address" :value="__('Address')" />
                                <x-input id="address" class="block mt-1 w-full" type="text"
                                    wire:model.defer="user.address" />
                                <x-input-error :messages="$errors->get('user.address')" class="mt-2" />
                            </div>

                            <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                                <x-label for="city" :value="__('City')" />
                                <x-input id="city" class="block mt-1 w-full" type="text"
                                    wire:model.defer="user.city" />
                                <x-input-error :messages="$errors->get('user.city')" class="mt-2" />
                            </div>

                            <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                                <x-label for="tax_number" :value="__('Tax Number')" />
                                <x-input id="tax_number" class="block mt-1 w-full" type="text"
                                    wire:model.defer="user.tax_number" />
                                <x-input-error :messages="$errors->get('user.tax_number')" for="" class="mt-2" />
                            </div>
                        </x-slot>
                    </x-accordion>

                    <div class="flex items-center justify-end mt-4">
                        <x-button primary wire:click="update" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-button>
                        <x-button sencondary wire:click="$set('editModal', false)" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
