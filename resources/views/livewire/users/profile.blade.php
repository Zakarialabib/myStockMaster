<div>
    <div class="flex flex-row">
        <form wire:submit.prevent="update">
    
            <div class="w-full px-2">
                <x-label for="image" :value="__('Profile Image')" required />
                <img style="width: 100px;height: 100px;"
                    class="d-block mx-auto img-thumbnail img-fluid rounded-circle mb-2" alt="Profile Image">
                <input id="image" type="file" name="image" data-max-file-size="500KB">
            </div>

            <div class="lg:w-1/2 sm:w-full px-3">
                <x-card>
                    <div class="w-full">
                        <div class="flex flex-wrap">
                            <div class="flex flex-wrap">
                                <div class="w-1/2 sm:w-full px-2">
                                    <div class="form-group">
                                        <x-label for="name" :value="__('Name')" required />
                                        <input
                                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                            type="text" name="name" wire:model="name" required
                                            value="{{ $user->name }}">
                                    </div>
                                </div>
                                <div class="w-1/2 sm:w-full px-2">
                                    <div class="form-group">
                                        <x-label for="email" :value="__('Email')" required />
                                        <input
                                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                            type="email" name="email" wire:model="email" required
                                            value="{{ $user->email }}">
                                    </div>
                                </div>
                            </div>

                            <div class="w-full px-2">
                                <x-label for="Role" :value="__('Role')" required />
                                <select
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                    name="role" id="role" wire:model="role" required>
                                    @foreach (\Spatie\Permission\Models\Role::where('name', '!=', 'Super Admin')->get() as $role)
                                        <option {{ $user->hasRole($role->name) ? 'selected' : '' }}
                                            value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full px-2">
                                <x-label for="is_active" :value="__('Status')" required />
                                <select
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                    name="is_active" id="is_active" wire:model="is_active" required>
                                    <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>
                                        {{ __('Active') }}
                                    </option>
                                    <option value="2" {{ $user->is_active == 2 ? 'selected' : '' }}>
                                        {{ __('Deactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <x-button type="submit" primary class="mt-4">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </x-card>
            </div>
        </form>

        <div class="lg:w-1/2 sm:w-full px-3">
            <x-card>
                <div class="p-4">
                    <form wire:submit.prevent="updatePassword">
                        <div class="mb-4">
                            <label for="current_password">{{ __('Current Password') }} <span
                                    class="text-danger">*</span></label>
                            <input type="password"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                name="current_password" required>
                            @error('current_password')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="password">{{ __('New Password') }} <span class="text-danger">*</span></label>
                            <input
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                type="password" name="password" required>
                            @error('password')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation">{{ __('Confirm Password') }} <span
                                    class="text-danger">*</span></label>
                            <input
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                type="password" name="password_confirmation" required>
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
            </x-card>
        </div>
    </div>
</div>
