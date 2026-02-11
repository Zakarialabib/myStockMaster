<div>
    @section('title', __('Point of Sale'))

    <x-page-container>
        {{-- <x-slot name="breadcrumbs">
            <x-breadcrumb :items="[
                ['label' => __('Dashboard'), 'url' => route('dashboard')],
                ['label' => __('Point of Sale'), 'url' => route('pos.index')],
            ]" />
        </x-slot> --}}

        <x-slot name="actions">
            <div class="flex items-center space-x-3">
                <x-button danger type="button" wire:click="clearCart" wire:loading.attr="disabled">
                    <i class="fas fa-undo mr-2"></i>
                    {{ __('Reset Cart') }}
                </x-button>
                <x-button success type="button" wire:click="proceed" wire:loading.attr="disabled"
                    class="{{ $total_amount == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{-- {{ $total_amount == 0 ? 'disabled' : '' }} --}}
                    >
                    <i class="fas fa-shopping-cart mr-2"></i>
                    {{ __('Proceed to Checkout') }}
                </x-button>
            </div>
        </x-slot>

        <div class="space-y-6">
            <x-validation-errors class="mb-4" :errors="$errors" />

            <!-- Selection Controls -->
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cog mr-2 text-blue-500"></i>
                    {{ __('Sale Configuration') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-label for="warehouse_id" :value="__('Warehouse')" class="mb-2" />
                        <div class="relative">
                            <select required id="warehouse_id" name="warehouse_id" wire:model.live="warehouse_id"
                                class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">{{ __('Select warehouse') }}</option>
                                @foreach ($this->warehouses as $index => $warehouse)
                                    <option value="{{ $index }}">{{ $warehouse }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-warehouse text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-label for="customer_id" :value="__('Customer')" class="mb-2" />
                        <div class="relative">
                            <select required id="customer_id" name="customer_id" wire:model.live="customer_id"
                                class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">{{ __('Select Customer') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Cart -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-shopping-basket mr-2 text-green-500"></i>
                        {{ __('Shopping Cart') }}
                    </h3>
                </div>
                <div class="p-6">
                    <livewire:utils.product-cart :cartInstance="'sale'" />
                </div>
            </div>
        </div>

        <x-modal wire:model="checkoutModal" max-width="4xl">
            <x-slot name="title">
                <div class="flex items-center">
                    <i class="fas fa-cash-register mr-3 text-green-500"></i>
                    {{ __('Checkout') }}
                </div>
            </x-slot>

            <x-slot name="content">
                <form id="checkout-form" wire:submit="store" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Payment Information -->
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
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-dollar-sign text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-label for="paid_amount" :value="__('Paid Amount')" required class="mb-2" />
                                    <div class="relative">
                                        <input id="paid_amount" type="text" wire:model.live="paid_amount"
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            name="paid_amount" required>
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-money-bill-wave text-gray-400"></i>
                                        </div>
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
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
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

                        <!-- Order Summary -->
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
                                            {{ $this->cart->count() }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600">{{ __('Order Tax') }}
                                            ({{ $global_tax }}%)</span>
                                        <span
                                            class="font-medium text-blue-600">+{{ format_currency($this->cart->tax()) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600">{{ __('Discount') }}
                                            ({{ $global_discount }}%)</span>
                                        <span
                                            class="font-medium text-red-600">-{{ format_currency($this->cart->discount()) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600">{{ __('Shipping') }}</span>
                                        <span
                                            class="font-medium text-blue-600">+{{ format_currency($shipping_amount) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-4 bg-gray-50 px-4 rounded-lg">
                                        <span
                                            class="text-lg font-semibold text-gray-900">{{ __('Grand Total') }}</span>
                                        <span
                                            class="text-lg font-bold text-green-600">{{ format_currency($total_with_shipping) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <x-button secondary type="button" x-on:click="checkoutModal = false">
                            <i class="fas fa-times mr-2"></i>
                            {{ __('Cancel') }}
                        </x-button>
                        <x-button success type="submit" wire:loading.attr="disabled" class="min-w-32">
                            <i class="fas fa-check mr-2"></i>
                            {{ __('Complete Sale') }}
                        </x-button>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    </x-page-container>
</div>
