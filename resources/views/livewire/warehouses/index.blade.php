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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Show') }}</label>
                    <x-input.select wire:model.live="perPage">
                        @foreach ($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </x-input.select>
                </div>
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Search') }}</label>
                    <x-input.text wire:model.live.debounce.500ms="search" placeholder="{{ __('Search warehouses...') }}"
                        icon="fas fa-search" />
                </div>
            </div>
            @if ($selected)
                <div
                    class="flex items-center space-x-2 mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center space-x-2 text-blue-700 dark:text-blue-300">
                        <i class="fas fa-info-circle w-4 h-4"></i>
                        <span class="text-sm font-medium">{{ $this->selectedCount ?? count($selected) }}
                            {{ __('selected') }}</span>
                    </div>
                    @can('warehouse_delete')
                        <x-button wire:click="deleteSelected" variant="danger" size="sm" icon="fas fa-trash">
                            {{ __('Delete Selected') }}
                        </x-button>
                    @endcan
                    <x-button wire:click="$set('selected', [])" variant="secondary" size="sm" icon="fas fa-times">
                        {{ __('Clear Selected') }}
                    </x-button>
                </div>
            @endif
        </x-slot>

        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="w-4 p-4">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model.live="selectPage"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="sr-only">{{ __('Select all') }}</label>
                        </div>
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
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
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('Location') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('Contact') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('Status') }}
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">{{ __('Actions') }}</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($warehouses as $warehouse)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                        <td class="w-4 p-4">
                            <div class="flex items-center">
                                <input type="checkbox" value="{{ $warehouse->id }}" wire:model.live="selected"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label class="sr-only">{{ __('Select warehouse') }}</label>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div
                                        class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <i class="fas fa-circle text-green-400 mr-1" style="font-size: 6px;"></i>
                                {{ __('Active') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                @can('warehouse_show')
                                    <x-button
                                        wire:click="dispatchTo('warehouses.show', 'showModal', { warehouse: {{ $warehouse->id }} })"
                                        variant="secondary" size="sm" icon="fas fa-eye" />
                                @endcan
                                @can('warehouse_update')
                                    <x-button
                                        wire:click="dispatchTo('warehouses.edit', 'editModal', { warehouse: {{ $warehouse->id }} })"
                                        variant="primary" size="sm" icon="fas fa-edit" />
                                @endcan
                                @can('warehouse_delete')
                                    <x-button wire:click="delete({{ $warehouse->id }})" variant="danger"
                                        wire:confirm="{{ __('Are you sure you want to delete this record?') }}"
                                        size="sm" icon="fas fa-trash" />
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-warehouse text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        {{ __('No warehouses found') }}</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                                        {{ __('Get started by creating your first warehouse.') }}</p>
                                    @can('warehouse_create')
                                        <x-button wire:click="dispatchTo('warehouses.create', 'createModal')"
                                            variant="primary" icon="fas fa-plus">
                                            {{ __('Create Warehouse') }}
                                        </x-button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>




            <x-slot name="pagination">
                {{ $warehouses->links() }}
            </x-slot>


        </x-page-container>

        <livewire:warehouses.edit :warehouse="$warehouse" />

        <livewire:warehouses.create />

    </div>
