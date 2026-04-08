<div>
    @section('title', __('Warehouses'))
    <x-page-container title="{{ __('Warehouses') }}" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Warehouses')]]" :show-filters="true">
        <x-slot name="actions">
            @can('warehouse_create')
            <x-button wire:click="dispatchTo('warehouses.create', 'createModal')" variant="primary" icon="fas fa-plus">
                {{ __('Create Warehouse') }}
            </x-button>
            @endcan
        </x-slot>

        <x-slot name="filters">
            <x-datatable.filters
                :per-page="$perPage"
                :pagination-options="$paginationOptions"
                :selected-count="$this->selectedCount"
                :search="$search"
                search-placeholder="{{ __('Search warehouses...') }}"
                wire:model.live.perPage="perPage"
                wire:model.live.search="search"
                wire:click.deleteSelected="deleteSelected"
                wire:click.resetSelected="$set('selected', [])"
                :can-delete="auth()->user()->can('warehouse_delete')" />
        </x-slot>

        <x-table>
            <x-table.thead class="bg-gray-50 dark:bg-gray-800">
                <x-table.tr>
                    <x-table.th>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model.live="selectPage"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="sr-only">{{ __('Select all') }}</label>
                        </div>
                    </x-table.th>
                    <x-table.th>
                        <button wire:click="sortingBy('name')" class="flex items-center space-x-1 text-left font-medium">
                            <span>{{ __('Name') }}</span>
                            @if ($sortBy === 'name')
                            @if ($sortDirection === 'asc')
                            <i class="fas fa-sort-up w-3 h-3"></i>
                            @else
                            <i class="fas fa-sort-down w-3 h-3"></i>
                            @endif
                            @else
                            <i class="fas fa-sort w-3 h-3 opacity-50"></i>
                            @endif
                        </button>
                    </x-table.th>
                    <x-table.th>
                        {{ __('Location') }}
                    </x-table.th>
                    <x-table.th>
                        {{ __('Contact') }}
                    </x-table.th>
                    <x-table.th>
                        {{ __('Status') }}
                    </x-table.th>
                    <x-table.th>
                        <span class="sr-only">{{ __('Actions') }}</span>
                    </x-table.th>
                </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
                @forelse ($warehouses as $warehouse)
                <x-table.tr wire:key="row-{{ $warehouse->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                    <x-table.td class="w-12">
                        <input type="checkbox" value="{{ $warehouse->id }}" wire:model.live="selected"
                            class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-gray-700" />
                    </x-table.td>
                    <x-table.td>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                    <i class="fas fa-warehouse text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $warehouse->name }}
                                </div>
                                @if ($warehouse->code)
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $warehouse->code }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </x-table.td>
                    <x-table.td>
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                {{ $warehouse->city }}@if ($warehouse->country)
                                , {{ $warehouse->country }}
                                @endif
                            </div>
                            @if ($warehouse->address)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ Str::limit($warehouse->address, 30) }}
                            </div>
                            @endif
                        </div>
                    </x-table.td>
                    <x-table.td>
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            @if ($warehouse->phone)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-phone text-gray-400 mr-2 text-xs"></i>
                                {{ $warehouse->phone }}
                            </div>
                            @endif
                            @if ($warehouse->email)
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 mr-2 text-xs"></i>
                                {{ $warehouse->email }}
                            </div>
                            @endif
                        </div>
                    </x-table.td>
                    <x-table.td>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <i class="fas fa-circle text-green-400 mr-1" style="font-size: 6px;"></i>
                            {{ __('Active') }}
                        </span>
                    </x-table.td>
                    <x-table.td class="text-right">
                        <div class="flex justify-start space-x-2">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-button primary type="button" class="text-white flex items-center">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </x-button>
                                </x-slot>
                                <x-slot name="content">
                                    @can('warehouse_update')
                                        <x-dropdown-link wire:click="dispatchTo('warehouses.edit', 'editModal', { warehouse: {{ $warehouse->id }} })">
                                            <i class="fas fa-edit"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('warehouse_delete')
                                        <x-dropdown-link wire:click="delete({{ $warehouse->id }})" wire:confirm="{{ __('Are you sure you want to delete this record?') }}">
                                            <i class="fas fa-trash"></i>
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.tr>
                    <x-table.td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-warehouse text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                {{ __('No warehouses found') }}
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">
                                {{ __('Get started by creating your first warehouse.') }}
                            </p>
                            @can('warehouse_create')
                            <x-button wire:click="dispatchTo('warehouses.create', 'createModal')"
                                variant="primary" icon="fas fa-plus">
                                {{ __('Create Warehouse') }}
                            </x-button>
                            @endcan
                        </div>
                    </x-table.td>
                </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table>

        <x-slot name="pagination">
            {{ $warehouses->links() }}
        </x-slot>

    </x-page-container>

    <livewire:warehouses.edit :warehouse="$warehouse" />

    <livewire:warehouses.create />

</div>