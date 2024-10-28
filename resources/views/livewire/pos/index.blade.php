<div>
    <div class="w-full px-2" dir="ltr">
        <x-validation-errors class="mb-4" :errors="$errors"/>
        <div class="flex gap-4">

            <div class="w-full relative inline-flex">
                <select required id="warehouse_id" name="warehouse_id" wire:model.live="warehouse_id"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                    {{-- @if (settings()->default_warehouse_id == true)
                        <option value="{{ $default_warehouse?->id }}" selected>{{ $default_warehouse?->name }}
                        </option>
                    @endif --}}
                    <option value="">{{ __('Select warehouse') }}</option>
                    @foreach ($this->warehouses as $index => $warehouse)
                        <option value="{{ $index }}">{{ $warehouse }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full relative inline-flex">
                <select required id="customer_id" name="customer_id" wire:model.live="customer_id"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                    {{-- @if (settings()->default_client_id == true)
                        <option value="{{ $default_client?->id }}" selected>{{ $default_client?->name }}</option>
                    @endif --}}
                    <option value="">{{ __('Select Customer') }}</option>

                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <livewire:utils.product-cart :cartInstance="'sale'"/>

    </div>

    <div class="mb-4 d-flex justify-center flex-wrap">
        <x-button danger type="button" wire:click="resetCart" wire:loading.attr="disabled" class="ml-2 font-bold">
            {{ __('Reset') }}
        </x-button>
        <button
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 bg-green-500 hover:bg-green-700"
            type="submit" wire:click="proceed" wire:loading.attr="disabled" {{ $total_amount == 0 ? 'disabled' : '' }}>
            {{ __('Proceed') }}
        </button>
    </div>

    <x-modal wire:model.live="checkoutModal">
        <x-slot name="title">
            {{ __('Checkout') }}
        </x-slot>

        <x-slot name="content">
            <form id="checkout-form" wire:submit="store" class="py-5">
                <div class="flex flex-wrap">
                    <div class="w-1/2 px-2">
                        <div class="flex flex-wrap mb-3">
                            <div class="w-full px-2">
                                <x-label for="total_amount" :value="__('Total Amount')" required/>
                                <input id="total_amount" type="text" wire:model.live="total_amount"
                                       class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                       name="total_amount" readonly required>
                            </div>
                            <div class="w-full px-2">
                                <x-label for="paid_amount" :value="__('Paid Amount')" required/>
                                <input id="paid_amount" type="text" wire:model.live="paid_amount"
                                       class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                       name="paid_amount" required>
                            </div>
                            <div class="w-full px-2">
                                <x-label for="payment_method" :value="__('Payment Method')" required/>
                                <select wire:model.live="payment_method" id="payment_method" required
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                                    <option value="Cash">{{ __('Cash') }}</option>
                                    <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                                    <option value="Cheque">{{ __('Cheque') }}</option>
                                    <option value="Other">{{ __('Other') }}</option>
                                </select>
                            </div>
                            <div class="mb-4 w-full px-2">
                                <x-label for="note" :value="__('Note')"/>
                                <textarea name="note" id="note" rows="5" wire:model.live="note"
                                          class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="w-1/2 px-2">
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
                                    (+) {{ format_currency($shipping_amount) }}
                                </x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>
                                    {{ __('Grand Total') }}
                                </x-table.th>
                                <x-table.th>
                                    (=) {{ format_currency($total_with_shipping) }}
                                </x-table.th>
                            </x-table.tr>
                        </x-table-responsive>
                    </div>
                </div>
                <div class="float-left pb-4 px-2">
                    <x-button secondary type="button"
                              x-on:click="checkoutModal = false">{{ __('Close') }}</x-button>
                    <x-button primary type="submit" wire:loading.attr="disabled">{{ __('Submit') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
