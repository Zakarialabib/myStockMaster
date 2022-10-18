<x-modal wire:model="checkoutModal">
    <x-slot name="title">
        {{ __('Checkout') }}
    </x-slot>

    <x-slot name="content">
        <form id="checkout-form" wire:submit.prevent="store">
            <div>
                @if (session()->has('checkout_message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="alert-body">
                            <span>{{ session('checkout_message') }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="flex flex-wrap">
                    <div class="w-1/2 px-2">
                        <input type="hidden" value="{{ $customer_id }}" name="customer_id">
                        <input type="hidden" value="{{ $global_tax }}" name="tax_percentage">
                        <input type="hidden" value="{{ $global_discount }}" name="discount_percentage">
                        <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                        <div class="flex flex-wrap -mx-1">
                            <div class="w-full px-2">
                                <label for="total_amount">{{ __('Total Amount') }} <span
                                        class="text-red-500">*</span></label>
                                <input id="total_amount" type="text"
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                    name="total_amount" value="{{ $total_amount }}" readonly required>
                            </div>
                            <div class="w-full px-2">
                                <label for="paid_amount">{{ __('Received Amount') }} <span
                                        class="text-red-500">*</span></label>
                                <input id="paid_amount" type="text"
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                    name="paid_amount" value="{{ $total_amount }}" required>
                            </div>
                            <div class="w-full px-2">
                                <label for="payment_method">{{ __('Payment Method') }} <span
                                        class="text-red-500">*</span></label>
                                <select
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                    name="payment_method" id="payment_method" required>
                                    <option value="Cash">{{ __('Cash') }}</option>
                                    <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                                    <option value="Cheque">{{ __('Cheque') }}</option>
                                    <option value="Other">{{ __('Other') }}</option>
                                </select>
                            </div>
                            <div class="mb-4 w-full">
                                <label for="note">{{ __('Note (If Needed)') }}</label>
                                <textarea name="note" id="note" rows="5"
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"></textarea>
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
                                    <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
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
            </div>
            <div class="float-left px-2">
                <x-button secondary type="button" data-dismiss="modal">{{ __('Close') }}</x-button>
                <x-button primary type="submit">{{ __('Submit') }}</x-button>
            </div>
        </form>
    </x-slot>
</x-modal>
