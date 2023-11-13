<div>
    <form wire:submit.prevent="generateReport">
        <div class="w=full gap-2 flex justify-center items-center mx-0 px-2">
            <x-button type="button" primary wire:click="filterByDate('day')">{{ __('Today') }}</x-button>
            <x-button type="button" info wire:click="filterByDate('month')">{{ __('This Month') }}</x-button>
            <x-button type="button" warning wire:click="filterByDate('year')">{{ __('This Year') }}</x-button>
        </div>
        <div class="flex flex-wrap px-2 text-center mb-3">
            <div class="lg:w-1/3 sm:w-full px-4">
                <x-label for="start_date" :value="__('Start Date')" required />
                <x-input wire:model.defer="start_date" type="date" name="start_date" />
                @error('start_date')
                    <span class="text-danger mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="lg:w-1/3 sm:w-full px-4">
                <x-label for="end_date" :value="__('End Date')" required />
                <x-input wire:model.defer="end_date" type="date" name="end_date" />
                @error('end_date')
                    <span class="text-danger mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="lg:w-1/3 sm:w-full px-4">
                <x-label for="warehouse_id" :value="__('Warehouse')" required />
                <select wire:model="warehouse_id" name="warehouse_id" required
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                    <option>{{ __('Select Warehouse') }}</option>
                    @foreach ($this->warehouses as $index => $warehouse)
                        <option value="{{ $index }}">{{ $warehouse }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="my-4 text-center">
            <x-button primary type="submit" wire:target="generateReport" wire:loading.attr="disabled">
                <span wire:target="generateReport" wire:loading class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
                <i wire:target="generateReport" wire:loading.remove class="fa fa-shuffle"></i>
                {{ __('Filter Report') }}
            </x-button>
        </div>
    </form>

    <div class="grid xl:grid-cols-3 sm:grid-cols-2 gap-2">
        {{-- Purchases --}}
        <x-card-tooltip icon="bi bi-bag" :href="route('purchases.index')" color="orange">
            <span class="text-2xl">{{ format_currency($purchases_amount) }}</span>
            <p>{{ __('Purchases') }}</p>
            <x-slot name="content">
                <p class="text-sm">
                    {{ __('The total number of treasures acquired during this period.') }}
                </p>
                <p class="text-sm">
                    {{ __('Successfully obtained: ') . $completed_purchases . __(' // Still on the horizon: ') . $pending_purchases }}
                </p>
            </x-slot>
        </x-card-tooltip>

        {{-- Purchase Returns --}}
        <x-card-tooltip icon="fa fa-arrow-right" color="yellow">
            <span class="text-2xl">{{ format_currency($purchase_returns_amount) }}</span>
            <p>{{ __('Purchase Returns') }}</p>
            <x-slot name="content">
                <p class="text-sm">
                    {{ __('The count of items returning from their adventures.') }}
                </p>
                <p class="text-sm">
                    {{ __('Total returns: ') . $total_purchase_returns . __(' // Value returned: ') . format_currency($purchase_returns_amount) }}
                </p>
            </x-slot>
        </x-card-tooltip>

        {{-- Sales --}}
        <x-card-tooltip icon="fa fa-receipt" color="blue" :href="route('sales.index')">
            <span class="text-2xl">{{ format_currency($sales_amount) }}</span>
            <p>{{ __('Sales') }}</p>
            <x-slot name="content">
                <p class="text-sm">
                    {{ __('The total value of goods successfully traded during this epic journey.') }}
                </p>
                <p class="text-sm">
                    {{ __('Successful transactions: ') . $total_sales }}
                </p>
            </x-slot>
        </x-card-tooltip>

        {{-- Sale Returns --}}
        <x-card-tooltip icon="fa fa-arrow-left" color="green">
            <span class="text-2xl">{{ format_currency($sale_returns_amount) }}</span>
            <p>{{ __('Sale Returns') }}</p>
            <x-slot name="content">
                <p class="text-sm">
                    {{ __('The count of goods deciding to return to their origin.') }}
                </p>
                <p class="text-sm">
                    {{ __('Total returns: ') . $total_sale_returns . __(' // Value returned: ') . format_currency($sale_returns_amount) }}
                </p>
            </x-slot>
        </x-card-tooltip>

        {{-- Payments Received --}}
        <x-card-tooltip icon="fa fa-cash-register" color="green">
            <span class="text-2xl">{{ format_currency($payments_received_amount) }}</span>
            <p>{{ __('Payments Received') }}</p>
            <x-slot name="content">
                <p class="text-sm">
                    {{ __('The total amount received from valiant warriors and wise merchants.') }}
                </p>
            </x-slot>
        </x-card-tooltip>

        {{-- Payments Sent --}}
        <x-card-tooltip icon="fa fa-money-bill" color="blue">
            <span class="text-2xl">{{ format_currency($payments_sent_amount) }}</span>
            <p>{{ __('Payments Sent') }}</p>
            <x-slot name="content">
                <p class="text-sm">
                    {{ __('The total amount sent for acquiring new treasures and honoring return quests.') }}
                </p>
                <p class="text-sm">
                    {{ __('Includes expenses for maps, supplies, and magical artifacts.') }}
                </p>
            </x-slot>
        </x-card-tooltip>

        {{-- Expenses --}}
        <x-card-tooltip icon="fa fa-wallet" color="purple">
            <span class="text-2xl">{{ format_currency($expenses_amount) }}</span>
            <p>{{ __('Expenses') }}</p>
            <x-slot name="content">
                <p class="text-sm">
                    {{ __('The total cost incurred during this adventure.') }}
                </p>
            </x-slot>
        </x-card-tooltip>

        {{-- Payments Net --}}
        <x-card-tooltip icon="fa fa-money-bills" color="orange">
            <span class="text-2xl">{{ format_currency($payments_net_amount) }}</span>
            <p>{{ __('Payments Net') }}</p>
            <x-slot name="content">
                <p class="text-sm">
                    {{ __('Net Payments is the magic result of received gold after deducting sent gold.') }}
                </p>
                <p class="text-sm">
                    {{ __('This represents the net amount received after deducting payments sent on quests.') }}
                </p>
            </x-slot>
        </x-card-tooltip>

        {{-- Profit --}}
        <x-card-tooltip class="col-span-full" icon="bi bi-trophy" color="indigo">
            <span class="text-2xl">{{ format_currency($profit_amount) }}</span>
            <p>{{ __('Profit') }}</p>
            <x-slot name="content">
                <p class="text-sm">
                    {{ __('Profit is the epic result of successful trades and quests - the costs of obtaining treasures.') }}
                </p>
            </x-slot>
        </x-card-tooltip>
    </div>
</div>
