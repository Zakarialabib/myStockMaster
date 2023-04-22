<div>
    <div>
        <div class="w-full px-2" dir="ltr">
            <div>
                <x-validation-errors class="mb-4" :errors="$errors" />
                @php
                    $default_client = \App\Models\Customer::find(settings()->default_client_id);
                @endphp

                <div class="w-full relative inline-flex">
                    <select required id="customer_id" name="customer_id" wire:model="customer_id"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                        @if (settings()->default_client_id == true)
                            <option value="{{ $default_client->id }}" selected>{{ $default_client->name }}</option>
                        @endif
                        @foreach ($this->customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
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
                                            @include('livewire.includes.product-cart-modal')
                                        </x-table.td>

                                        <x-table.td class="flex justify-center flex-col">
                                            {{ format_currency($cart_item->price) }}
                                            @include('livewire.includes.product-cart-price')
                                        </x-table.td>

                                        <x-table.td>
                                            @include('livewire.includes.product-cart-quantity')
                                        </x-table.td>

                                        <x-table.td>
                                            <a href="#"
                                                wire:click.prevent="removeItem('{{ $cart_item->rowId }}')">
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
                            @if (settings()->show_order_tax == true)
                                <x-table.tr>
                                    <x-table.th>{{ __('Order Tax') }} ({{ $global_tax }}%)</x-table.th>
                                    <x-table.td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}
                                    </x-table.td>
                                </x-table.tr>
                            @endif
                            @if (settings()->show_discount == true)
                                <x-table.tr>
                                    <x-table.th>{{ __('Discount') }} ({{ $global_discount }}%)</x-table.th>
                                    <x-table.td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}
                                    </x-table.td>
                                </x-table.tr>
                            @endif
                            <x-table.tr>
                                <x-table.th>{{ __('Shipping') }}</x-table.th>
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

            <div class="flex flex-wrap -mx-2 mb-3">
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <div class="mb-4">
                        <label for="tax_percentage">{{ __('Order Tax') }} (%)</label>
                        <x-input wire:model.lazy="tax_percentage" type="text" value="{{ $global_tax }}" />
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <div class="mb-4">
                        <label for="discount_percentage">{{ __('Discount') }} (%)</label>
                        <x-input wire:model.lazy="discount_percentage" type="text" value="{{ $global_discount }}" />
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <div class="mb-4">
                        <label for="shipping_amount">{{ __('Shipping') }}</label>
                        <x-input wire:model.lazy="shipping" type="text" />
                    </div>
                </div>
            </div>

            <div class="mb-4 d-flex justify-center flex-wrap">
                <x-button danger wire:click="resetCart" wire:loading.attr="disabled" class="ml-2 font-bold">
                    {{ __('Reset') }}
                </x-button>
                <button
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 bg-green-500 hover:bg-green-700"
                    type="submit" wire:click="proceed" wire:loading.attr="disabled"
                    {{ $total_amount == 0 ? 'disabled' : '' }}>
                    {{ __('Proceed') }}
                </button>
            </div>
        </div>

        {{-- Checkout Modal --}}
        <x-modal wire:model="checkoutModal">
            <x-slot name="title">
                {{ __('Checkout') }}
            </x-slot>

            <x-slot name="content">
                <form id="checkout-form" wire:submit.prevent="store" class="py-5">
                    <div class="flex flex-wrap">
                        <div class="w-1/2 px-2">
                            <div class="flex flex-wrap -mx-2 mb-3">
                                <div class="w-full px-2">
                                    <x-label for="total_amount" :value="__('Total Amount')" required />
                                    <input id="total_amount" type="text" wire:model="total_amount"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                        name="total_amount" readonly required>
                                </div>
                                <div class="w-full px-2">
                                    <x-label for="paid_amount" :value="__('Paid Amount')" required />
                                    <input id="paid_amount" type="text" wire:model="paid_amount"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                        name="paid_amount" required>
                                </div>
                                <div class="w-full px-2">
                                    <x-label for="payment_method" :value="__('Payment Method')" required />
                                    <select wire:model="payment_method" id="payment_method" required
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                                        <option value="Cash">{{ __('Cash') }}</option>
                                        <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                                        <option value="Cheque">{{ __('Cheque') }}</option>
                                        <option value="Other">{{ __('Other') }}</option>
                                    </select>
                                </div>
                                <div class="mb-4 w-full">
                                    <x-label for="note" :value="__('Note')" />
                                    <textarea name="note" id="note" rows="5" wire:model="note"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="w-1/2 px-2">
                            <div>
                                <x-table-responsive>
                                    <x-table.tr>
                                        <x-table.th>
                                            {{ __('Total Products') }}
                                        </x-table.th>
                                        <x-table.td>
                                            <span class="badge badge-success">
                                                {{ Cart::instance($cart_instance)->count() }}
                                            </span>
                                        </x-table.td>
                                    </x-table.tr>
                                    <x-table.tr>
                                        <x-table.th>
                                            {{ __('Order Tax') }} ({{ $global_tax }}%)
                                        </x-table.th>
                                        <x-table.td>
                                            (+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}
                                        </x-table.td>
                                    </x-table.tr>
                                    <x-table.tr>
                                        <x-table.th>
                                            {{ __('Discount') }} ({{ $global_discount }}%)
                                        </x-table.th>
                                        <x-table.td>
                                            (-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}
                                        </x-table.td>
                                    </x-table.tr>
                                    <x-table.tr>
                                        <x-table.th>
                                            {{ __('Shipping') }}
                                        </x-table.th>
                                        <x-table.td>
                                            (+) {{ format_currency($shipping) }}
                                        </x-table.td>
                                    </x-table.tr>
                                    <x-table.tr>
                                        <x-table.th>
                                            {{ __('Grand Total') }}
                                        </x-table.th>
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
                    <div class="float-left pb-4 px-2">
                        <x-button secondary type="button" data-dismiss="modal">{{ __('Close') }}</x-button>
                        <x-button primary type="submit">{{ __('Submit') }}</x-button>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {
            window.addEventListener('showCheckoutModal', event => {
                $('#checkoutModal').modal('show');

                $('#paid_amount').maskMoney({
                    prefix: '{{ settings()->currency->symbol }}',
                    thousands: '{{ settings()->currency->thousand_separator }}',
                    decimal: '{{ settings()->currency->decimal_separator }}',
                    allowZero: false,
                });

                $('#total_amount').maskMoney({
                    prefix: '{{ settings()->currency->symbol }}',
                    thousands: '{{ settings()->currency->thousand_separator }}',
                    decimal: '{{ settings()->currency->decimal_separator }}',
                    allowZero: true,
                });

                $('#paid_amount').maskMoney('mask');
                $('#total_amount').maskMoney('mask');

                $('#checkout-form').submit(function() {
                    var paid_amount = $('#paid_amount').maskMoney('unmasked')[0];
                    $('#paid_amount').val(paid_amount);
                    var total_amount = $('#total_amount').maskMoney('unmasked')[0];
                    $('#total_amount').val(total_amount);
                });
            });
        });
    </script>
@endpush
