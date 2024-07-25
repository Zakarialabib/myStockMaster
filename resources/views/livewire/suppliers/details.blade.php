<div>
    <div class="container px-4 mx-auto">
        <div class="w-full">
            <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 w-full">
                <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                    <div>
                        <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                            {{ __('Purchases Total') }}
                        </p>
                        <p class="text-3xl sm:text-lg font-bold text-indigo-700 dark:text-indigo-600">
                            {{ format_currency($this->TotalPurchases) }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                    <div>
                        <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                            {{ __('Total Payments') }}
                        </p>
                        <p class="text-3xl sm:text-lg font-bold text-indigo-700 dark:text-indigo-600">
                            {{ format_currency($this->TotalPayments) }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                    <div>
                        <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                            {{ __('Total Purchase Returns') }}
                        </p>
                        <p class="text-3xl sm:text-lg font-bold text-indigo-700 dark:text-indigo-600">
                            {{ format_currency($this->TotalPurchaseReturns) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                    <div>
                        <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                            {{ __('Due amount') }}
                        </p>
                        <p class="text-3xl sm:text-lg font-bold text-indigo-700 dark:text-indigo-600">
                            {{ format_currency($this->Debit) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full mx-auto">
            <div class="w-full px-2 my-5">
                <h2 class="my-5 text-2xl font-bold">
                    {{ __('Purchases') }}
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
                            <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
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
                            <x-input wire:model.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
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
                            <x-table.th sortable multi-column wire:click="sortBy('supplier_id')" :direction="$sorts['supplier_id'] ?? null">
                                {{ __('Customer') }}
                            </x-table.th>
                            <x-table.th sortable multi-column wire:click="sortBy('payment_status')" :direction="$sorts['payment_status'] ?? null">
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
                            @forelse ($this->purchases as $purchase)
                                <x-table.tr wire:loading.class.delay="opacity-50">
                                    <x-table.td>
                                        <input type="checkbox" value="{{ $purchase->id }}" wire:model="selected" />
                                    </x-table.td>
                                    <x-table.td>
                                        {{ $purchase->date }}
                                    </x-table.td>
                                    <x-table.td>
                                        {{ $purchase->supplier->name }}
                                    </x-table.td>
                                    <x-table.td>
                                        @php
                                            $type = $purchase->payment_status->getBadgeType();
                                        @endphp
                                        <x-badge :type="$type">{{ $purchase->payment_status->getName() }}</x-badge>
                                    </x-table.td>
                                    <x-table.td>
                                        {{ format_currency($purchase->due_amount) }}
                                    </x-table.td>

                                    <x-table.td>
                                        {{ format_currency($purchase->total_amount) }}
                                    </x-table.td>

                                    <x-table.td>
                                        @php
                                            $badgeType = $purchase->status->getBadgeType();
                                        @endphp

                                        <x-badge :type="$badgeType">{{ $purchase->status->getName() }}</x-badge>
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
                                                    <x-dropdown-link wire:click="showModal({{ $purchase->id }})"
                                                        wire:loading.attr="disabled">
                                                        <i class="fas fa-eye"></i>
                                                        {{ __('View') }}
                                                    </x-dropdown-link>

                                                    @can('purchase_update')
                                                        <x-dropdown-link href="{{ route('purchases.edit', $purchase) }}"
                                                            wire:loading.attr="disabled">
                                                            <i class="fas fa-edit"></i>
                                                            {{ __('Edit') }}
                                                        </x-dropdown-link>
                                                    @endcan

                                                    <x-dropdown-link target="_blank"
                                                        href="{{ route('purchases.pdf', $purchase->id) }}"
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
                    {{ $this->purchases->links() }}
                </div>

            </div>
            <div class="w-full px-2 mb-5">

                <h2 class="my-5 text-2xl font-bold">
                    {{ __('Payments') }}
                </h2>
                <x-table>
                    <x-slot name="thead">
                        <x-table.th>{{ __('Date') }}</x-table.th>
                        <x-table.th>{{ __('Reference') }}</x-table.th>
                        <x-table.th>{{ __('Amount') }}</x-table.th>
                        <x-table.th>{{ __('Due Amount') }}</x-table.th>
                        <x-table.th>{{ __('Payment Method') }}</x-table.th>
                        <x-table.th>{{ __('Actions') }}</x-table.th>
                    </x-slot>
                    <x-table.tbody>
                        @foreach ($this->supplierPayments as $supplierPayment)
                            @forelse ($supplierPayment->purchasepayments as $purchasepayment)
                                <x-table.tr>
                                    <x-table.td>{{ $purchasepayment->created_at }}</x-table.td>
                                    <x-table.td>{{ $purchasepayment->purchase->reference }}</x-table.td>
                                    <x-table.td>
                                        {{ format_currency($purchasepayment->amount) }}
                                    </x-table.td>
                                    <x-table.td>
                                        {{ format_currency($purchasepayment->purchase->due_amount) }}
                                    </x-table.td>
                                    <x-table.td>{{ $purchasepayment->payment_method }}</x-table.td>
                                    <x-table.td>
                                        @can('access_purchase_payments')
                                            <x-button wire:click="$emit('paymentModal', {{ $purchasepayment->id }} )"
                                                type="button" primary>
                                                <i class="fa fa-pen"></i>
                                            </x-button>
                                        @endcan
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
                    {{ $this->supplierPayments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
