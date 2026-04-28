<div>
    <x-page-container title="{{ __('Warehouse Report') }}" :breadcrumbs="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Reports'), 'url' => '#'],
        ['label' => __('Warehouse Report'), 'url' => '#']
    ]" :show-filters="true">

        <x-slot name="filters">
            <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md dark:bg-gray-800 dark:border-blue-500">
                <div class="flex items-start">
                    <div class="shrink-0"><i class="fas fa-info-circle text-blue-400"></i></div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <strong>{{ __('Comprehensive Branch Dashboard:') }}</strong> 
                            {{ __('Select a warehouse to view its total inventory valuation and financial performance over the selected period.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <x-label for="warehouse_id" :value="__('Warehouse')" />
                    <x-select-list :options="$this->warehouses" wire:model.live="warehouse_id" id="warehouse_id" />
                </div>
                <div>
                    <x-label for="start_date" :value="__('Start Date')" />
                    <x-input wire:model.live="start_date" type="date" id="start_date" />
                </div>
                <div>
                    <x-label for="end_date" :value="__('End Date')" />
                    <x-input wire:model.live="end_date" type="date" id="end_date" />
                </div>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-card-tooltip icon="bi bi-box-seam" color="indigo">
                <span class="text-2xl">{{ format_currency($this->stockValue) }}</span>
                <p>{{ __('Total Inventory Value') }}</p>
            </x-card-tooltip>

            <x-card-tooltip icon="bi bi-graph-up-arrow" color="emerald">
                <span class="text-2xl">{{ format_currency($this->totalSales) }}</span>
                <p>{{ __('Total Sales') }}</p>
            </x-card-tooltip>

            <x-card-tooltip icon="bi bi-cart-check" color="blue">
                <span class="text-2xl">{{ format_currency($this->totalPurchases) }}</span>
                <p>{{ __('Total Purchases') }}</p>
            </x-card-tooltip>

            <x-card-tooltip icon="bi bi-receipt" color="rose">
                <span class="text-2xl">{{ format_currency($this->totalExpenses) }}</span>
                <p>{{ __('Total Expenses') }}</p>
            </x-card-tooltip>
        </div>

        <div class="space-y-6">
            <!-- Sales Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Recent Sales') }}</h3>
                </div>
                <x-table>
                    <x-slot name="thead">
                        <x-table.th>{{ __('Date') }}</x-table.th>
                        <x-table.th>{{ __('Reference') }}</x-table.th>
                        <x-table.th>{{ __('Customer') }}</x-table.th>
                        <x-table.th>{{ __('Status') }}</x-table.th>
                        <x-table.th>{{ __('Total') }}</x-table.th>
                    </x-slot>
                    <x-table.tbody>
                        @forelse($this->sales as $sale)
                            <x-table.tr>
                                <x-table.td>{{ $sale->date->format('Y-m-d') }}</x-table.td>
                                <x-table.td>{{ $sale->reference }}</x-table.td>
                                <x-table.td>{{ $sale->customer->name }}</x-table.td>
                                <x-table.td>
                                    <x-badge :type="$sale->status->getBadgeType()">{{ $sale->status->getName() }}</x-badge>
                                </x-table.td>
                                <x-table.td>{{ format_currency($sale->total_amount) }}</x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.tr>
                                <x-table.td colspan="5" class="text-center">{{ __('No sales found.') }}</x-table.td>
                            </x-table.tr>
                        @endforelse
                    </x-table.tbody>
                </x-table>
                <div class="p-4">{{ $this->sales->links() }}</div>
            </div>

            <!-- Purchases Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Recent Purchases') }}</h3>
                </div>
                <x-table>
                    <x-slot name="thead">
                        <x-table.th>{{ __('Date') }}</x-table.th>
                        <x-table.th>{{ __('Reference') }}</x-table.th>
                        <x-table.th>{{ __('Supplier') }}</x-table.th>
                        <x-table.th>{{ __('Status') }}</x-table.th>
                        <x-table.th>{{ __('Total') }}</x-table.th>
                    </x-slot>
                    <x-table.tbody>
                        @forelse($this->purchases as $purchase)
                            <x-table.tr>
                                <x-table.td>{{ $purchase->date->format('Y-m-d') }}</x-table.td>
                                <x-table.td>{{ $purchase->reference }}</x-table.td>
                                <x-table.td>{{ $purchase->supplier->name }}</x-table.td>
                                <x-table.td>
                                    <x-badge :type="$purchase->status->getBadgeType()">{{ $purchase->status->getName() }}</x-badge>
                                </x-table.td>
                                <x-table.td>{{ format_currency($purchase->total_amount) }}</x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.tr>
                                <x-table.td colspan="5" class="text-center">{{ __('No purchases found.') }}</x-table.td>
                            </x-table.tr>
                        @endforelse
                    </x-table.tbody>
                </x-table>
                <div class="p-4">{{ $this->purchases->links() }}</div>
            </div>
        </div>

    </x-page-container>
</div>
