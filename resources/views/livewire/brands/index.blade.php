<div>
    @section('title', __('Brands List'))

    <x-theme.breadcrumb :title="__('Brands List')" :parent="route('brands.index')" :parentName="__('Brands')">
        <x-dropdown align="right" width="48" class="w-auto mr-2">
            <x-slot name="trigger" class="inline-flex">
                <x-button secondary type="button" class="text-white flex items-center">
                    <i class="fas fa-angle-double-down w-4 h-4"></i>
                </x-button>
            </x-slot>
            <x-slot name="content">
                @can('brand_import')
                    <x-dropdown-link wire:click="importModal" wire:loading.attr="disabled">
                        {{ __('Import') }}
                    </x-dropdown-link>
                @endcan
            </x-slot>
        </x-dropdown>

        @can('brand_create')
            <x-button primary type="button" wire:click="dispatchTo('brands.create', 'createModal')">
                {{ __('Create Brand') }}
            </x-button>
        @endcan
    </x-theme.breadcrumb>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model.live="perPage"
                class="w-20 border border-gray-300 rounded-md shadow-sm py-2 px-4 bg-white text-sm leading-5 font-medium text-gray-700 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @can('brand_delete')
                @if ($this->selected)
                    <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                        <i class="fas fa-trash"></i>
                    </x-button>
                @endif
            @endcan
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
                <input wire:model.live="selectPage" type="checkbox" />
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th>
                {{ __('Description') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
            </tr>
        </x-slot>
        <x-table.tbody>
            @forelse($brands as $brand)
                <x-table.tr>
                    <x-table.td>
                        <input type="checkbox" value="{{ $brand->id }}" wire:model.live="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $brand->name }}
                    </x-table.td>
                    <x-table.td class="whitespace-nowrap break-words">
                        {{ Str::limit($brand->description, 50, '...') }}
                    </x-table.td>

                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            @can('brand_update')
                                <x-button primary
                                    wire:click="dispatchTo('brands.edit','editModal', { id: '{{ $brand->id }}' } )"
                                    type="button" wire:loading.attr="disabled">
                                    <i class="fas fa-edit"></i>
                                </x-button>
                            @endcan
                            @can('brand_delete')
                                <x-button danger wire:click="dispatch('deleteModal',{ id: '{{ $brand->id }}'})"
                                    type="button" wire:loading.attr="disabled">
                                    <i class="fas fa-trash"></i>
                                </x-button>
                            @endcan
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="10" class="text-center">
                        {{ __('No entries found.') }}
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="p-4">
        <div class="pt-3">
            {{ $brands->links() }}
        </div>
    </div>

    @livewire('brands.show', ['brand' => $brand])

    <!-- Edit Modal -->
    @livewire('brands.edit', ['brand' => $brand])
    <!-- End Edit modal -->

    <!-- Create modal -->
    <livewire:brands.create />
    <!-- End Create modal -->

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
    <!-- End Import modal -->

</div>
