<div>
    <x-page-container title="{{ __('Sales Return Report') }}" :breadcrumbs="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Reports'), 'url' => '#'],
        ['label' => __('Sales Return Report'), 'url' => '#']
    ]" :show-filters="true">

        <x-slot name="filters">
            <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md dark:bg-gray-800 dark:border-blue-500">
                <div class="flex items-start">
                    <div class="shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <strong>{{ __('How to get the most from this report:') }}</strong> 
                            {{ __('Use the date filters to narrow down your sales returns. Filter by specific customers or payment statuses to easily track outstanding balances and refund progress.') }}
                        </p>
                    </div>
                </div>
            </div>

            <form wire:submit="generateReport">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                    <div>
                        <x-label for="start_date" :value="__('Start Date')" />
                        <x-input wire:model="start_date" type="date" id="start_date" />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="end_date" :value="__('End Date')" />
                        <x-input wire:model="end_date" type="date" id="end_date" />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="customer_id" :value="__('Customer')" />
                        <x-select-list :options="$this->customers" wire:model.live="customer_id" id="customer_id" />
                    </div>
                    <div>
                        <x-label for="sale_return_status" :value="__('Status')" />
                        <x-select wire:model="sale_return_status" id="sale_return_status" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                            <option value="">{{ __('All Statuses') }}</option>
                            @foreach (\App\Enums\SaleReturnStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ __($status->name) }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div>
                        <x-label for="payment_status" :value="__('Payment Status')" />
                        <x-select wire:model="payment_status" id="payment_status" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                            <option value="">{{ __('All Payment Statuses') }}</option>
                            @foreach (\App\Enums\PaymentStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ __($status->name) }}</option>
                            @endforeach
                        </x-select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-button type="submit" primary>{{ __('Filter Report') }}</x-button>
                </div>
            </form>
        </x-slot>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative">
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
                            @forelse($this->saleReturns as $sale_return)
                                <x-table.tr>
                                    <x-table.td>{{ format_date($sale_return->date) }}
                                    </x-table.td>
                                    <x-table.td>{{ $sale_return->reference }}</x-table.td>
                                    <x-table.td>
                                        <a href="{{ route('customer.details', $sale_return->customer->uuid) }}"
                                            class="text-indigo-500 hover:text-indigo-600">
                                            {{ $sale_return->customer->name }}
                                        </a>
                                    </x-table.td>
                                    <x-table.td>
                                        @php
                                            $badgeType = $sale_return->status->getBadgeType();
                                        @endphp
                                        <x-badge :type="$badgeType">{{ $sale_return->status->getName() }}</x-badge>
                                    </x-table.td>
                                    <x-table.td>{{ format_currency($sale_return->total_amount) }}</x-table.td>
                                    <x-table.td>{{ format_currency($sale_return->paid_amount) }}</x-table.td>
                                    <x-table.td>{{ format_currency($sale_return->due_amount) }}</x-table.td>
                                    <x-table.td>
                                        @php
                                            $type = $sale_return->payment_status->getBadgeType();
                                        @endphp
                                        <x-badge
                                            :type="$type">{{ $sale_return->payment_status->getName() }}</x-badge>

                                    </x-table.td>
                                </x-table.tr>
                            @empty
                                <x-table.tr>
                                    <x-table.td colspan="8">
                                        <span class="text-red-500">{{ __('No Sale Return Data Available!') }}</span>
                                    </x-table.td>
                                </x-table.tr>
                            @endforelse
                        </x-table.tbody>
                    </x-table>
                    <div class="p-4">
                        {{ $this->saleReturns->links() }}
                    </div>
        </div>
    </x-page-container>
</div>
