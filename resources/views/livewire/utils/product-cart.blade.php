<div x-data="productCart({
    currencyCode: '{{ settings()?->currency?->code ?? 'USD' }}',
    locale: '{{ settings()?->currency?->locale ?? 'en_US' }}'
})">
    <div class="relative">
        <x-validation-errors class="mb-4" :errors="$errors" />

        <div wire:loading class="absolute top-0 right-0 m-2 z-10">
            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
        </div>
        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-600 dark:text-gray-400 font-medium">
                    <tr>
                        <th class="px-3 py-2">{{ __('Product') }}</th>
                        <th class="px-3 py-2">{{ __('Price') }}</th>
                        <th class="px-3 py-2 text-center">{{ __('Qty') }}</th>
                        <th class="px-3 py-2 text-right">{{ __('Total') }}</th>
                        <th class="px-3 py-2 text-center"></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @if ($cart_items->isNotEmpty())
                        @foreach ($cart_items as $item)
                            <x-cart-item-row :item="$item" :cartInstance="'pos'" />
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-shopping-cart text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <span class="text-blue-500 dark:text-blue-400 font-medium">{{ __('Your cart is empty') }}</span>
                                    <span
                                        class="text-xs mt-1">{{ __('Search and select products to add them to the cart.') }}</span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap md:justify-end">
            <div class="w-full">
                <div class="w-full py-2 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @if (settings()->show_order_tax == true)
                            <div class="flex justify-between items-center px-4 py-3 text-sm">
                                <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('Order Tax') }} (<span x-text="globalTax"></span>%)</span>
                                <span class="font-bold text-gray-900 dark:text-gray-100" x-text="'(+) ' + formatCurrency(cartTax)">(+) {{ format_currency($this->cartTax) }}</span>
                            </div>
                        @endif
                        @if (settings()->show_discount == true)
                            <div class="flex justify-between items-center px-4 py-3 text-sm">
                                <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('Discount') }} (<span x-text="globalDiscount"></span>%)</span>
                                <span class="font-bold text-gray-900 dark:text-gray-100" x-text="'(-) ' + formatCurrency(cartDiscount)">(-) {{ format_currency($this->cartDiscount) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center px-4 py-3 text-sm">
                            <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('Shipping') }}</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100" x-text="'(+) ' + formatCurrency(shippingAmount)">(+) {{ format_currency($shipping_amount) }}</span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 bg-white dark:bg-gray-900 rounded-b-lg">
                            <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ __('Grand Total') }}</span>
                            <span class="text-lg font-bold text-primary-600 dark:text-primary-400" x-text="'(=) ' + formatCurrency(grandTotal)">
                                (=) {{ format_currency($this->cartTotal + $shipping_amount) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="total_amount" x-bind:value="grandTotal" value="{{ $this->cartTotal + $shipping_amount }}">

        <div class="grid grid-cols-3 gap-3 mt-4">
            <div>
                <label for="tax_percentage"
                    class="block text-xs font-medium text-gray-700 mb-1">{{ __('Order Tax (%)') }}</label>
                <x-input type="number" step="0.01" wire:model.blur="global_tax" x-model="globalTax" />
            </div>
            <div>
                <label for="discount_percentage"
                    class="block text-xs font-medium text-gray-700 mb-1">{{ __('Discount (%)') }}</label>
                <x-input type="number" step="0.01" wire:model.blur="global_discount" x-model="globalDiscount" />
            </div>
            <div>
                <label for="shipping_amount"
                    class="block text-xs font-medium text-gray-700 mb-1">{{ __('Shipping') }}</label>
                <x-input type="number" step="0.01" wire:model.blur="shipping_amount" x-model="shippingAmount" />
            </div>
        </div>
    </div>

    @script
    <script>
        Alpine.data('productCart', (config) => ({
            quantities: $wire.entangle('quantity'),
            prices: $wire.entangle('price'),
            globalTax: $wire.entangle('global_tax'),
            globalDiscount: $wire.entangle('global_discount'),
            shippingAmount: $wire.entangle('shipping_amount'),
            
            get cartSubtotal() {
                let total = 0;
                for (let id in this.quantities) {
                    let qty = parseFloat(this.quantities[id]) || 0;
                    let price = parseFloat(this.prices[id]) || 0;
                    total += (qty * price);
                }
                return total;
            },
            
            get cartTax() {
                let taxPercentage = parseFloat(this.globalTax) || 0;
                return (this.cartSubtotal * taxPercentage) / 100;
            },
            
            get cartDiscount() {
                let discountPercentage = parseFloat(this.globalDiscount) || 0;
                return (this.cartSubtotal * discountPercentage) / 100;
            },
            
            get grandTotal() {
                let shipping = parseFloat(this.shippingAmount) || 0;
                return this.cartSubtotal + this.cartTax - this.cartDiscount + shipping;
            },

            formatCurrency(value) {
                let locale = config.locale.replace('_', '-');
                try {
                    let formatter = new Intl.NumberFormat(locale, {
                        style: 'currency',
                        currency: config.currencyCode
                    });
                    return formatter.format(value || 0);
                } catch (e) {
                    return '$' + parseFloat(value || 0).toFixed(2);
                }
            }
        }))
    </script>
    @endscript
</div>
