<div class="space-y-8">
    <div class="text-center">
        <div
            class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                </path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-orange-900 font-display">Create Admin Account</h3>
        <p class="mt-2 text-orange-600">Set up your administrator account to manage your stock management system.</p>
    </div>

    <div class="space-y-6">
        <div>
            <label for="admin_email" class="block text-sm font-semibold text-orange-700 mb-2">Admin Email *</label>
            <input type="email" wire:model="admin_email" id="admin_email"
                class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border"
                placeholder="{{ __('e.g., admin@company.com') }}">
            @error('admin_email')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="admin_password" class="block text-sm font-semibold text-orange-700 mb-2">Password *</label>
            <input type="password" wire:model="admin_password" id="admin_password"
                class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border"
                placeholder="{{ __('Create a strong password') }}">
            @error('admin_password')
                <span class="text-red-600 text-sm mt-1 block">{{ __($message) }}</span>
            @enderror
            <p class="mt-2 text-sm text-orange-500">
                {{ __('Use at least 8 characters with a mix of letters, numbers, and symbols.') }}</p>
        </div>

        <div>
            <label for="admin_password_confirmation" class="block text-sm font-semibold text-orange-700 mb-2">Confirm
                Password *</label>
            <input type="password" wire:model="admin_password_confirmation" id="admin_password_confirmation"
                class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border"
                placeholder="{{ __('Repeat your password') }}">
            @error('admin_password_confirmation')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-amber-700">
                        <strong>Important:</strong> This account will have full access to manage your stock management
                        system, including inventory, orders, and business data.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
