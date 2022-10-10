<div>
    <div class="w-full py-2 px-6 shadow-md sm:rounded-lg">
        <div>
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <div class="mb-4">
                <a href="{{ route('customers.create') }}"
                    class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">
                    {{ __('Add Customer') }}
                </a>
                <select wire:model="customer_id" id="customer_id"
                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded">
                    <option value="" selected>{{ __('Select Customer') }}</option>
                    @foreach ($customers as $customer)
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
                                        {{-- @include('livewire.includes.product-cart-modal') --}}
                                    </x-table.td>

                                    <x-table.td>
                                        {{ format_currency($cart_item->price) }}
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
            <div class="w-full px-4">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>{{ __('Order Tax') }} ({{ $global_tax }}%)</th>
                            <td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Discount') }} ({{ $global_discount }}%)</th>
                            <td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Shipping') }}</th>
                            <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                            <td>(+) {{ format_currency($shipping) }}</td>
                        </tr>
                        <tr class="text-primary">
                            <th>{{ __('Grand Total') }}</th>
                            @php
                                $total_with_shipping = Cart::instance($cart_instance)->total() + (float) $shipping;
                            @endphp
                            <th>
                                (=) {{ format_currency($total_with_shipping) }}
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap -mx-1">
            <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                <div class="mb-4">
                    <label for="tax_percentage">{{ __('Order Tax') }} (%)</label>
                    <input wire:model.lazy="global_tax" type="number"
                        class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                        min="0" max="100" value="{{ $global_tax }}" required>
                </div>
            </div>
            <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                <div class="mb-4">
                    <label for="discount_percentage">{{ __('Discount') }} (%)</label>
                    <input wire:model.lazy="global_discount" type="number"
                        class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                        min="0" max="100" value="{{ $global_discount }}" required>
                </div>
            </div>
            <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                <div class="mb-4">
                    <label for="shipping_amount">{{ __('Shipping') }}</label>
                    <input wire:model.lazy="shipping" type="number"
                        class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                        min="0" value="0" required step="0.01">
                </div>
            </div>
        </div>

        <div class="mb-4 d-flex justify-content-center flex-wrap md:mb-0">
            <x-button danger wire:click="resetCart" wire:loading.attr="disabled" class="ml-2">
                <i class="bi bi-x"></i> {{ __('Reset') }}
            </x-button>
            <button wire:click="proceed" {{ $total_amount == 0 ? 'disabled' : '' }} wire:loading.attr="disabled"
                class="ml-2">
                <i class="bi bi-check"></i> {{ __('Proceed') }}
                <button>
        </div>
    </div>

    {{-- Checkout Modal --}}
    @include('livewire.pos.includes.checkout-modal')

</div>
