<div x-data="productCart({
    currencyCode: '{{ settings()?->currency?->code ?? 'USD' }}',
    locale: '{{ settings()?->currency?->locale ?? 'en_US' }}'
})">
    <div class="relative">
        <x-validation-errors class="mb-4" :errors="$errors" />

        <div wire:loading class="absolute top-0 right-0 m-2 z-10">
            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
        </div>
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium">
                    <tr>
                        <th class="px-3 py-2">{{ __('Product') }}</th>
                        <th class="px-3 py-2">{{ __('Price') }}</th>
                        <th class="px-3 py-2 text-center">{{ __('Qty') }}</th>
                        <th class="px-3 py-2 text-right">{{ __('Total') }}</th>
                        <th class="px-3 py-2 text-center"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @if ($cart_items->isNotEmpty())
                        @foreach ($cart_items as $item)
                            <tr wire:key="cart-item-{{ $item->rowId }}"
                                class="transition-all duration-300 ease-in-out hover:bg-gray-50" x-data="{ pulse: false }"
                                x-init="$watch('$wire.quantity.{{ $item->id }}', value => {
                                    pulse = true;
                                    setTimeout(() => pulse = false, 300)
                                })" :class="pulse ? 'bg-green-50 scale-[1.01]' : ''">

                                <td class="px-3 py-2 align-top">
                                    <div class="flex items-start gap-2">
                                        <div class="hidden sm:block shrink-0 mt-1">
                                            @if ($item->attributes['image'] ?? null)
                                                <img src="{{ asset('images/products/' . $item->attributes['image']) }}"
                                                    class="w-8 h-8 object-cover rounded border border-gray-200"
                                                    alt="{{ $item->name }}">
                                            @else
                                                <div
                                                    class="w-8 h-8 bg-gray-100 rounded border border-gray-200 flex items-center justify-center text-gray-400">
                                                    <i class="fas fa-box text-xs"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <div class="font-semibold text-gray-800 line-clamp-2 leading-tight">
                                                {{ $item->name }}</div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                    {{ $item->attributes['code'] ?? '' }}
                                                </span>
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ ($item->attributes['stock'] ?? 0) > 0 ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                                                    {{ ($item->attributes['stock'] ?? 0) . ' ' . ($item->attributes['unit'] ?? '') }}
                                                </span>
                                            </div>
                                            @include('livewire.includes.product-cart-modal')
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 py-2 align-top">
                                    <div x-data="{ editPrice: false }" class="relative mt-1">
                                        <div x-show="!editPrice" class="flex items-center group cursor-pointer"
                                            x-on:click="editPrice = true">
                                            <span
                                                class="font-bold text-gray-700 whitespace-nowrap">{{ format_currency($item->attributes['unit_price'] ?? $item->price ?? 0) }}</span>
                                            <button type="button"
                                                class="ml-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <i class="fa fa-pen text-blue-500 text-[10px]"></i>
                                            </button>
                                        </div>
                                        <div x-show="editPrice" @click.away="editPrice = false" x-transition>
                                            <div wire:change="updatePrice('{{ $item->rowId }}', '{{ $item->id }}')"
                                                class="flex flex-col">
                                                <input type="number" step="0.01"
                                                    x-model="prices[{{ $item->id }}]"
                                                    wire:model.blur="price.{{ $item->id }}"
                                                    class="w-20 text-xs px-2 py-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="{{ format_currency($item->attributes['unit_price'] ?? $item->price ?? 0) }}" />
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 py-2 align-top text-center">
                                    <div class="mt-0.5 inline-block">
                                        <div class="flex items-center justify-center">
                                            <input type="number" step="1" min="1"
                                                x-model="quantities[{{ $item->id }}]"
                                                wire:model.blur="quantity.{{ $item->id }}"
                                                wire:change="updateQuantity('{{ $item->rowId }}', '{{ $item->id }}')"
                                                class="w-16 text-center text-sm px-2 py-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 py-2 align-top text-right">
                                    <div class="font-bold text-green-600 mt-1 whitespace-nowrap" x-text="formatCurrency((parseFloat(prices[{{ $item->id }}]) || 0) * (parseFloat(quantities[{{ $item->id }}]) || 0))">
                                        {{ format_currency($item->attributes['sub_total'] ?? ($item->price * $item->quantity)) }}
                                    </div>
                                </td>

                                <td class="px-3 py-2 align-top text-center">
                                    <button wire:click="removeItem('{{ $item->rowId }}')" wire:loading.attr="disabled"
                                        type="button"
                                        class="text-red-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-md transition-colors mt-0.5"
                                        title="{{ __('Remove') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-shopping-cart text-4xl mb-3 text-gray-300"></i>
                                    <span class="text-gray-500 font-medium">{{ __('Your cart is empty') }}</span>
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
