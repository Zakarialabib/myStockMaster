<div>
    <div class="flex flex-col lg:flex-row gap-4 h-full" x-data
        x-on:keydown.window.prevent.ctrl.f="document.getElementById('product-search-input').focus()"
        x-on:keydown.window.prevent.ctrl.enter="$wire.proceed()"
        x-on:keydown.window.prevent.escape="$wire.checkoutModal = false">
        <!-- Left Column: Product Selection (40%) -->
        <div class="w-full lg:w-5/12 flex flex-col gap-4">
            <!-- Product Search -->
            <div
                class="bg-white rounded-lg shadow-sm border overflow-hidden flex-1 relative {{ !$warehouse_id ? 'opacity-75' : '' }}">
                <div class="bg-gray-50 px-4 py-3 border-b">
                    <h3 class="text-md font-semibold text-gray-900">
                        <i class="fas fa-search mr-2 text-blue-500"></i>
                        {{ __('Select Products') }}
                    </h3>
                </div>
                <div class="p-3">
                    @if (!$warehouse_id)
                        <div
                            class="absolute inset-0 z-50 bg-white/50 backdrop-blur-sm flex items-center justify-center">
                            <div class="bg-white p-4 rounded-lg shadow-lg text-center border border-gray-200">
                                <i class="fas fa-warehouse text-4xl text-blue-400 mb-3"></i>
                                <h4 class="text-lg font-bold text-gray-800">{{ __('Warehouse Required') }}</h4>
                                <p class="text-gray-500 text-sm mt-1">
                                    {{ __('Please select a warehouse first to load available products.') }}</p>
                            </div>
                        </div>
                    @endif
                    <livewire:products.search-product :warehouseId="$warehouse_id" />
                </div>
            </div>
        </div>

        <!-- Center Column: Shopping Cart (35%) -->
        <div class="w-full lg:w-4/12 flex flex-col gap-4">
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden flex-1 flex flex-col">
                <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                    <h3 class="text-md font-semibold text-gray-900">
                        <i class="fas fa-shopping-basket mr-2 text-green-500"></i>
                        {{ __('Shopping Cart') }}
                    </h3>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $this->cartCount }} {{ __('Items') }}
                    </span>
                </div>
                <div class="p-3 flex-1 overflow-y-auto">
                    <livewire:utils.product-cart :cartInstance="'pos'" :warehouseId="$warehouse_id" />
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Customer (25%) -->
        <div class="w-full lg:w-3/12 flex flex-col gap-4">
            <!-- Warehouse Selection -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                    <h3 class="text-md font-semibold text-gray-900">
                        <i class="fas fa-warehouse mr-2 text-blue-500"></i>
                        {{ __('Warehouse') }}
                    </h3>
                    @if (!$warehouse_id)
                        <span
                            class="px-2 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full animate-pulse">
                            {{ __('Required') }}
                        </span>
                    @endif
                </div>
                <div class="p-4">
                    <div class="relative">
                        <select required id="warehouse_id" name="warehouse_id" wire:model.live="warehouse_id"
                            class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white {{ !$warehouse_id ? 'ring-2 ring-red-300' : '' }}">
                            <option value="">{{ __('Select warehouse to start') }}</option>
                            @foreach ($this->warehouses as $index => $warehouse)
                                <option value="{{ $index }}">{{ $warehouse }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Selection -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                    <h3 class="text-md font-semibold text-gray-900">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        {{ __('Customer') }}
                    </h3>
                </div>
                <div class="p-4">
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <select required id="customer_id" name="customer_id" wire:model.live="customer_id"
                                class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">{{ __('Select Customer') }}</option>
                                @foreach ($this->customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-circle text-gray-400"></i>
                            </div>
                        </div>
                        <button type="button" wire:click="dispatchTo('customers.create', 'createModal')"
                            class="px-3 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg border border-blue-200 transition-colors"
                            title="{{ __('Add Customer') }}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Panel -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b">
                    <h3 class="text-md font-semibold text-gray-900">
                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                        {{ __('Actions') }}
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <button type="button" wire:click="proceed" wire:loading.attr="disabled"
                        class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-green-500 {{ $total_amount == 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-cash-register mr-2"></i>
                        {{ __('Checkout') }} <span
                            class="ml-2 text-sm bg-green-700 px-2 py-0.5 rounded-md">Ctrl+Enter</span>
                    </button>

                    <button type="button" wire:click="clearCart" wire:loading.attr="disabled"
                        class="w-full flex items-center justify-center px-4 py-2.5 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash-alt mr-2"></i>
                        {{ __('Reset Cart') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-modal wire:model="checkoutModal" max-width="4xl">
        <div class="px-6 py-4 border-b">
            <div class="flex items-center text-lg font-bold">
                <i class="fas fa-cash-register mr-3 text-green-500"></i>
                {{ __('Checkout') }}
            </div>
        </div>

        <div class="p-6">
            <form id="checkout-form" wire:submit="store" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">
                            <i class="fas fa-credit-card mr-2 text-blue-500"></i>
                            {{ __('Payment Information') }}
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <x-label for="total_amount" :value="__('Total Amount')" required class="mb-2" />
                                <div class="relative">
                                    <input id="total_amount" type="text" wire:model.live="total_amount"
                                        class="block w-full pl-10 pr-4 py-3 bg-gray-100 border border-gray-300 rounded-lg"
                                        name="total_amount" readonly required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-dollar-sign text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <x-label for="paid_amount" :value="__('Paid Amount')" required />
                                    <div class="text-sm font-medium text-gray-500">
                                        {{ __('Change') }}:
                                        <span
                                            class="text-xl font-bold {{ (float) $paid_amount - (float) $total_amount < 0 ? 'text-red-500' : 'text-green-600' }}">
                                            {{ format_currency((float) $paid_amount - (float) $total_amount) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="relative mb-3">
                                    <input id="paid_amount" type="number" step="0.01"
                                        wire:model.live="paid_amount"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg font-semibold"
                                        name="paid_amount" required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-money-bill-wave text-gray-400"></i>
                                    </div>
                                </div>

                                <!-- Quick Cash Buttons -->
                                <div class="grid grid-cols-4 gap-2 mb-4">
                                    <button type="button" wire:click="$set('paid_amount', {{ $total_amount }})"
                                        class="py-2 px-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded border border-indigo-200 text-sm transition-colors">
                                        {{ __('Exact') }}
                                    </button>
                                    <button type="button"
                                        wire:click="$set('paid_amount', {{ (float) $total_amount + 10 }})"
                                        class="py-2 px-1 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded border border-gray-200 text-sm transition-colors">
                                        +10
                                    </button>
                                    <button type="button"
                                        wire:click="$set('paid_amount', {{ (float) $total_amount + 50 }})"
                                        class="py-2 px-1 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded border border-gray-200 text-sm transition-colors">
                                        +50
                                    </button>
                                    <button type="button"
                                        wire:click="$set('paid_amount', {{ (float) $total_amount + 100 }})"
                                        class="py-2 px-1 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded border border-gray-200 text-sm transition-colors">
                                        +100
                                    </button>
                                </div>
                            </div>

                            <div>
                                <x-label for="payment_method" :value="__('Payment Method')" required class="mb-2" />
                                <div class="relative">
                                    <select wire:model.live="payment_method" id="payment_method" required
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                        <option value="Cash">{{ __('Cash') }}</option>
                                        <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                                        <option value="Cheque">{{ __('Cheque') }}</option>
                                        <option value="Other">{{ __('Other') }}</option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-credit-card text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <x-label for="note" :value="__('Note')" class="mb-2" />
                                <textarea name="note" id="note" rows="4" wire:model.live="note"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="{{ __('Add any notes...') }}"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h4 class="text-md font-semibold text-gray-900">
                                <i class="fas fa-receipt mr-2 text-purple-500"></i>
                                {{ __('Order Summary') }}
                            </h4>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">{{ __('Total Products') }}</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $this->cartCount }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">{{ __('Order Tax') }}
                                        ({{ $global_tax }}%)</span>
                                    <span
                                        class="font-medium text-blue-600">+{{ format_currency($this->cartTax) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">{{ __('Discount') }}
                                        ({{ $global_discount }}%)</span>
                                    <span
                                        class="font-medium text-red-600">-{{ format_currency($this->cartDiscount) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">{{ __('Shipping') }}</span>
                                    <span
                                        class="font-medium text-blue-600">+{{ format_currency($shipping_amount) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-4 bg-gray-50 px-4 rounded-lg">
                                    <span class="text-lg font-semibold text-gray-900">{{ __('Grand Total') }}</span>
                                    <span
                                        class="text-lg font-bold text-green-600">{{ format_currency($total_with_shipping) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <x-button secondary type="button" x-on:click="show = false">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button success type="submit" wire:loading.attr="disabled" class="min-w-32">
                        <i class="fas fa-check mr-2"></i>
                        {{ __('Complete Sale') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>

    @push('scripts')
        <script>
            document.addEventListener('open-print-window', (event) => {
                window.open(event.detail.url, '_blank');
            });
        </script>
    @endpush
</div>
