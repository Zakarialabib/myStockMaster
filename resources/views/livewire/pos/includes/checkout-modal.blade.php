<x-modal wire:model="checkoutModal">
    <x-slot name="title">
        {{ __('Checkout') }}
    </x-slot>

    <x-slot name="content">
            <form id="checkout-form" action="{{ route('app.pos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
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
                        <div class="md:w-3/5 px-4">
                            <input type="hidden" value="{{ $customer_id }}" name="customer_id">
                            <input type="hidden" value="{{ $global_tax }}" name="tax_percentage">
                            <input type="hidden" value="{{ $global_discount }}" name="discount_percentage">
                            <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                            <div class="flex flex-wrap -mx-1">
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="total_amount">{{__('Total Amount')}} <span class="text-red-500">*</span></label>
                                        <input id="total_amount" type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="total_amount" value="{{ $total_amount }}" readonly required>
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="paid_amount">{{__('Received Amount')}} <span class="text-red-500">*</span></label>
                                        <input id="paid_amount" type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="paid_amount" value="{{ $total_amount }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="payment_method">{{__('Payment Method')}} <span class="text-red-500">*</span></label>
                                <select class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="payment_method" id="payment_method" required>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="note">{{__('Note (If Needed)')}}</label>
                                <textarea name="note" id="note" rows="5" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{__('Total Products')}}</th>
                                        <td>
                                                <span class="badge badge-success">
                                                    {{ Cart::instance($cart_instance)->count() }}
                                                </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Order Tax')}} ({{ $global_tax }}%)</th>
                                        <td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Discount')}} ({{ $global_discount }}%)</th>
                                        <td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Shipping')}}</th>
                                        <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                                        <td>(+) {{ format_currency($shipping) }}</td>
                                    </tr>
                                    <tr class="text-primary">
                                        <th>{{__('Grand Total')}}</th>
                                        @php
                                            $total_with_shipping = Cart::instance($cart_instance)->total() + (float) $shipping
                                        @endphp
                                        <th>
                                            (=) {{ format_currency($total_with_shipping) }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">Submit</button>
                </div>
                
            </form>
    </x-slot>
</x-modal>