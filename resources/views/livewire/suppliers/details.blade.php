<div>
    @section('title', __('Supplier Detail') . '-' . $supplier->name)
    <x-theme.breadcrumb :title="__('Supplier Detail')" :parent="route('suppliers.index')" :parentName="__('Supplier List')" :childrenName="__('Supplier Detail')">
        <h2 class="mb-1 text-2xl font-bold">
            {{ $this->supplier->name }}
        </h2>
    </x-theme.breadcrumb>
    <div class="w-full">
        <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 w-full mb-4">
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                <div>
                    <p class="mb-2 text-lg font-medium text-gray-600">
                        {{ __('Purchases Total') }}
                    </p>
                    <p class="text-3xl sm:text-lg font-bold text-indigo-700">
                        {{ format_currency($this->TotalPurchases) }}
                    </p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                <div>
                    <p class="mb-2 text-lg font-medium text-gray-600">
                        {{ __('Total Payments') }}
                    </p>
                    <p class="text-3xl sm:text-lg font-bold text-indigo-700">
                        {{ format_currency($this->TotalPayments) }}
                    </p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                <div>
                    <p class="mb-2 text-lg font-medium text-gray-600">
                        {{ __('Total Purchase Returns') }}
                    </p>
                    <p class="text-3xl sm:text-lg font-bold text-indigo-700">
                        {{ format_currency($this->TotalPurchaseReturns) }}
                    </p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                <div>
                    <p class="mb-2 text-lg font-medium text-gray-600">
                        {{ __('Due amount') }}
                    </p>
                    <p class="text-3xl sm:text-lg font-bold text-indigo-700">
                        {{ format_currency($this->TotalDue) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <x-theme.accordion title="{{ __('Supplier Purchases') }}">
        <div class="w-full px-2 mb-5">

            <div class="flex flex-wrap justify-center">
                <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
                    <select wire:model.live="perPage" name="perPage"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-auto sm:text-sm border-gray-300 rounded-md focus:outline-none focus:shadow-outline-blue transition duration-150 ease-in-out">
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
                        <x-input wire:model.live="search" placeholder="{{ __('Search') }}" autofocus />
                    </div>
                </div>
            </div>
            <div>
                <x-table>
                    <x-slot name="thead">
                        <x-table.th>
                            <input type="checkbox" wire:model.live="selectPage" />
                        </x-table.th>
                        <x-table.th sortable wire:click="sortingBy('date')" field="date" :direction="$sorts['date'] ?? null">
                            {{ __('Date') }}
                        </x-table.th>
                        <x-table.th sortable wire:click="sortingBy('supplier_id')" field="supplier_id"
                            :direction="$sorts['supplier_id'] ?? null">
                            {{ __('Customer') }}
                        </x-table.th>
                        <x-table.th sortable wire:click="sortingBy('payment_id')" field="payment_id"
                            :direction="$sorts['payment_id'] ?? null">
                            {{ __('Payment status') }}
                        </x-table.th>
                        <x-table.th sortable wire:click="sortingBy('due_amount')" field="due_amount" :direction="$sorts['due_amount'] ?? null">
                            {{ __('Due Amount') }}
                        </x-table.th>
                        <x-table.th sortable wire:click="sortingBy('total')" field="total" :direction="$sorts['total'] ?? null">
                            {{ __('Total') }}
                        </x-table.th>
                        <x-table.th sortable wire:click="sortingBy('status')" field="status" :direction="$sorts['status'] ?? null">
                            {{ __('Status') }}
                        </x-table.th>
                        <x-table.th>
                            {{ __('Actions') }}
                        </x-table.th>
                    </x-slot>

                    <x-table.tbody>
                        {{-- @forelse ($this->purchases as $purchase)
                        <x-table.tr wire:loading.class.delay="opacity-50">
                            <x-table.td>
                                <input type="checkbox" value="{{ $purchase->id }}" wire:model.live="selected" />
                            </x-table.td>
                            <x-table.td>
                                {{ $purchase->date }}
                            </x-table.td>
                            <x-table.td>
                                {{ $purchase->supplier->name }}
                            </x-table.td>
                            <x-table.td>
                                @php
                                    $type = $purchase->payment_id->getBadgeType();
                                @endphp
                                <x-badge :type="$type">{{ $purchase->payment_id->label() }}</x-badge>
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

                                <x-badge :type="$badgeType">{{ $purchase->status->label() }}</x-badge>
                            </x-table.td>
                            <x-table.td>
                                <div class="flex justify-start space-x-2">
                                    <x-dropdown align="right" width="56">
                                        <x-slot name="trigger" class="inline-flex">
                                            <x-button primary type="button" class="text-white flex items-center">
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
                                                <x-dropdown-link href="{{ route('purchase.edit', $purchase) }}"
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
                                    <span class="text-gray-400">{{ __('No results found') }}</span>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse --}}
                    </x-table.tbody>
                </x-table>
            </div>

            <div class="px-6 py-3">
                {{-- {{ $this->purchases->links() }} --}}
            </div>
        </div>

    </x-theme.accordion>

    <x-theme.accordion title="{{ __('Supplier Payments') }}">

        <div class="w-full px-2 mb-5">

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
                                    {{-- @can('purchase_payment_access') --}}
                                    <x-button wire:click="$dispatch('paymentModal', {{ $purchasepayment->id }} )"
                                        type="button" primary>
                                        <i class="fa fa-pen"></i>
                                    </x-button>
                                    {{-- @endcan --}}
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
    </x-theme.accordion>
</div>
