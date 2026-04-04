<div>
    @section('title', __('Currency'))
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
                            <div class="flex items-center justify-end space-x-2">
                                <x-button variant="info" size="xs" icon="fas fa-eye"
                                    wire:click="dispatchTo('currency.show', 'showModal', {id : '{{ $currency->id }}'})"
                                    type="button" wire:loading.attr="disabled" />
                                @can('currency_update')
                                    <x-button variant="primary" size="xs" icon="fas fa-edit"
                                        wire:click="dispatchTo('currency.edit', 'editModal',{id : '{{ $currency->id }}'})"
                                        type="button" wire:loading.attr="disabled" />
                                @endcan

                                @can('currency_delete')
                                    <x-button variant="danger" size="xs" icon="fas fa-trash"
                                        wire:click="deleteModal( {{ $currency->id }})" type="button"
                                        wire:loading.attr="disabled" />
                                @endcan
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

        <x-slot name="pagination">
            {{ $currencies->links() }}
        </x-slot>
    </x-page-container>

    @livewire('currency.show', ['currency' => $currency])

    @livewire('currency.edit', ['currency' => $currency])

    <livewire:currency.create />
</div>
