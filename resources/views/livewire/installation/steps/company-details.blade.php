<div class="space-y-8">
    <div class="text-center">
        <div
            class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-orange-900 font-display">{{(__('Company Information'))}}</h3>
        <p class="mt-2 text-orange-600">{{(__("Let's get your stock management system set up with the essential
            details."))}}</p>
    </div>

    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="company_name" class="block text-sm font-semibold text-orange-700 mb-2">Company Name *</label>
            <input type="text" wire:model="company_name" id="company_name"
                class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border"
                placeholder="{{(__('e.g., ABC Trading Company'))}}">
            @error('company_name')
                <span class="text-red-600 text-sm mt-1 block">{{ __($message) }}</span>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="company_email" class="block text-sm font-semibold text-orange-700 mb-2">Business Email *</label>
            <input type="email" wire:model="company_email" id="company_email"
                class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border"
                placeholder="{{(__('e.g., info@abctrading.com'))}}">
            @error('company_email')
                <span class="text-red-600 text-sm mt-1 block">{{ __($message) }}</span>
            @enderror
        </div>

        <div>
            <label for="company_phone" class="block text-sm font-semibold text-orange-700 mb-2">Phone Number *</label>
            <input type="tel" wire:model="company_phone" id="company_phone"
                class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border"
                placeholder="{{(__('e.g., +1 (555) 123-4567'))}}">
            @error('company_phone')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="company_tax" class="block text-sm font-semibold text-orange-700 mb-2">Tax ID (Optional)</label>
            <input type="text" wire:model="company_tax" id="company_tax"
                class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border"
                placeholder="{{(__('e.g., 12-3456789'))}}">
            @error('company_tax')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="company_address" class="block text-sm font-semibold text-orange-700 mb-2">Business Address
                *</label>
            <textarea wire:model="company_address" id="company_address" rows="3"
                class="block w-full rounded-xl border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition-colors duration-200 px-4 py-3 border"
                placeholder="{{(__('e.g., 123 Main Street, City, State 12345'))}}"></textarea>
            @error('company_address')
                <span class="text-red-600 text-sm mt-1 block">{{ __($message) }}</span>
            @enderror
        </div>
    </div>
</div>
