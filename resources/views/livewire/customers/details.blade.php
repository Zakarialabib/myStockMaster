<div>
    <div class="container mx-auto">
        <x-card>
            <h2 class="my-5 text-2xl font-bold">
                {{ __('Stats') }}
            </h2>
            <div class="w-full flex flex-wrap align-center mb-4">
                <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-5 xl:grid-cols-5 w-full">
                    <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                        <div>
                            <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                                {{ __('Sales Total') }}
                            </p>
                            <p class="text-3xl sm:text-lg font-bold text-gray-700 dark:text-gray-300">
                                {{ format_currency($this->totalSales) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                        <div>
                            <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                                {{ __('Total Payments') }}
                            </p>
                            <p class="text-3xl sm:text-lg font-bold text-gray-700 dark:text-gray-300">
                                {{ format_currency($this->totalPayments) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                        <div>
                            <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                                {{ __('Total Sale Returns') }}
                            </p>
                            <p class="text-3xl sm:text-lg font-bold text-gray-700 dark:text-gray-300">
                                {{ format_currency($this->totalSaleReturns) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                        <div>
                            <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                                {{ __('Total Due') }}
                            </p>
                            <p class="text-3xl sm:text-lg font-bold text-gray-700 dark:text-gray-300">
                                {{ format_currency($this->totalDue) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                        <div>
                            <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                                {{ __('Profit') }}
                            </p>
                            <p class="text-3xl sm:text-lg font-bold text-gray-700 dark:text-gray-300">
                                {{ format_currency($this->profit) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
    <div class="container mx-auto">
        <div class="w-full flex">
            <x-card>
                <div class="w-full px-2">
                    <h2 class="my-5 text-2xl font-bold">
                        {{ __('Sales') }}
                    </h2>
                    <div class="flex flex-wrap justify-center">
                        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
                            <select wire:model="perPage"
                                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                                @foreach ($paginationOptions as $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($selected)
                                <x-button danger type="button" wire:click="$toggle('showDeleteModal')"
                                    wire:loading.attr="disabled">
                                    <i class="fas fa-trash"></i>
                                </x-button>
                            @endif
                            @if ($this->selectedCount)
                                <p class="text-sm leading-5">
                                    <span class="font-medium">
                                        {{ $this->selectedCount }}
                                    </span>
                                    {{ __('Entries selected') }}
                                </p>
                            @endif
                        </div>
                        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
                            <div class="my-2">
                                <input type="text" wire:model.debounce.300ms="search"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                    placeholder="{{ __('Search') }}" />
                            </div>
                        </div>
                    </div>
                    <div>
                        <x-table>
                            <x-slot name="thead">
                                <x-table.th>
                                    <input type="checkbox" wire:model="selectPage" />
                                </x-table.th>
                                <x-table.th sortable multi-column wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                                    {{ __('Date') }}
                                </x-table.th>
                                <x-table.th sortable multi-column wire:click="sortBy('customer_id')" :direction="$sorts['customer_id'] ?? null">
                                    {{ __('Customer') }}
                                </x-table.th>
                                <x-table.th sortable multi-column wire:click="sortBy('payment_status')"
                                    :direction="$sorts['payment_status'] ?? null">
                                    {{ __('Payment status') }}
                                </x-table.th>
                                <x-table.th sortable multi-column wire:click="sortBy('due_amount')" :direction="$sorts['due_amount'] ?? null">
                                    {{ __('Due Amount') }}
                                </x-table.th>
                                <x-table.th sortable multi-column wire:click="sortBy('total')" :direction="$sorts['total'] ?? null">
                                    {{ __('Total') }}
                                </x-table.th>
                                <x-table.th sortable multi-column wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
                                    {{ __('Status') }}
                                </x-table.th>
                                <x-table.th>
                                    {{ __('Actions') }}
                                </x-table.th>
                            </x-slot>

                            <x-table.tbody>
                                @forelse ($this->sales as $sale)
                                    <x-table.tr wire:loading.class.delay="opacity-50">
                                        <x-table.td>
                                            <input type="checkbox" value="{{ $sale->id }}" wire:model="selected" />
                                        </x-table.td>
                                        <x-table.td>
                                            {{ $sale->date }}
                                        </x-table.td>
                                        <x-table.td>
                                            {{ $sale->customer->name }}
                                        </x-table.td>
                                        <x-table.td>
                                            @if ($sale->payment_status == \App\Enums\PaymentStatus::Paid)
                                                <x-badge success>{{ __('Paid') }}</x-badge>
                                            @elseif ($sale->payment_status == \App\Enums\PaymentStatus::Partial)
                                                <x-badge warning>{{ __('Partially Paid') }}</x-badge>
                                            @elseif($sale->payment_status == \App\Enums\PaymentStatus::Due)
                                                <x-badge danger>{{ __('Due') }}</x-badge>
                                            @endif
                                        </x-table.td>
                                        <x-table.td>
                                            {{ format_currency($sale->due_amount) }}
                                        </x-table.td>

                                        <x-table.td>
                                            {{ format_currency($sale->total_amount) }}
                                        </x-table.td>

                                        <x-table.td>
                                            @if ($sale->status == \App\Enums\SaleStatus::Pending)
                                                <x-badge warning>{{ __('Pending') }}</x-badge>
                                            @elseif ($sale->status == \App\Enums\SaleStatus::Ordered)
                                                <x-badge info>{{ __('Ordered') }}</x-badge>
                                            @elseif($sale->status == \App\Enums\SaleStatus::Completed)
                                                <x-badge success>{{ __('Completed') }}</x-badge>
                                            @endif
                                        </x-table.td>
                                        <x-table.td>
                                            <div class="flex justify-start space-x-2">
                                                <x-dropdown align="right" width="56">
                                                    <x-slot name="trigger" class="inline-flex">
                                                        <x-button primary type="button"
                                                            class="text-white flex items-center">
                                                            <i class="fas fa-angle-double-down"></i>
                                                        </x-button>
                                                    </x-slot>

                                                    <x-slot name="content">
                                                        <x-dropdown-link wire:click="showModal({{ $sale->id }})"
                                                            wire:loading.attr="disabled">
                                                            <i class="fas fa-eye"></i>
                                                            {{ __('View') }}
                                                        </x-dropdown-link>

                                                        @can('edit_sales')
                                                            <x-dropdown-link href="{{ route('sales.edit', $sale) }}"
                                                                wire:loading.attr="disabled">
                                                                <i class="fas fa-edit"></i>
                                                                {{ __('Edit') }}
                                                            </x-dropdown-link>
                                                        @endcan

                                                        <x-dropdown-link target="_blank"
                                                            href="{{ route('sales.pos.pdf', $sale->id) }}"
                                                            wire:loading.attr="disabled">
                                                            <i class="fas fa-print"></i>
                                                            {{ __('Print') }}
                                                        </x-dropdown-link>

                                                    </x-slot>
                                                </x-dropdown>
                                            </div>
                                        </x-table.td>
                                    </x-table.tr>
                                @empty
                                    <x-table.tr>
                                        <x-table.td>
                                            <div class="flex justify-center items-center">
                                                <span
                                                    class="text-gray-400 dark:text-gray-300">{{ __('No results found') }}</span>
                                            </div>
                                        </x-table.td>
                                    </x-table.tr>
                                @endforelse
                            </x-table.tbody>
                        </x-table>
                    </div>

                    <div class="px-6 py-3">
                        {{ $this->sales->links() }}
                    </div>
                </div>
            </x-card>
            <x-card>
                <div class="w-full px-2">
                    <h2 class="my-5 text-2xl font-bold">
                        {{ __('Payments') }}
                    </h2>
                    <x-table>
                        <x-slot name="thead">
                            <x-table.th>{{ __('Date') }}</x-table.th>
                            <x-table.th>{{ __('Amount') }}</x-table.th>
                            <x-table.th>{{ __('Due Amount') }}</x-table.th>
                            <x-table.th>{{ __('Payment Method') }}</x-table.th>
                            <x-table.th>{{ __('Actions') }}</x-table.th>
                        </x-slot>
                        <x-table.tbody>
                            @foreach ($this->customerPayments as $customerPayment)
                                @forelse ($customerPayment->salepayments as $salepayment)
                                    <x-table.tr>
                                        <x-table.td>{{ $salepayment->created_at }}</x-table.td>
                                        <x-table.td>
                                            {{ format_currency($salepayment->amount) }}
                                        </x-table.td>
                                        <x-table.td>
                                            {{ format_currency($salepayment->sale->due_amount) }}
                                        </x-table.td>
                                        <x-table.td>{{ $salepayment->payment_method }}</x-table.td>
                                        <x-table.td>
                                            @can('access_sale_payments')
                                                <x-button wire:click="$emit('paymentModal', {{ $salepayment->id }} )"
                                                    type="button" primary>
                                                    {{ __('Edit') }}
                                                </x-button>
                                                <a href="{{ route('sale-payments.edit', [$salepayment->sale->id, $salepayment->id]) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                            {{-- <x-button wire:click="delete({{ $salepayment->id }})"
                                        class="bg-red-500 hover:bg-red-700">
                                        {{ __('Delete') }}
                                    </x-button> --}}
                                        </x-table.td>
                                    </x-table.tr>
                                @empty
                                    <x-table.tr>
                                        <x-table.td colspan="3">{{ __('No data found') }}</x-table.td>
                                    </x-table.tr>
                                @endforelse
                            @endforeach
                        </x-table.tbody>
                    </x-table>

                    <div class="mt-4">
                        {{ $this->customerPayments->links() }}
                    </div>

                </div>
            </x-card>
        </div>
    </div>
</div>
