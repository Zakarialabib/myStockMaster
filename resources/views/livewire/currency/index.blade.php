<div>
    @section('title', __('Currency'))
    <x-theme.breadcrumb :title="__('Currency List')" :parent="route('currencies.index')" :parentName="__('Currency List')">
        @can('currency_create')
            <x-button primary wire:click="dispatchTo('currency.create', 'createModal')">
                {{ __('Create Currency') }}
            </x-button>
        @endcan
    </x-theme.breadcrumb>

    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model.live="perPage"
                class="w-20 block p-3 leading-5 bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($selected)
                <x-button danger wire:click="deleteSelected" class="ml-3">
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
                <x-input wire:model.live.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model.live="selectPage" />
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('code')" :direction="$sorts['code'] ?? null">
                {{ __('Code') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('symbol')" :direction="$sorts['symbol'] ?? null">
                {{ __('Symbol') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($currencies as $currency)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $currency->id }}">
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $currency->id }}" wire:model.live="selected" />
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
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button alert
                                wire:click="dispatchTo('currency.show', 'showModal', {id : '{{ $currency->id }}'})"
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            @can('currency_update')
                                <x-button primary
                                    wire:click="dispatchTo('currency.edit', 'editModal',{id : '{{ $currency->id }}'})"
                                    type="button" wire:loading.attr="disabled">
                                    <i class="fas fa-edit"></i>
                                </x-button>
                            @endcan

                            @can('currency_delete')
                                <x-button danger wire:click="deleteModal( {{ $currency->id }})" type="button"
                                    wire:loading.attr="disabled">
                                    <i class="fas fa-trash"></i>
                                </x-button>
                            @endcan
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="8">
                        <div class="flex items-center justify-center">
                            <span class="dark:text-gray-300">{{ __('No results found') }}</span>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="p-4">
        <div class="pt-3">
            @if ($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $currencies->links() }}
        </div>
    </div>

    @livewire('currency.show', ['currency' => $currency])

    @livewire('currency.edit', ['currency' => $currency])

    <livewire:currency.create />
</div>
