<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <!-- Sidebar Controls -->
    <div class="md:col-span-1 space-y-4 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Template Customizer') }}</h3>

        <div>
            <x-label for="primary_color" :value="__('Primary Color')" />
            <div class="mt-1 flex items-center gap-3">
                <input type="color" id="primary_color" wire:model.live="primary_color" class="h-10 w-10 rounded border border-gray-300 cursor-pointer" />
                <x-input type="text" wire:model.live="primary_color" class="flex-1" />
            </div>
        </div>

        <div>
            <x-label for="secondary_color" :value="__('Secondary Color')" />
            <div class="mt-1 flex items-center gap-3">
                <input type="color" id="secondary_color" wire:model.live="secondary_color" class="h-10 w-10 rounded border border-gray-300 cursor-pointer" />
                <x-input type="text" wire:model.live="secondary_color" class="flex-1" />
            </div>
        </div>

        <div>
            <x-label for="font_family" :value="__('Font Family')" />
            <x-select id="font_family" wire:model.live="font_family" class="mt-1">
                <option value="Inter, sans-serif">Inter</option>
                <option value="Arial, sans-serif">Arial</option>
                <option value="Georgia, serif">Georgia</option>
                <option value="'Courier New', monospace">Courier New</option>
                <option value="Tahoma, sans-serif">Tahoma</option>
            </x-select>
        </div>

        <div>
            <x-label for="pattern_style" :value="__('Background Pattern')" />
            <x-select id="pattern_style" wire:model.live="pattern_style" class="mt-1">
                <option value="none">None</option>
                <option value="dots">Dots</option>
                <option value="stripes">Stripes</option>
                <option value="grid">Grid</option>
            </x-select>
        </div>
    </div>

    <!-- Live Preview Area -->
    <div class="md:col-span-3 rounded-lg shadow-inner flex items-center justify-center p-8 border border-gray-200 min-h-[600px] overflow-hidden relative" 
         style="
            background-color: {{ $secondary_color }};
            font-family: {{ $font_family }};
         ">
         
        @if($pattern_style === 'dots')
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient({{ $primary_color }} 2px, transparent 2px); background-size: 20px 20px;"></div>
        @elseif($pattern_style === 'stripes')
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, {{ $primary_color }} 10px, {{ $primary_color }} 20px);"></div>
        @elseif($pattern_style === 'grid')
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: linear-gradient({{ $primary_color }} 1px, transparent 1px), linear-gradient(90deg, {{ $primary_color }} 1px, transparent 1px); background-size: 20px 20px;"></div>
        @endif
        
        <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-2xl border-t-8 relative z-10" style="border-top-color: {{ $primary_color }};">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-4xl font-black tracking-tight" style="color: {{ $primary_color }};">INVOICE</h2>
                    <p class="text-gray-500 mt-1 font-medium">#INV-2023-001</p>
                </div>
                <div class="text-right">
                    <div class="font-bold text-xl text-gray-800">Your Company</div>
                    <div class="text-gray-500 text-sm mt-1 leading-relaxed">123 Business Street<br>City, State 12345</div>
                </div>
            </div>

            <div class="mb-10">
                <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2" style="color: {{ $primary_color }};">Bill To:</div>
                <div class="font-bold text-gray-800 text-lg">Client Name</div>
                <div class="text-gray-500 text-sm">client@example.com</div>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr style="border-bottom: 2px solid {{ $primary_color }};">
                        <th class="py-3 text-gray-700 font-bold uppercase text-xs tracking-wider">Description</th>
                        <th class="py-3 text-gray-700 font-bold uppercase text-xs tracking-wider text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-4 text-gray-600 font-medium">Web Design Services</td>
                        <td class="py-4 text-gray-600 text-right font-medium">$1,500.00</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-4 text-gray-600 font-medium">Hosting (1 Year)</td>
                        <td class="py-4 text-gray-600 text-right font-medium">$150.00</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="py-6 text-right font-bold text-gray-700 uppercase text-sm tracking-wider">Total Due</td>
                        <td class="py-6 text-right font-black text-2xl" style="color: {{ $primary_color }};">$1,650.00</td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-16 text-center text-sm font-medium px-4 py-3 rounded-lg" style="background-color: {{ $secondary_color }}; color: {{ $primary_color }}; border: 1px solid {{ $primary_color }}33;">
                Thank you for your business!
            </div>
        </div>
    </div>
</div>