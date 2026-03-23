<div>
    @section('title', __('Brands List'))
    <x-page-container title="{{ __('Brands List') }}" :breadcrumbs="[['name' => __('Brands'), 'url' => route('brands.index')]]">

        <x-slot name="actions">
            <x-dropdown align="right" width="48" class="w-auto mr-3">
                <x-slot name="trigger" class="inline-flex">
                    <x-button variant="secondary" icon="fas fa-download">
                        {{ __('Import') }}
                        <i class="fas fa-chevron-down w-4 h-4 ml-2 -mr-1"></i>
                    </x-button>
                </x-slot>
                <x-slot name="content">
                    @can('brand_import')
                        <x-dropdown-link wire:click="importModal" wire:loading.attr="disabled"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-file-import w-4 h-4 mr-3 text-blue-500"></i>
                            {{ __('Import') }}
                        </x-dropdown-link>
                    @endcan
                </x-slot>
            </x-dropdown>

            @can('brand_create')
                <x-button wire:click="dispatchTo('brands.create', 'createModal')" icon="fas fa-plus">
                    {{ __('Create Brand') }}
                </x-button>
            @endcan
        </x-slot>
        <x-slot name="filters">
            <div class="flex items-center gap-2">
                <label for="perPage"
                    class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Show') }}</label>
                <x-input.select wire:model.live="perPage" id="perPage" class="w-20">
                    @foreach ($paginationOptions as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </x-input.select>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('entries') }}</span>
            </div>

            @can('brand_delete')
                @if ($this->selected)
                    <x-button wire:click="deleteSelected" variant="danger" icon="fas fa-trash" size="sm">
                        {{ __('Delete Selected') }}
                    </x-button>
                @endif
            @endcan

            @if ($this->selectedCount)
                <div
                    class="flex items-center px-3 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <i class="fas fa-info-circle w-4 h-4 mr-2 text-blue-500 dark:text-blue-400"></i>
                    <span class="text-sm text-blue-700 dark:text-blue-300">
                        <span class="font-medium">{{ $this->selectedCount }}</span>
                        {{ __('Entries selected') }}
                    </span>
                </div>
            @endif

            <x-input.text wire:model.live.debounce.500ms="search" placeholder="{{ __('Search brands...') }}"
                icon="fas fa-search" class="w-full sm:w-80" autofocus />
        </x-slot>

        <x-table>
            <x-slot name="thead">
                <x-table.th class="w-12">
                    <input wire:model.live="selectPage" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2" />
                </x-table.th>
                <x-table.th sortable wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" icon="fas fa-tag">
                    {{ __('Name') }}
                </x-table.th>
                <x-table.th icon="fas fa-align-left">
                    {{ __('Description') }}
                </x-table.th>
                <x-table.th icon="fas fa-cogs">
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>
            <x-slot name="tbody">
                @forelse($brands as $brand)
                    <x-table.tr>
                        <x-table.td>
                            <input type="checkbox" value="{{ $brand->id }}" wire:model.live="selected"
                                class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2">
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center">
                                <div
                                    class="shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-tag text-blue-600 dark:text-blue-400 text-sm"></i>
                                </div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $brand->name }}
                                </div>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs">
                                <p class="truncate" title="{{ $brand->description }}">
                                    {{ Str::limit($brand->description, 50, '...') }}
                                </p>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center space-x-2">
                                @can('brand_update')
                                    <x-button
                                        wire:click="$dispatchTo('brands.edit','editModal', { id: '{{ $brand->id }}' } )"
                                        variant="info" size="xs" icon="fas fa-edit">
                                        {{ __('Edit') }}
                                    </x-button>
                                @endcan
                                @can('brand_delete')
                                    <x-button wire:click="deleteModal('{{ $brand->id }}')"
                                        variant="danger" size="xs" icon="fas fa-trash">
                                        {{ __('Delete') }}
                                    </x-button>
                                @endcan
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <x-table.td colspan="4" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-tags w-12 h-12 text-gray-400 dark:text-gray-500 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    {{ __('No brands found') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Get started by creating a new brand.') }}</p>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @endforelse
            </x-slot>
        </x-table>

        <x-slot name="pagination">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                @if ($brands->total() > 0)
                    <span>{{ __('Showing') }}</span>
                    <span class="font-medium">{{ $brands->firstItem() }}</span>
                    <span>{{ __('to') }}</span>
                    <span class="font-medium">{{ $brands->lastItem() }}</span>
                    <span>{{ __('of') }}</span>
                    <span class="font-medium">{{ $brands->total() }}</span>
                    <span>{{ __('results') }}</span>
                @endif
            </div>
            <div>
                {{ $brands->links() }}
            </div>
        </x-slot>

    </x-page-container>


    @livewire('brands.show', ['brand' => $brand])
    @livewire('brands.edit', ['brand' => $brand])
    <livewire:brands.create />

    <!-- Import modal -->
    <x-modal wire:model.live="importModal">
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
                <div class="mb-4">
                    <div class="my-4">
                        <x-label for="file" :value="__('Import')" />
                        <x-input id="file" class="block mt-1 w-full" type="file" name="file"
                            wire:model="file" />
                        <x-input-error :messages="$errors->get('file')" for="file" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-start">
                        <x-button primary wire:click="import" type="button" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
