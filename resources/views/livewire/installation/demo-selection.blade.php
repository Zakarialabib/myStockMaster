<div class="space-y-6">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-orange-900">Demo Data Selection</h2>
        <p class="mt-2 text-orange-600">Choose a business line to populate your system with relevant demo data</p>
    </div>

    <div class="space-y-4">
        <!-- Install Demo Data Toggle -->
        <div class="flex items-center justify-between p-4 bg-orange-50 rounded-xl border border-orange-100">
            <div>
                <h3 class="text-lg font-medium text-orange-900">Install Demo Data</h3>
                <p class="text-sm text-orange-600">Populate your system with sample products, categories, and data</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.live="install_demo_data" class="sr-only peer">
                <div class="w-11 h-6 bg-orange-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-orange-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
            </label>
        </div>

        @if($install_demo_data)
            <!-- Business Line Selection -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-orange-900">Select Business Line</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @php
                        $businessLines = [
                            'electronics' => ['name' => 'Electronics', 'icon' => '📱', 'description' => 'Phones, laptops, gadgets'],
                            'automotive' => ['name' => 'Automotive', 'icon' => '🚗', 'description' => 'Parts, accessories, tools'],
                            'fashion' => ['name' => 'Fashion', 'icon' => '👕', 'description' => 'Clothing, shoes, accessories'],
                            'sports' => ['name' => 'Sports & Fitness', 'icon' => '⚽', 'description' => 'Equipment, apparel, accessories'],
                            'furniture' => ['name' => 'Furniture', 'icon' => '🪑', 'description' => 'Home, office furniture'],
                            'books' => ['name' => 'Books & Media', 'icon' => '📚', 'description' => 'Books, magazines, media'],
                            'jewelry' => ['name' => 'Jewelry', 'icon' => '💎', 'description' => 'Rings, necklaces, watches'],
                            'pharmacy' => ['name' => 'Pharmacy', 'icon' => '💊', 'description' => 'Medicines, health products'],
                            'grocery' => ['name' => 'Grocery', 'icon' => '🛒', 'description' => 'Food items, household goods'],
                            'restaurant' => ['name' => 'Restaurant', 'icon' => '🍽️', 'description' => 'Food, beverages, supplies']
                        ];
                    @endphp

                    @foreach($businessLines as $key => $line)
                        <div class="relative">
                            <input type="radio" 
                                   id="business_{{ $key }}" 
                                   name="business_line" 
                                   value="{{ $key }}" 
                                   wire:model.live="selected_business_line"
                                   class="sr-only peer">
                            <label for="business_{{ $key }}" 
                                   class="flex flex-col items-center p-4 bg-white border-2 border-orange-100 rounded-xl cursor-pointer hover:bg-orange-50 peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all">
                                <div class="text-3xl mb-2">{{ $line['icon'] }}</div>
                                <div class="text-sm font-medium text-orange-900 text-center">{{ $line['name'] }}</div>
                                <div class="text-xs text-orange-500 text-center mt-1">{{ $line['description'] }}</div>
                            </label>
                        </div>
                    @endforeach
                </div>

                @error('selected_business_line')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Selected Business Line Preview -->
            @if($selected_business_line)
                <div class="p-4 bg-orange-50 border border-orange-200 rounded-xl">
                    <h4 class="font-medium text-orange-900">Selected: {{ $businessLines[$selected_business_line]['name'] }}</h4>
                    <p class="text-sm text-orange-700 mt-1">
                        This will install sample products, categories, and data relevant to the {{ strtolower($businessLines[$selected_business_line]['name']) }} industry.
                    </p>
                </div>
            @endif
        @else
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <p class="text-amber-800">
                    <strong>Note:</strong> You can always add demo data later from the admin panel if you choose to skip this step.
                </p>
            </div>
        @endif
    </div>
</div>
