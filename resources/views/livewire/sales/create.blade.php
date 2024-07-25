<div>
    @section('title', __('Create Sale'))

    <x-theme.breadcrumb :title="__('Create Sale')" :parent="route('sales.index')" :parentName="__('Sales List')" :childrenName="__('Create Sale')">

        @can('customer_create')
            <x-button primary type="button" wire:click="dispatchTo('customer.create', 'createModal')">
                {{ __('Create Customer') }}
            </x-button>
        @endcan

    </x-theme.breadcrumb>
    <div class="mt-2 flex flex-wrap">
        <div class="lg:w-1/2 sm:w-full h-full">
            <livewire:utils.search-product />
        </div>
        <div class="lg:w-1/2 sm:w-full h-full">
            <x-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit="store">

                <div class="mb-4 flex flex-wrap gap-4">
                    <div class="flex-1">
                        <x-label for='customer_id' :value="__('Customer')" required />
                        <x-select-list
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required id="customer_id" name="customer_id" wire:model.live="customer_id"
                            :options="$this->customers" />
                        <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                    </div>
                    <div class="flex-1">
                        <x-label for="date" :value="__('Date')" required />
                        <input type="date" name="date" required wire:model="date"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>
                    <div class="flex-1">
                        <x-label for="warehouse" :value="__('Warehouse')" />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required id="warehouse_id" name="warehouse_id" wire:model.live="warehouse_id">
                            <option value=""> {{ __('Select warehouse') }}</option>
                            @foreach ($this->warehouses as $index => $warehouse)
                                <option value="{{ $index }}">{{ $warehouse }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                    </div>
                </div>

                <livewire:utils.product-cart :cartInstance="'sale'" />
                <div class="mb-4 grid md:grid-cols-2 sm:grid-cols-1 gap-4">

                    <div class="">
                        <label for="payment_method">{{ __('Payment Method') }} <span
                                class="text-red-500">*</span></label>
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="payment_method" id="payment_method" wire:model.live="payment_method" required>
                            <option value=""> {{ __('Select option') }}</option>
                            <option value="Cash">{{ __('Cash') }}</option>
                            <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                            <option value="Cheque">{{ __('Cheque') }}</option>
                            <option value="Other">{{ __('Other') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />

                    </div>
                    <div class="">
                        <label for="status">{{ __('Status') }} <span class="text-red-500">*</span></label>
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="status" id="status" required wire:model.live="status">
                            <option value="" selected> {{ __('Select option') }}</option>
                            @foreach (\App\Enums\SaleStatus::cases() as $status)
                                <option value="{{ $status->value }}">
                                    {{ __($status->name) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />

                    </div>

                    <div class="">
                        <label for="total_amount">{{ __('Total Amount') }} <span class="text-red-500">*</span></label>
                        <x-input id="total_amount" type="text" wire:model.live="total_amount" name="total_amount"
                            value="{{ $total_amount }}" readonly required />
                    </div>

                    <div class="">
                        <label for="paid_amount">{{ __('Received Amount') }} <span
                                class="text-red-500">*</span></label>
                        <x-input id="paid_amount" type="text" wire:model.live="paid_amount"
                            value="{{ $total_amount }}" name="paid_amount" required />

                    </div>
                </div>

                <div class="my-4">
                    <label for="note">{{ __('Note (If Needed)') }}</label>
                    <textarea name="note" id="note" rows="5" wire:model.live="note"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                </div>

                <div class="flex flex-wrap gap-4 justify-between">
                    <x-button danger type="button" wire:click="resetCart" wire:loading.attr="disabled"
                        class="ml-2 font-bold flex-1">
                        {{ __('Reset') }}
                    </x-button>
                    <button
                        class="flex-1 items-center px-4 py-2 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-500 disabled:opacity-25 transition ease-in-out duration-150 bg-green-600 hover:bg-green-700"
                        type="button" wire:click.throttle="proceed" wire:loading.attr="disabled"
                        {{ $total_amount == 0 ? 'disabled' : '' }}>
                        {{ __('Proceed') }}
                    </button>
                </div>

            </form>
        </div>
    </div>

    <livewire:customers.create />

    <livewire:cash-register.create />

</div>
