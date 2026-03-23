<div>
    @section('title', __('Purchases'))

    <x-page-container>
        <x-slot name="header">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ __('Purchases List') }}</h1>
        </x-slot>

        <x-slot name="actions">
            @can('purchase_create')
                <x-button href="{{ route('purchase.create') }}" primary>
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Create Purchase order') }}
                </x-button>
            @endcan
        </x-slot>
        <x-slot name="filters">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <x-input.select wire:model.live="perPage" :label="__('Show')">
                    @foreach ($paginationOptions as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </x-input.select>

                <x-input.text wire:model.live.debounce.500ms="search" :placeholder="__('Search purchases...')" icon="fas fa-search" />

                <x-input.date wire:model.live="startDate" :label="__('From Date')" />

                <x-input.date wire:model.live="endDate" :label="__('To Date')" />

                <x-input.select wire:model.live="paymentStatus" :label="__('Payment Status')">
                    <option value="">{{ __('All') }}</option>
                    <option value="1">{{ __('Paid') }}</option>
                    <option value="2">{{ __('Partial') }}</option>
                    <option value="3">{{ __('Due') }}</option>
                </x-input.select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                <x-input.select wire:model.live="supplier_id" :label="__('Supplier')">
                    <option value="">{{ __('All') }}</option>
                    @foreach ($this->suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </x-input.select>
            </div>

            <div class="flex flex-wrap gap-2 mt-4">
                <x-button type="button" primary wire:click="filterByType('day')">{{ __('Today') }}</x-button>
                <x-button type="button" info wire:click="filterByType('month')">{{ __('This Month') }}</x-button>
                <x-button type="button" warning wire:click="filterByType('year')">{{ __('This Year') }}</x-button>
            </div>

            @if ($selected)
                <div class="flex items-center space-x-4 mt-4">
                    <x-button type="button" wire:click="deleteSelected" danger>
                        <i class="fas fa-trash mr-2"></i>
                        {{ __('Delete Selected') }}
                    </x-button>
                    @if ($this->selectedCount)
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex items-center px-3 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span class="text-sm font-medium">{{ $this->selectedCount }}
                                    {{ __('Entries selected') }}</span>
                            </div>
                            <x-button wire:click="resetSelected" type="button" secondary>
                                {{ __('Clear Selected') }}
                            </x-button>
                        </div>
                    @endif
                </div>
            @endif
        </x-slot>
        <x-table>
            <x-slot name="thead">
                <x-table.th>
                    <input type="checkbox" wire:model.live="selectPage"
                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:focus:ring-blue-600 dark:focus:ring-opacity-50">
                </x-table.th>
                <x-table.th sortable wire:click="sortBy('reference')" :direction="$sortBy === 'reference' ? $sortDirection : null">
                    {{ __('Reference') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortBy('date')" :direction="$sortBy === 'date' ? $sortDirection : null">
                    {{ __('Date') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortBy('supplier_id')" :direction="$sortBy === 'supplier_id' ? $sortDirection : null">
                    {{ __('Supplier') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortBy('payment_status')" :direction="$sortBy === 'payment_status' ? $sortDirection : null">
                    {{ __('Payment status') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortBy('total_amount')" :direction="$sortBy === 'total_amount' ? $sortDirection : null">
                    {{ __('Total') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortBy('due_amount')" :direction="$sortBy === 'due_amount' ? $sortDirection : null">
                    {{ __('Due amount') }}
                </x-table.th>
                <x-table.th>
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse ($purchases as $purchase)
                    <x-table.tr wire:key="row-{{ $purchase->id }}">
                        <x-table.td>
                            <input type="checkbox" value="{{ $purchase->id }}" wire:model.live="selected"
                                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800">
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center">
                                <div class="shrink-0 h-10 w-10">
                                    <div
                                        class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <i class="fas fa-hashtag text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $purchase->reference }}
                                    </div>
                                </div>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            {{ format_date($purchase->date) }}
                        </x-table.td>
                        <x-table.td>
                            @if ($purchase->supplier)
                                <a class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium transition-colors"
                                    href="{{ route('supplier.details', $purchase->supplier->id) }}">
                                    {{ $purchase->supplier->name }}
                                </a>
                            @else
                                <span
                                    class="text-sm text-gray-900 dark:text-gray-100">{{ $purchase->supplier->name }}</span>
                            @endif
                        </x-table.td>
                        <x-table.td>
                            {{-- @php
                            $badgeType = $purchase->status->getBadgeType();
                        @endphp --}}
                            {{-- <x-badge :type="$badgeType"> --}}
                            {{ $purchase->payment_id }}
                            {{-- {{ \app\Enums\PaymentStatus::getName($purchase->payment_id) }} --}}
                            {{-- </x-badge> --}}
                        </x-table.td>
                        <x-table.td>
                            {{ format_currency($purchase->total_amount) }}
                        </x-table.td>
                        <x-table.td>
                            {{ format_currency($purchase->due_amount) }}
                        </x-table.td>
                        <x-table.td>
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-button primary type="button" class="text-white flex items-center">
                                        <i class="fas fa-angle-double-down"></i>
                                    </x-button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('purchase_payment_access')
                                        <x-dropdown-link
                                            wire:click="$dispatchTo('purchase.payment.index', 'showPayments', { id: {{ $purchase->id }} })"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ __('Payments') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('purchase_payment_access')
                                        @if ($purchase->due_amount > 0)
                                            <x-dropdown-link
                                                wire:click="$dispatchTo('purchase.payment-form', 'paymentModal', { id: {{ $purchase->id }} })"
                                                wire:loading.attr="disabled">
                                                <i class="fas fa-money-bill-wave"></i>
                                                {{ __('Add Payment') }}
                                            </x-dropdown-link>
                                        @endif
                                    @endcan

                                    @can('purchase_access')
                                        <x-dropdown-link
                                            wire:click="$dispatchTo('purchase.show', 'showModal', { id: {{ $purchase->id }} })"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-eye"></i>
                                            {{ __('View') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('purchase_update')
                                        <x-dropdown-link href="{{ route('purchase.edit', $purchase->id) }}"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-edit"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <x-dropdown-link target="_blank" href="{{ route('purchases.pdf', $purchase->id) }}"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-print"></i>
                                        {{ __('Print') }}
                                    </x-dropdown-link>

                                    @can('purchase_delete')
                                        <x-dropdown-link wire:click="deleteModal({{ $purchase->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-trash"></i>
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <tr>
                        <x-table.td colspan="8">
                            <div class="flex flex-col items-center justify-center py-12">
                                <div
                                    class="flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                    <i class="fas fa-shopping-cart text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    {{ __('No results found') }}</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">
                                    {{ __('Try adjusting your search or filter to find what you\'re looking for.') }}
                                </p>
                            </div>
                        </x-table.td>
                    </tr>
                @endforelse
            </x-table.tbody>
        </x-table>
        <x-slot name="pagination">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300">
                    @if ($this->selectedCount)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            {{ $this->selectedCount }} {{ __('selected') }}
                        </span>
                    @endif
                    <span>{{ __('Showing') }} {{ $purchases->firstItem() ?? 0 }} {{ __('to') }}
                        {{ $purchases->lastItem() ?? 0 }} {{ __('of') }} {{ $purchases->total() }}
                        {{ __('results') }}</span>
                </div>
                <div>
                    {{ $purchases->links() }}
                </div>
            </div>
        </x-slot>
    </x-page-container>

    @livewire('purchase.show', ['purchase' => $purchase])

    @livewire('purchase.payment-form', ['purchase' => $purchase])

    {{-- @if (empty($showPayments))
        <livewire:purchase.payment.index :purchase="$purchase" />
      @endif --}}
</div>
