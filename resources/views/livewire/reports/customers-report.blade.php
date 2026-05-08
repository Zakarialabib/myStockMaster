<div>
    <x-page-container :title="__('Customers Report')" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Customers Report')]]" :show-filters="true">

        <x-slot name="filters">
            <div class="flex flex-wrap mb-3">
                <div class="w-full md:w-1/3 px-2 mb-2">
                    <div class="mb-4">
                        <label>{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                        <x-input wire:model.live="start_date" type="date" name="start_date" />
                        @error('start_date')
                            <span class="text-danger mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-2 mb-2">
                    <div class="mb-4">
                        <label>{{ __('End Date') }} <span class="text-red-500">*</span></label>
                        <x-input wire:model.live="end_date" type="date" name="end_date" />
                        @error('end_date')
                            <span class="text-danger mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-2 mb-2">
                    <div class="mb-4">
                        <label>{{ __('Customer') }}</label>
                        <x-select-list :options="$this->customers" name="customer_id" id="customer_id"
                            wire:model.live="customer_id" />
                    </div>
                </div>
                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                    <div class="mb-4">
                        <label>{{ __('Payment Status') }}</label>
                        <x-select wire:model.live="payment_status"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="payment_status" id="payment_status" required>
                            <option value="">{{ __('Select Payment Status') }}</option>
                            @foreach (\App\Enums\PaymentStatus::cases() as $status)
                                <option value="{{ $status->value }}">
                                    {{ __($status->name) }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                </div>
            </div>
        </x-slot>

        <div class="grid xl:grid-cols-2 sm:grid-cols-1 gap-4 mb-4">
            <x-card-tooltip icon="fa fa-money-bill" color="green">
                <span class="text-2xl">{{ format_currency($this->ltv) }}</span>
                <p>{{ __('Life Time Value (LTV)') }}</p>
                <x-slot name="content">
                    <p class="text-sm">
                        {{ __('The total value of completed sales.') }}
                    </p>
                </x-slot>
            </x-card-tooltip>

            <x-card-tooltip icon="fa fa-money-bill" color="red">
                <span class="text-2xl">{{ format_currency($this->totalDueAmount) }}</span>
                <p>{{ __('Total Due Amount') }}</p>
                <x-slot name="content">
                    <p class="text-sm">
                        {{ __('The total due amount for the selected customers.') }}
                    </p>
                </x-slot>
            </x-card-tooltip>
        </div>

        <x-table>
            <x-slot name="thead">
                <x-table.th>{{ __('Date') }}</x-table.th>
                <x-table.th>{{ __('Reference') }}</x-table.th>
                <x-table.th>{{ __('Customer') }}</x-table.th>
                <x-table.th>{{ __('Status') }}</x-table.th>
                <x-table.th>{{ __('Total') }}</x-table.th>
                <x-table.th>{{ __('Paid') }}</x-table.th>
                <x-table.th>{{ __('Due') }}</x-table.th>
                <x-table.th>{{ __('Payment Status') }}</x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse($this->sales as $sale)
                    <x-table.tr>
                        <x-table.td>{{ format_date($sale->date) }}</x-table.td>
                        <x-table.td>{{ $sale->reference }}</x-table.td>
                        <x-table.td>
                            <a href="{{ route('customer.details', $sale->customer?->id) }}"
                                class="text-indigo-500 hover:text-indigo-600">
                                {{ $sale->customer?->name }}
                            </a>
                        </x-table.td>
                        <x-table.td>
                            @php
                                $badgeType = $sale->status->getBadgeType();
                            @endphp

                            <x-badge :type="$badgeType">{{ $sale->status->getName() }}</x-badge>
                        </x-table.td>
                        <x-table.td>{{ format_currency($sale->total_amount) }}</x-table.td>
                        <x-table.td>{{ format_currency($sale->paid_amount) }}</x-table.td>
                        <x-table.td>{{ format_currency($sale->due_amount) }}</x-table.td>
                        <x-table.td>
                            @php
                                $type = $sale->payment_status->getBadgeType();
                            @endphp
                            <x-badge :type="$type">{{ $sale->payment_status->getName() }}</x-badge>
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <x-table.td colspan="8">
                            <span class="text-red-500">{{ __('No Sales Data Available!') }}</span>
                        </x-table.td>
                    </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table>
        <div @class(['mt-3' => $this->sales->hasPages()])>
            {{ $this->sales->links() }}
        </div>
    </x-page-container>
</div>
