<div>
    @section('title', __('Warehouses'))

    <x-theme.breadcrumb :title="__('Warehouses List')" :parent="route('warehouses.index')" :parentName="__('Warehouses List')">
        @can('warehouse_create')
            <x-button primary type="button" wire:click="dispatchTo('warehouses.create', 'createModal')">
                {{ __('Create Warehouse') }}
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
                <p wire:click="resetSelected" wire:loading.attr="disabled"
                    class="text-sm leading-5 font-medium text-red-500 cursor-pointer ">
                    {{ __('Clear Selected') }}
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
            <x-table.th>
                {{ __('Name') }}
            </x-table.th>
            <x-table.th>
                {{ __('Products Quantity') }}
            </x-table.th>
            <x-table.th>
                {{ __('Stock Value') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>

            @forelse($warehouses as $warehouse)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $warehouse->id }}">
                    <x-table.td>
                        <input type="checkbox" value="{{ $warehouse->id }}" wire:model.live="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $warehouse->name }} - {{ $warehouse->phone }}
                    </x-table.td>
                    <x-table.td>
                        {{ $warehouse->total_quantity }}
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($warehouse->stock_value) }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button info type="button"
                                wire:click="dispatchTo('warehouses.edit', 'editModal', { id : '{{ $warehouse->id }}'})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger type="button" wire:click="dispatch('deleteModal', {{ $warehouse->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="5">
                        <div class="flex justify-center items-center">
                            <p class="text-gray-500">{{ __('No results found') }}</p>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="mt-4">
        {{ $warehouses->links() }}
    </div>

    <livewire:warehouses.edit :warehouse="$warehouse" />

    <livewire:warehouses.create />

    {{-- @push('scripts')
        <script>
            document.addEventListener('livewire:init', function() {
                window.livewire.on('deleteModal', warehouseId => {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.Livewire.dispatch('delete', warehouseId)
                        }
                    })
                })
            })
        </script>
    @endpush --}}

</div>
