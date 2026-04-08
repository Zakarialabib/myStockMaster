<div>
    <x-page-container title="{{ __('Currency List') }}" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Currency List')]]" :show-filters="true">
        <x-slot name="actions">
            @can('currency_create')
                <x-button variant="primary" icon="fas fa-plus" wire:click="dispatchTo('currency.create', 'createModal')">
                    {{ __('Create Currency') }}
                </x-button>
            @endcan
        </x-slot>

        <x-slot name="filters">
            <x-datatable.filters 
                :per-page="$perPage" 
                :pagination-options="$paginationOptions" 
                :selected-count="$this->selectedCount" 
                :search="$search"
                search-placeholder="{{ __('Search currency...') }}" 
                wire:model.live.perPage="perPage"
                wire:model.live.search="search" 
                wire:click.deleteSelected="deleteSelected"
                wire:click.resetSelected="$set('selected', [])" 
                :can-delete="auth()->user()->can('currency_delete')" 
            />
        </x-slot>

        <x-table>
            <x-slot name="thead">
                <x-table.th class="w-12">
                    <input type="checkbox" wire:model.live="selectPage" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-gray-700" />
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('name')" :direction="$sorts['name'] ?? null">
                    {{ __('Name') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('code')" :direction="$sorts['code'] ?? null">
                    {{ __('Code') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('symbol')" :direction="$sorts['symbol'] ?? null">
                    {{ __('Symbol') }}
                </x-table.th>
                <x-table.th class="text-right">
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse ($currencies as $currency)
                    <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $currency->id }}">
                        <x-table.td class="w-12">
                            <input type="checkbox" value="{{ $currency->id }}" wire:model.live="selected" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-gray-700" />
                        </x-table.td>
                        <x-table.td>
                            {{ $currency->name }}
                        </x-table.td>
                        <x-table.td>
                            {{ $currency->code }}
                        </x-table.td>
                        <x-table.td>
                            {{ $currency->locale }}
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
                                        <x-dropdown-link wire:click="dispatchTo('currency.show', 'showModal', {id : '{{ $currency->id }}'})" wire:loading.attr="disabled">
                                            <i class="fas fa-eye"></i>
                                            {{ __('Show') }}
                                        </x-dropdown-link>
                                        @can('currency_update')
                                            <x-dropdown-link wire:click="dispatchTo('currency.edit', 'editModal',{id : '{{ $currency->id }}'})" wire:loading.attr="disabled">
                                                <i class="fas fa-edit"></i>
                                                {{ __('Edit') }}
                                            </x-dropdown-link>
                                        @endcan
                                        @can('currency_delete')
                                            <x-dropdown-link wire:click="deleteModal( {{ $currency->id }})" wire:loading.attr="disabled">
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
                        <x-table.td colspan="5">
                            <div class="flex items-center justify-center py-12">
                                <span class="dark:text-gray-300">{{ __('No results found') }}</span>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table>

        <!-- Pagination Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-4 mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                @if ($this->selectedCount)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-blue-500 dark:text-blue-400"></i>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $this->selectedCount }}</span>
                            {{ __('of') }} {{ $currencies->total() }} {{ __('entries selected') }}
                        </p>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Showing') }} {{ $currencies->firstItem() ?? 0 }} {{ __('to') }}
                        {{ $currencies->lastItem() ?? 0 }} {{ __('of') }} {{ $currencies->total() }}
                        {{ __('results') }}
                    </p>
                @endif
                <div class="flex justify-center sm:justify-end">
                    {{ $currencies->links() }}
                </div>
            </div>
        </div>
        </x-page-container>

    @livewire('currency.show', ['currency' => $currency])

    @livewire('currency.edit', ['currency' => $currency])

    <livewire:currency.create />
</div>
