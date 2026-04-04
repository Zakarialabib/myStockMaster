<div>
    @section('title', __('Quotations'))

    <x-page-container title="{{ __('Quotations List') }}" :show-filters="true">
        <x-slot name="actions">
            @can('quotation_export')
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger" class="inline-flex">
                        <x-button secondary type="button">
                            <i class="fas fa-file-export mr-2"></i>
                            {{ __('Export') }}
                        </x-button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link wire:click="exportAll">
                            <i class="fas fa-file-pdf mr-2"></i> {{ __('PDF') }}
                        </x-dropdown-link>
                        <x-dropdown-link wire:click="downloadAll">
                            <i class="fas fa-file-excel mr-2"></i> {{ __('Excel') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            @endcan
            @can('quotation_create')
                <x-button primary href="{{ route('quotation.create') }}" icon="fas fa-plus">
                    {{ __('Create Quotation') }}
                </x-button>
            @endcan
        </x-slot>

        <x-slot name="filters">
            <x-datatable.filters 
                :per-page="$perPage" 
                :pagination-options="$paginationOptions" 
                :selected-count="count($selected)" 
                :search="$search"
                search-placeholder="{{ __('Search quotations...') }}" 
                wire:model.live.perPage="perPage"
                wire:model.live.search="search" 
                wire:click.deleteSelected="deleteSelected"
                wire:click.resetSelected="resetSelected" 
                :can-delete="auth()->user()->can('quotation_delete')" />
        </x-slot>

        <x-table>
            <x-slot name="thead">
                <x-table.th class="w-12">
                    <input type="checkbox" wire:model.live="selectPage"
                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 dark:checked:bg-blue-600 dark:checked:border-blue-600 dark:focus:ring-blue-500 dark:focus:ring-offset-gray-800" />
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('date')" :direction="$sortBy === 'date' ? $sortDirection : null">
                    {{ __('Date') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('customer_id')" :direction="$sortBy === 'customer_id' ? $sortDirection : null">
                    {{ __('Customer') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('total_amount')" :direction="$sortBy === 'total_amount' ? $sortDirection : null">
                    {{ __('Total') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('status')" :direction="$sortBy === 'status' ? $sortDirection : null">
                    {{ __('Status') }}
                </x-table.th>
                <x-table.th>
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse ($quotations as $quotation)
                    {{-- @dd($quotation); --}}
                    <x-table.tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                        wire:loading.class.delay="opacity-50">
                        <x-table.td>
                            <input type="checkbox" value="{{ $quotation->id }}" wire:model.live="selected"
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 dark:checked:bg-blue-600 dark:checked:border-blue-600 dark:focus:ring-blue-500 dark:focus:ring-offset-gray-800" />
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-gray-400 dark:text-gray-500 mr-3 text-sm"></i>
                                <span
                                    class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $quotation->date }}</span>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center">
                                <i class="fas fa-user-circle text-blue-500 dark:text-blue-400 mr-3 text-lg"></i>
                                <div>
                                    <a href="{{ route('customer.details', $quotation->customer->id) }}"
                                        class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                                        {{ $quotation->customer->name }}
                                    </a>
                                </div>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center">
                                <i
                                    class="fas fa-dollar-sign text-green-500 dark:text-green-400 mr-3 text-sm"></i>
                                <span
                                    class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ format_currency($quotation->total_amount) }}</span>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            @php
                                $badgeType = $quotation->status->getBadgeType();
                            @endphp
                            <x-badge :type="$badgeType">{{ $quotation->status->getName() }}</x-badge>
                        </x-table.td>
                        <x-table.td>
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger" class="inline-flex">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    @can('quotation_sale')
                                        <x-dropdown-link href="{{ route('quotation-sales.create', $quotation) }}">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            {{ __('Make Sale') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('send_quotation_mails')
                                        <x-dropdown-link href="{{ route('quotation.email', $quotation) }}">
                                            <i class="fas fa-envelope mr-2"></i>
                                            {{ __('Send Mail') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('quotation_update')
                                        <x-dropdown-link href="{{ route('quotation.edit', $quotation->id) }}">
                                            <i class="fas fa-edit mr-2"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('quotation_show')
                                        <x-dropdown-link
                                            wire:click="$dispatchTo('quotations.show', 'showModal', { id : {{ $quotation->id }} })"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-eye mr-2"></i>
                                            {{ __('View') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('quotation_delete')
                                        <x-dropdown-link type="button"
                                            wire:click="deleteModal({{ $quotation->id }})">
                                            <i class="fas fa-trash mr-2"></i>
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <x-table.td colspan="6">
                            <div class="flex flex-col items-center justify-center text-center py-12">
                                <div
                                    class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                    <i
                                        class="fas fa-file-invoice text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    {{ __('No quotations found') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                                    {{ __('Get started by creating your first quotation.') }}</p>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table>

        <div class="mt-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    {{ __('Showing') }}
                    <span class="font-medium">{{ $quotations->firstItem() ?? 0 }}</span>
                    {{ __('to') }}
                    <span class="font-medium">{{ $quotations->lastItem() ?? 0 }}</span>
                    {{ __('of') }}
                    <span class="font-medium">{{ $quotations->total() }}</span>
                    {{ __('results') }}
                </div>
                <div class="flex items-center gap-2">
                    {{ $quotations->links() }}
                </div>
            </div>
        </div>
    </x-page-container>

    <livewire:quotations.show />
</div>
