<div>
    @section('title', __('Stock Adjustments'))
    <x-page-container title="{{ __('Stock Adjustments') }}" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Stock Adjustments')]]" :show-filters="true">
        <x-slot name="actions">
            @can('adjustment_create')
                <x-button href="{{ route('adjustments.create') }}" variant="primary" icon="fas fa-plus">
                    {{ __('Create Adjustment') }}
                </x-button>
            @endcan
        </x-slot>

        <x-slot name="filters">
            <x-datatable.filters 
                :per-page="$perPage" 
                :pagination-options="$paginationOptions" 
                :selected-count="$this->selectedCount" 
                :search="$search"
                search-placeholder="{{ __('Search adjustments...') }}" 
                wire:model.live.perPage="perPage"
                wire:model.live.search="search" 
                wire:click.deleteSelected="deleteSelected"
                wire:click.resetSelected="$set('selected', [])" 
                :can-delete="auth()->user()->can('adjustment_delete')" 
            />
        </x-slot>

        <x-table>
            <x-slot name="thead">
                <x-table.th class="w-12">
                    <div class="flex items-center">
                        <input type="checkbox" wire:model.live="selectPage"
                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" />
                    </div>
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('date')" :direction="$sorts['date'] ?? null" class="min-w-32">
                    {{ __('Date') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('reference')" :direction="$sorts['reference'] ?? null" class="min-w-40">
                    {{ __('Reference') }}
                </x-table.th>
                <x-table.th class="min-w-32">
                    {{ __('Type') }}
                </x-table.th>
                <x-table.th class="min-w-32">
                    {{ __('Status') }}
                </x-table.th>
                <x-table.th class="min-w-32">
                    {{ __('Total Items') }}
                </x-table.th>
                <x-table.th class="w-32">
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse($adjustments as $adjustment)
                    <x-table.tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <x-table.td>
                            <div class="flex items-center">
                                <input type="checkbox" value="{{ $adjustment->id }}" wire:model.live="selected"
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" />
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center space-x-3">
                                <div class="shrink-0">
                                    <div
                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 text-sm"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($adjustment->date)->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($adjustment->date)->format('l') }}
                                    </div>
                                </div>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center space-x-3">
                                <div class="shrink-0">
                                    <div
                                        class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-hashtag text-purple-600 dark:text-purple-400 text-sm"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $adjustment->reference }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        ID: {{ $adjustment->id }}
                                    </div>
                                </div>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                <i class="fas fa-adjust mr-1"></i>
                                {{ __('Stock Adjustment') }}
                            </span>
                        </x-table.td>
                        <x-table.td>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ __('Completed') }}
                            </span>
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-boxes text-orange-600 dark:text-orange-400 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $adjustment->adjustmentDetails->count() ?? 0 }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('items') }}
                                    </div>
                                </div>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center space-x-2">
                                @can('adjustment_show')
                                    <x-button
                                        wire:click="$dispatchTo('adjustment.show', 'showModal', { adjustment: {{ $adjustment->id }} })"
                                        variant="info" size="xs" icon="fas fa-eye">
                                        <span class="sr-only">{{ __('View') }}</span>
                                    </x-button>
                                @endcan
                                @can('adjustment_update')
                                    <x-button href="{{ route('adjustments.edit', $adjustment) }}" variant="warning"
                                        size="xs" icon="fas fa-edit">
                                        <span class="sr-only">{{ __('Edit') }}</span>
                                    </x-button>
                                @endcan
                                @can('adjustment_delete')
                                    <x-button wire:click="deleteModal({{ $adjustment->id }})" variant="danger"
                                        size="xs" icon="fas fa-trash">
                                        <span class="sr-only">{{ __('Delete') }}</span>
                                    </x-button>
                                @endcan
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <x-table.td colspan="7">
                            <div class="flex flex-col items-center justify-center py-12">
                                <div
                                    class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-adjust text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    {{ __('No adjustments found') }}</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm">
                                    {{ __('Get started by creating your first stock adjustment.') }}
                                </p>
                                @can('adjustment_create')
                                    <x-button href="{{ route('adjustments.create') }}" variant="primary" class="mt-4"
                                        icon="fas fa-plus">
                                        {{ __('Create Adjustment') }}
                                    </x-button>
                                @endcan
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table>

        <x-slot name="pagination">
            <div class="flex items-center justify-between">
                @if ($this->selectedCount)
                    <p class="text-sm leading-5 text-gray-700 dark:text-gray-300">
                        <span class="font-medium">
                            {{ $this->selectedCount }}
                        </span>
                        {{ __('Entries selected') }}
                    </p>
                @else
                    <div></div>
                @endif
                {{ $adjustments->links() }}
            </div>
        </x-slot>
    </x-page-container>

    @if ($adjustment)
        @livewire('adjustment.show', ['adjustment' => $adjustment])
    @endif

    <!-- Delete Modal -->
    {{-- <div x-data="{ deleteModal: false }" x-on:delete-modal.window="deleteModal = true">
        <x-modal.confirmation wire:model="deleteModal">
            <x-slot name="title">{{ __('Delete Adjustment') }}</x-slot>

            <x-slot name="content">
                <div class="py-8 text-gray-700 dark:text-gray-300">{{ __('Are you sure you want to delete this Adjustment?') }}</div>
            </x-slot>

            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button x-on:click="deleteModal = false" type="button" variant="secondary">{{ __('Cancel') }}</x-button>
                    <x-button type="button" wire:click="delete" variant="danger">{{ __('Delete') }}</x-button>
                </div>
            </x-slot>
        </x-modal.confirmation>
    </div> --}}
</div>
