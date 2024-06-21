<div>
    <div class="flex flex-row">
        <div class="lg:w-1/2 sm:w-full px-3">
            <form wire:submit="update">
                <div class="flex flex-wrap">
                    <div class="w-1/2 sm:w-full px-2">
                        <x-label for="name" :value="__('Name')" required />
                        <input
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            type="text" name="name" wire:model.live="name" required>
                    </div>
                    <div class="w-1/2 sm:w-full px-2">
                        <x-label for="email" :value="__('Email')" required />
                        <input
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            type="email" name="email" wire:model.live="email" required>
                    </div>
                    <div class="w-1/2 sm:w-full px-2">
                        <x-label for="phone" :value="__('Phone')" required />
                        <input
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            type="text" name="phone" wire:model.live="phone" required>
                    </div>
                    <div class="w-full px-2">
                        <x-label for="Role" :value="__('Role')" required />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="role" id="role" wire:model.live="role" required>
                        </select>
                    </div>

                    <div class="w-full px-2">
                        <x-label for="is_active" :value="__('Status')" required />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="is_active" id="is_active" wire:model.live="is_active" required>
                            <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>
                                {{ __('Active') }}
                            </option>
                            <option value="2" {{ $user->is_active == 2 ? 'selected' : '' }}>
                                {{ __('Deactive') }}</option>
                        </select>
                    </div>
                    <div class="w-full px-2">
                        <x-button type="submit" primary class="mt-4">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </div>

        <div class="lg:w-1/2 sm:w-full px-3">
            <form wire:submit="updatePassword">
                <div class="mb-4">
                    <label for="current_password">{{ __('Current Password') }} <span
                            class="text-danger">*</span></label>
                    <x-input type="password" name="current_password" required />
                    @error('current_password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password">{{ __('New Password') }} <span class="text-danger">*</span></label>
                    <x-input type="password" name="password" required />
                    @error('password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password_confirmation">{{ __('Confirm Password') }} <span
                            class="text-danger">*</span></label>
                    <x-input type="password" name="password_confirmation" required />
                    @error('password_confirmation')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <x-button type="submit" primary>
                        {{ __('Update Password') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
