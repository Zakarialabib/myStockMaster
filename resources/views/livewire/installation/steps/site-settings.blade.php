<div class="space-y-8">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-orange-900 font-display">System Configuration</h3>
        <p class="mt-2 text-orange-600">Fine-tune your stock management system settings for optimal performance.</p>
    </div>

    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
        <div>
            <label for="currency" class="block text-sm font-semibold text-orange-700 mb-2">Currency *</label>
            <select wire:model="currency" id="currency" 
                    class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border">
                <option value="">Select currency...</option>
                <option value="USD">🇺🇸 US Dollar ($)</option>
                <option value="EUR">🇪🇺 Euro (€)</option>
                <option value="GBP">🇬🇧 British Pound (£)</option>
                <option value="JPY">🇯🇵 Japanese Yen (¥)</option>
                <option value="CAD">🇨🇦 Canadian Dollar (C$)</option>
                <option value="AUD">🇦🇺 Australian Dollar (A$)</option>
                <option value="MXN">🇲🇽 Mexican Peso ($)</option>
                <option value="BRL">🇧🇷 Brazilian Real (R$)</option>
            </select>
            @error('currency') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="timezone" class="block text-sm font-semibold text-orange-700 mb-2">Timezone *</label>
            <select wire:model="timezone" id="timezone" 
                    class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border">
                <option value="">Select timezone...</option>
                <option value="America/New_York">🇺🇸 Eastern Time</option>
                <option value="America/Chicago">🇺🇸 Central Time</option>
                <option value="America/Denver">🇺🇸 Mountain Time</option>
                <option value="America/Los_Angeles">🇺🇸 Pacific Time</option>
                <option value="Europe/London">🇬🇧 London</option>
                <option value="Europe/Paris">🇫🇷 Paris</option>
                <option value="Europe/Berlin">🇩🇪 Berlin</option>
                <option value="Asia/Tokyo">🇯🇵 Tokyo</option>
                <option value="Australia/Sydney">🇦🇺 Sydney</option>
                <option value="UTC">🌍 UTC</option>
            </select>
            @error('timezone') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="items_per_page" class="block text-sm font-semibold text-orange-700 mb-2">Items Per Page *</label>
            <input type="number" wire:model="items_per_page" id="items_per_page" min="5" max="100" 
                   class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border"
                   placeholder="20">
            <p class="mt-2 text-sm text-orange-500">Number of items to display per page</p>
            @error('items_per_page') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-orange-700">
                    <strong>Pro tip:</strong> You can always change these settings later from your admin dashboard under Settings > General.
                </p>
            </div>
        </div>
    </div>
</div>