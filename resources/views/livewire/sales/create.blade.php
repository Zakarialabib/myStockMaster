<div>
    <div class="w-full py-2 px-4">
        <div>
            <x-validation-errors class="mb-4" :errors="$errors" />

            <div class="mb-4">
                <x-select-list
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    required id="customer_id" name="customer_id" wire:model="customer_id" :options="$this->listsForFields['customers']" />
            </div>

            <div>
                <x-table>
                    <x-slot name="thead">
                        <x-table.th>{{ __('Product') }}</x-table.th>
                        <x-table.th>{{ __('Price') }}</x-table.th>
                        <x-table.th>{{ __('Quantity') }}</x-table.th>
                        <x-table.th>{{ __('Action') }}</x-table.th>
                    </x-slot>
                    <x-table.tbody>
                        @if ($cart_items->isNotEmpty())
                            @foreach ($cart_items as $cart_item)
                                <x-table.tr>
                                    <x-table.td>
                                        {{ $cart_item->name }} <br>
                                        <span class="badge badge-success">
                                            {{ $cart_item->options->code }}
                                        </span>
                                        {{-- @include('livewire.includes.product-cart-modal') --}}
                                    </x-table.td>

                                    <x-table.td>
                                        {{ format_currency($cart_item->price) }}
                                        @include('livewire.includes.product-cart-price')
                                    </x-table.td>

                                    <x-table.td>
                                        @include('livewire.includes.product-cart-quantity')
                                    </x-table.td>

                                    <x-table.td>
                                        <a href="#" wire:click.prevent="removeItem('{{ $cart_item->rowId }}')">
                                            <i class="bi bi-x-circle font-2xl text-danger"></i>
                                        </a>
                                    </x-table.td>
                                </x-table.tr>
                            @endforeach
                        @else
                            <x-table.tr>
                                <x-table.td colspan="8" class="text-center">
                                    <span class="text-red-500">
                                        {{ __('Please search & select products!') }}
                                    </span>
                                </x-table.td>
                            </x-table.tr>
                        @endif
                    </x-table.tbody>
                </x-table>
            </div>
        </div>

        <div class="flex flex-row">
            <div class="w-full">
                <div class="w-full mb-5 p-0">
                    <x-table-responsive>
                        <x-table.tr>
                            <x-table.th>{{ __('Order Tax') }} ({{ $global_tax }}%)</x-table.th>
                            <x-table.td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('Discount') }} ({{ $global_discount }}%)</x-table.th>
                            <x-table.td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}
                            </x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('Shipping') }}</x-table.th>
                            <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                            <x-table.td>(+) {{ format_currency($shipping) }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr class="text-primary">
                            <x-table.th>{{ __('Grand Total') }}</x-table.th>
                            @php
                                $total_with_shipping = Cart::instance($cart_instance)->total() + (float) $shipping;
                            @endphp
                            <x-table.th>
                                (=) {{ format_currency($total_with_shipping) }}
                            </x-table.th>
                        </x-table.tr>
                    </x-table-responsive>
                </div>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="total_amount">{{ __('Total Amount') }} <span class="text-red-500">*</span></label>
                <input id="total_amount" type="text" wire:model="total_amount"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    name="total_amount" value="{{ $total_amount }}" readonly required>
            </div>

            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="paid_amount">{{ __('Received Amount') }} <span class="text-red-500">*</span></label>
                <input id="paid_amount" type="text" wire:model="paid_amount" value="{{ $total_amount }}"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    name="paid_amount" required>
            </div>

            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="payment_method">{{ __('Payment Method') }} <span class="text-red-500">*</span></label>
                <select
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    name="payment_method" id="payment_method" wire:model="payment_method" required>
                    <option value="Cash">{{ __('Cash') }}</option>
                    <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                    <option value="Cheque">{{ __('Cheque') }}</option>
                    <option value="Other">{{ __('Other') }}</option>
                </select>
            </div>
        </div>

        <x-accordion title="{{ __('Details') }}">
            <div class="flex flex-wrap -mx-2 mb-3">
                @if (settings()->show_order_tax == true)
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <div class="mb-4">
                        <label for="tax_percentage">{{ __('Order Tax') }} (%)</label>
                        <input wire:model.lazy="tax_percentage" type="number"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            min="0" max="100" required>
                    </div>
                </div>
                @endif
                @if (settings()->show_discount == true)
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <div class="mb-4">
                        <label for="discount_percentage">{{ __('Discount') }} (%)</label>
                        <input wire:model.lazy="discount_percentage" type="number"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            min="0" max="100" required>
                    </div>
                </div>
                @endif
                @if (settings()->show_shipping == true)
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <div class="mb-4">
                        <label for="shipping_amount">{{ __('Shipping') }}</label>
                        <input wire:model.lazy="shipping_amount" type="number"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            min="0" value="0" required step="0.01">
                    </div>
                </div>
                @endif
                <div class="mb-4 w-full px-4">
                    <label for="note">{{ __('Note (If Needed)') }}</label>
                    <textarea name="note" id="note" rows="5" wire:model.lazy="note"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                </div>
            </div>
        </x-accordion>

    </div>

    <div class="mb-4 flex justify-center px-3 flex-wrap">
        <x-button danger wire:click="resetCart" wire:loading.attr="disabled" class="ml-2 font-bold">
            {{ __('Reset') }}
        </x-button>
        <button
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 bg-green-500 hover:bg-green-700"
            type="submit" wire:click="proceed" wire:loading.attr="disabled"
            {{ $total_amount == 0 ? 'disabled' : '' }}>
            {{ __('Proceed') }}
        </button>
    </div>

</div>
