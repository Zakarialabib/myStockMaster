<div>

    <x-page-container title="{{ __('Brands List') }}" :breadcrumbs="[['name' => __('Brands'), 'url' => route('brands.index')]]">

        <x-slot name="actions">
            <div class="flex justify-end space-x-2">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger" class="inline-flex">
                        <x-button primary type="button" class="text-white flex items-center">
                            <i class="fas fa-ellipsis-v"></i>
                        </x-button>
                    </x-slot>
                    <x-slot name="content">
                        @can('brand_import')
                        <x-dropdown-link wire:click="importModal" wire:loading.attr="disabled">
                            <i class="fas fa-file-import"></i>
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
            </div>
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
                <x-table.th sortable wire:click="sortingBy('name')" :direction="$sorts['name'] ?? null" icon="fas fa-tag">
                    {{ __('Name') }}
                </x-table.th>
                <x-table.th icon="fas fa-align-left">
                    {{ __('Description') }}
                </x-table.th>
                <x-table.th icon="fas fa-cogs">
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse($brands as $brand)

                <x-table.tr wire:key="row-{{ $brand->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <x-table.td>
                        <input type="checkbox" value="{{ $brand->id }}" wire:model.live="selected"
                            class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2">
                    </x-table.td>
                    <x-table.td>
                        <div class="flex items-center">
                            <div class="shrink-0 w-8 h-8 rounded-full overflow-hidden mr-3">
                                @if($brand->image)
                                    <img src="{{ asset('images/brands/' . $brand->image) }}" class="w-full h-full object-cover" alt="{{ $brand->name }}">
                                @else
                                    <div class="w-full h-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <i class="fas fa-tag text-blue-600 dark:text-blue-400 text-sm"></i>
                                    </div>
                                @endif
                            </div>
                            <span class="text-sm font-medium text-blue-700 hover:text-blue-500 cursor-pointer"
                                wire:click="$dispatchTo('brands.show','showModal', { id: '{{ $brand->id }}' } )">
                                {{ $brand->name }}
                            </span>
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
                        <div class="flex justify-start space-x-2">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-button primary type="button" class="text-white flex items-center">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </x-button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link wire:click="$dispatchTo('brands.show','showModal', { id: '{{ $brand->id }}' } )" wire:loading.attr="disabled">
                                        <i class="fas fa-eye"></i>
                                        {{ __('Show') }}
                                    </x-dropdown-link>
                                    @can('brand_update')
                                    <x-dropdown-link wire:click="$dispatchTo('brands.edit','editModal', { id: '{{ $brand->id }}' } )" wire:loading.attr="disabled">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                    @endcan
                                    @can('brand_delete')
                                    <x-dropdown-link wire:click="deleteModal('{{ $brand->id }}')" wire:loading.attr="disabled">
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
                    <x-table.td colspan="4" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-tags w-12 h-12 text-gray-400 dark:text-gray-500 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                {{ __('No brands found') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Get started by creating a new brand.') }}
                            </p>
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
                            {{ __('of') }} {{ $brands->total() }} {{ __('entries selected') }}
                        </p>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Showing') }} {{ $brands->firstItem() ?? 0 }} {{ __('to') }}
                        {{ $brands->lastItem() ?? 0 }} {{ __('of') }} {{ $brands->total() }}
                        {{ __('results') }}
                    </p>
                @endif
                <div class="flex justify-center sm:justify-end">
                    {{ $brands->links() }}
                </div>
            </div>
        </div>
        </x-page-container>


    @livewire('brands.show', ['brand' => $brand])
    @livewire('brands.edit', ['brand' => $brand])
    @livewire('brands.delete', ['brand' => $brand])
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