<div>
    @section('title', __('Quotations'))

    <x-page-container title="{{ __('Quotations List') }}">
        <x-slot name="actions">
            @can('quotation_export')
                <x-button wire:click="exportAll" secondary icon="fas fa-file-pdf">
                    {{ __('PDF') }}
                </x-button>
                <x-button wire:click="downloadAll" secondary icon="fas fa-file-excel">
                    {{ __('Excel') }}
                </x-button>
            @endcan
            @can('quotation_create')
                <x-button primary href="{{ route('quotation.create') }}" icon="fas fa-plus">
                    {{ __('Create Quotation') }}
                </x-button>
            @endcan
        </x-slot>

        <x-slot name="filters">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Show') }}</label>
                <x-input.select wire:model.live="perPage" class="w-20">
                    @foreach ($paginationOptions as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </x-input.select>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('entries') }}</span>
            </div>
            
            @if ($selected)
                @can('quotation_delete')
                    <x-button danger wire:click="deleteSelected" icon="fas fa-trash">
                        {{ __('Delete Selected') }}
                    </x-button>
                @endcan
                @can('quotation_export')
                    <x-button success wire:click="downloadSelected" icon="fas fa-file-excel">
                        {{ __('Excel Selected') }}
                    </x-button>
                    <x-button warning wire:click="exportSelected" icon="fas fa-file-pdf">
                        {{ __('PDF Selected') }}
                    </x-button>
                @endcan
                <div class="flex items-center px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg border border-blue-200 dark:border-blue-800">
                    <i class="fas fa-info-circle mr-2 text-xs"></i>
                    <span class="text-sm font-medium">{{ count($selected) }} {{ __('selected') }}</span>
                </div>
            @endif
            
            <x-input.text wire:model.live.debounce.300ms="search" placeholder="{{ __('Search quotations...') }}" icon="fas fa-search" />
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
            <x-slot name="tbody">
                @forelse ($quotations as $quotation)
                    {{-- @dd($quotation); --}}
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
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
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table>

        <x-slot name="pagination">
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
        </x-slot>
    </x-page-container>

    <livewire:quotations.show />
</div>
