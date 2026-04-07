<div>    
    <x-page-container title="{{ __('Suppliers') }}" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Suppliers'), 'url' => route('suppliers.index')]]" :show-filters="true">

        <x-slot name="actions">
            <x-dropdown align="right" width="48" class="w-auto mr-2">
                <x-slot name="trigger" class="inline-flex">
                    <x-button secondary type="button" class="text-white flex items-center">
                        <i class="fas fa-angle-double-down w-4 h-4"></i>
                    </x-button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link wire:click="importModal" wire:loading.attr="disabled">
                        {{ __('Excel Import') }}
                    </x-dropdown-link>
                    <x-dropdown-link wire:click="downloadAll" wire:loading.attr="disabled">
                        {{ __('Export Excel') }}
                    </x-dropdown-link>
                    <x-dropdown-link wire:click="exportAll" wire:loading.attr="disabled">
                        {{ __('Export PDF') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
            <x-button primary type="button" wire:click="dispatchTo('suppliers.create', 'showModal')">
                {{ __('Create Supplier') }}
            </x-button>
        </x-slot>

        <x-slot name="filters">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">{{ __('Per Page') }}</label>
                    <x-select wire:model.live="perPage"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-auto sm:text-sm border-gray-300 rounded-md focus:outline-hidden focus:shadow-outline-blue transition duration-150 ease-in-out">
                        @foreach ($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </x-select>
                </div>
                
                <div class="flex-1 max-w-md">
                    <x-input wire:model.live="search" placeholder="{{ __('Search suppliers...') }}" class="w-full" />
                </div>
                
                @if ($selected)
                    <div class="flex items-center gap-2">
                        <x-button danger type="button" wire:click="deleteSelected" size="sm">
                            <i class="fas fa-trash"></i>
                        </x-button>
                        <x-button success type="button" wire:click="downloadSelected" wire:loading.attr="disabled" size="sm">
                            {{ __('EXCEL') }}
                        </x-button>
                        <x-button warning type="button" wire:click="exportSelected" wire:loading.attr="disabled" size="sm">
                            {{ __('PDF') }}
                        </x-button>
                    </div>
                @endif
            </div>
            
            @if ($this->selectedCount)
                <div class="flex items-center justify-between mt-3 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <span class="font-medium">{{ $this->selectedCount }}</span>
                        {{ __('suppliers selected') }}
                    </p>
                    <button wire:click="resetSelected" wire:loading.attr="disabled"
                        class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                        {{ __('Clear Selection') }}
                    </button>
                </div>
            @endif
        </x-slot>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <x-table>
                <x-slot name="thead">
                    <x-table.th class="w-12">
                        <input type="checkbox" wire:model.live="selectPage" 
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    </x-table.th>
                    <x-table.th wire:sort="name" :direction="$sortBy === 'name' ? $sortDirection : null" class="text-left">
                        {{ __('Name') }}
                    </x-table.th>
                    <x-table.th wire:sort="phone" :direction="$sortBy === 'phone' ? $sortDirection : null" class="text-left">
                        {{ __('Phone') }}
                    </x-table.th>
                    <x-table.th wire:sort="address" :direction="$sortBy === 'address' ? $sortDirection : null" class="text-left">
                        {{ __('Address') }}
                    </x-table.th>
                    <x-table.th class="w-24 text-center">
                        {{ __('Actions') }}
                    </x-table.th>
                </x-slot>
                <x-table.tbody>
                    @forelse ($suppliers as $supplier)
                        <x-table.tr wire:key="row-{{ $supplier->id }}" class="hover:bg-gray-50 transition-colors">
                            <x-table.td class="pr-0">
                                <input type="checkbox" wire:model.live="selected" value="{{ $supplier->id }}" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            </x-table.td>
                            <x-table.td class="font-medium">
                                <button type="button" wire:click="$dispatch('showModal', { id : '{{ $supplier->id }}' })" 
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                    {{ $supplier->name }}
                                </button>
                            </x-table.td>
                            <x-table.td class="text-gray-600">
                                {{ $supplier->phone }}
                            </x-table.td>
                            <x-table.td class="text-gray-600">
                                <div class="max-w-xs truncate" title="{{ $supplier->address }}">
                                    {{ $supplier->address }}
                                </div>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-dropdown align="right" width="56">
                                    <x-slot name="trigger" class="inline-flex">
                                        <x-button primary type="button" size="sm" class="text-white flex items-center">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </x-button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link
                                            wire:click="dispatchTo('suppliers.show','showModal', { id : '{{ $supplier->id }}' })"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-eye mr-2"></i>
                                            {{ __('View') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link href="{{ route('supplier.details', $supplier->id) }}">
                                            <i class="fas fa-book mr-2"></i>
                                            {{ __('Details') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link
                                            wire:click="dispatchTo('suppliers.edit', 'showModal', { id : '{{ $supplier->id }}' })"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-edit mr-2"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link
                                            wire:click="delete({{ $supplier->id }})"
                                            wire:confirm="{{ __('Are you sure you want to delete this record?') }}"
                                            wire:loading.attr="disabled"
                                            class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash mr-2"></i>
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="5" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <i class="fas fa-users text-4xl text-gray-300"></i>
                                    <p class="text-gray-500 text-lg font-medium">{{ __('No suppliers found') }}</p>
                                    <p class="text-gray-400 text-sm">{{ __('Get started by creating your first supplier') }}</p>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
            
            @if($suppliers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </x-page-container>

    <livewire:suppliers.show :supplier="$supplier" />

    <livewire:suppliers.edit :supplier="$supplier" />

    <livewire:suppliers.create />

    <x-modal wire:model="importModal" name="importModal">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                {{ __('Import Excel') }}
                <x-button primary wire:click="downloadSample" type="button">
                    {{ __('Download Sample') }}
                </x-button>
            </div>
        </x-slot>

        <x-slot name="content">
            <form wire:submit="import">
                <div class="mb-4 space-y-4">
                    <div class="w-full px-3 mt-4">
                        <x-label for="file" :value="__('Import')" />
                        <x-input id="file" class="block mt-1 w-full" type="file" name="file"
                            wire:model="file" />
                        <x-input-error :messages="$errors->get('file')" for="file" class="mt-2" />
                    </div>

                    <x-table-responsive>
                        <x-table.tr>
                            <x-table.th>{{ __('Name') }}</x-table.th>
                            <x-table.td>{{ __('Required') }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('Phone') }}</x-table.th>
                            <x-table.td>{{ __('Required') }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('Email') }}</x-table.th>
                            <x-table.td>{{ __('Optional') }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('Address') }}</x-table.th>
                            <x-table.td>{{ __('Optional') }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('City') }}</x-table.th>
                            <x-table.td>{{ __('Optional') }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('Tax Number') }}</x-table.th>
                            <x-table.td>{{ __('Optional') }}</x-table.td>
                        </x-table.tr>
                    </x-table-responsive>


                    <div class="w-full flex justify-start">
                        <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
