<div class="grid grid-cols-1 md:grid-cols-12 gap-6">
    <!-- Controls Sidebar -->
    <div class="md:col-span-4 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Brand Colors') }}</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Primary Color') }}</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" wire:model.live="mailStyles.primary_color" class="h-8 w-8 rounded cursor-pointer border-0 p-0">
                        <input type="text" wire:model.live="mailStyles.primary_color" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Text Color') }}</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" wire:model.live="mailStyles.text_color" class="h-8 w-8 rounded cursor-pointer border-0 p-0">
                        <input type="text" wire:model.live="mailStyles.text_color" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Background Color') }}</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" wire:model.live="mailStyles.background_color" class="h-8 w-8 rounded cursor-pointer border-0 p-0">
                        <input type="text" wire:model.live="mailStyles.background_color" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Preview -->
    <div class="md:col-span-8">
        <div class="bg-gray-100 rounded-lg p-8 h-full flex items-center justify-center border border-gray-200">
            <!-- Simulated Email Container -->
            <div class="w-full max-w-lg bg-white rounded shadow-md overflow-hidden" style="background-color: {{ $mailStyles['background_color'] }}">
                <!-- Header -->
                <div class="px-6 py-4 text-center" style="background-color: {{ $mailStyles['primary_color'] }}">
                    <h2 class="text-2xl font-bold text-white">{{ settings()?->company_name ?? 'Your Company' }}</h2>
                </div>
                <!-- Body -->
                <div class="px-6 py-8 bg-white">
                    <h3 class="text-xl font-bold mb-4" style="color: {{ $mailStyles['text_color'] }}">Your Invoice is Ready</h3>
                    <p class="mb-6" style="color: {{ $mailStyles['text_color'] }}">Hello John Doe,</p>
                    <p class="mb-6" style="color: {{ $mailStyles['text_color'] }}">Thank you for your recent purchase. Your invoice #INV-001 is attached to this email.</p>
                    <div class="text-center">
                        <a href="#" class="inline-block px-6 py-3 font-medium text-white {{ $mailStyles['button_radius'] }}" style="background-color: {{ $mailStyles['primary_color'] }}; text-decoration: none;">View Invoice</a>
                    </div>
                </div>
                <!-- Footer -->
                <div class="px-6 py-4 text-center border-t border-gray-200 bg-gray-50">
                    <p class="text-xs text-gray-500">&copy; {{ date('Y') }} {{ settings()?->company_name }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</div>
