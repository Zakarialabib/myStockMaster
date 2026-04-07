<div>
    <x-card x-data="{
        init() {
            this.$watch('$wire.primary_color', value => {
                if (typeof window.updateThemePalette === 'function') {
                    window.updateThemePalette(value);
                }
            });
        }
    }">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('App Customizer') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-label for="primary_color" :value="__('Primary Color')" />
                    <div class="mt-1 flex items-center gap-3">
                        <input type="color" id="primary_color" wire:model.live="primary_color" class="h-10 w-10 rounded border border-gray-300 cursor-pointer" />
                        <x-input type="text" wire:model.live="primary_color" class="flex-1" />
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Set the primary color used for buttons, highlights, and active states across the application.') }}</p>
                </div>

                <div>
                    <x-label for="font_family" :value="__('Font Family')" />
                    <x-select id="font_family" wire:model.live="font_family" class="mt-1 w-full">
                        <option value="'Inter', sans-serif">Inter</option>
                        <option value="'Arial', sans-serif">Arial</option>
                        <option value="'Georgia', serif">Georgia</option>
                        <option value="'Courier New', monospace">Courier New</option>
                        <option value="'Tahoma', sans-serif">Tahoma</option>
                    </x-select>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Choose the default font family for the application interface.') }}</p>
                </div>
            </div>

            <div class="mt-8">
                <h4 class="text-md font-medium text-gray-700 mb-3">{{ __('Preview') }}</h4>
                <div class="p-6 border rounded-lg" style="font-family: {{ $font_family }};">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold bg-primary-500">
                            A
                        </div>
                        <div>
                            <h5 class="font-bold text-lg text-primary-500">Heading Example</h5>
                            <p class="text-gray-600 text-sm">This is how your selected font family looks.</p>
                        </div>
                    </div>
                    <button type="button" class="px-4 py-2 text-white rounded-md text-sm font-medium transition-colors bg-primary-500 hover:bg-primary-600">
                        Primary Button
                    </button>
                </div>
            </div>
        </div>
    </x-card>
</div>
