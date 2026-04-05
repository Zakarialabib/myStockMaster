<div x-data="{ isCartOpen: false }" @keydown.window.ctrl.s.prevent="$wire.store()">
    @section('title', __('Create Transfer'))

    <x-theme.breadcrumb :title="__('Create Transfer')" :parent="route('transfers.index')" :parentName="__('Transfers List')" :childrenName="__('Create Transfer')">
        <div class="flex items-center gap-2">
            <x-button success type="button" wire:click.throttle="store" wire:loading.attr="disabled" :disabled="count($products) == 0">
                <i class="fas fa-check mr-2"></i>
                {{ __('Complete Transfer') }} (Ctrl+S)
            </x-button>
        </div>
    </x-theme.breadcrumb>

    <!-- Split-Pane Layout -->
    <div class="mt-4 grid grid-cols-1 lg:grid-cols-12 gap-6 h-[calc(100vh-12rem)]">
        
        <!-- Left Pane: Context & Products (60%) -->
        <div class="lg:col-span-7 flex flex-col gap-4 overflow-y-auto">
            <!-- Context Metadata -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="from_warehouse_id" :value="__('From Warehouse')" required />
                        <div class="relative mt-1">
                            <select required id="from_warehouse_id" name="from_warehouse_id" wire:model.live="form.from_warehouse_id"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                <option value="">{{ __('Select From Warehouse') }}</option>
                                @foreach ($this->warehouses as $index => $warehouse)
                                    <option value="{{ $index }}">{{ $warehouse }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('form.from_warehouse_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="to_warehouse_id" :value="__('To Warehouse')" required />
                        <div class="relative mt-1">
                            <select required id="to_warehouse_id" name="to_warehouse_id" wire:model.live="form.to_warehouse_id"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                <option value="">{{ __('Select To Warehouse') }}</option>
                                @foreach ($this->warehouses as $index => $warehouse)
                                    <option value="{{ $index }}">{{ $warehouse }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('form.to_warehouse_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="date" :value="__('Date')" required />
                        <x-input type="date" name="date" required wire:model="form.date" class="w-full mt-1" />
                        <x-input-error :messages="$errors->get('form.date')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-label for="document" :value="__('Document')" />
                        <x-input type="text" name="document" wire:model="form.document" class="w-full mt-1" />
                        <x-input-error :messages="$errors->get('form.document')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Product Search -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex-1">
                @if (!$form->from_warehouse_id)
                    <div class="h-full flex items-center justify-center text-gray-500 dark:text-gray-400 p-6 text-center">
                        <div>
                            <i class="fas fa-warehouse text-4xl mb-4"></i>
                            <p>{{ __('Please select a source warehouse first to load available products.') }}</p>
                        </div>
                    </div>
                @else
                    <livewire:products.search-product :warehouseId="$form->from_warehouse_id" />
                @endif
            </div>
        </div>

        <!-- Right Pane: Cart & Totals (40%) -->
        <div class="lg:col-span-5 flex flex-col gap-4 overflow-y-auto">
            <!-- Cart Items -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex-1 flex flex-col min-h-[300px]">
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-shopping-cart mr-2 text-indigo-500"></i>
                        {{ __('Transfer Items') }}
                    </h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                        {{ count($products) }} {{ __('Items') }}
                    </span>
                </div>
                <div class="p-0 flex-1 overflow-y-auto">
                    @if(count($products) > 0)
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($products as $key => $product)
                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product['name'] }}</h4>
                                        <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            <span>{{ __('Price') }}: {{ format_currency($product['price'] ?? 0) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="w-24">
                                            <x-input type="number" min="1" step="1" 
                                                wire:change="updateQuantity({{ $key }}, $event.target.value)" 
                                                value="{{ $product['quantities'] ?? 1 }}" 
                                                class="w-full text-center" />
                                        </div>
                                        <button type="button" wire:click="removeProduct({{ $key }})" class="text-red-500 hover:text-red-700 focus:outline-none">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-full flex items-center justify-center text-gray-500 dark:text-gray-400 p-6 text-center">
                            <div>
                                <i class="fas fa-box-open text-4xl mb-4"></i>
                                <p>{{ __('No products added yet. Search and select products to transfer.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div>
                        <x-label for="shipping_amount" :value="__('Shipping Amount')" />
                        <x-input id="shipping_amount" type="number" step="0.01" wire:model.live.debounce.300ms="form.shipping_amount" name="shipping_amount" class="w-full mt-1" />
                        <x-input-error :messages="$errors->get('form.shipping_amount')" class="mt-2" />
                    </div>
                </div>

                <div class="space-y-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Total Quantity') }}</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $form->total_qty }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Total Cost') }}</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ format_currency($form->total_cost) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Total Value') }}</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ format_currency($form->total_amount) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Shipping') }}</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">+{{ format_currency($form->shipping_amount ?? 0) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 bg-gray-50 dark:bg-gray-900 px-3 rounded-lg mt-2">
                        <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ __('Grand Total') }}</span>
                        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ format_currency($form->total_amount + ($form->shipping_amount ?? 0)) }}
                        </span>
                    </div>
                </div>

                <div class="mt-4">
                    <x-label for="note" :value="__('Transfer Note')" />
                    <textarea name="note" id="note" rows="2" wire:model.live.debounce.300ms="form.note"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md mt-1"></textarea>
                    <x-input-error :messages="$errors->get('form.note')" class="mt-2" />
                </div>
            </div>
        </div>
    </div>
</div>
