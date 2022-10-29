<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 border border-gray-300 rounded-md shadow-sm py-2 px-4 bg-white text-sm leading-5 font-medium text-gray-700 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            <x-button danger wire:click="deleteSelected" class="ml-3">
                <i class="fas fa-trash"></i>
            </x-button>

        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <div class="flex items-center mr-3 pl-4">
                <input wire:model="search" type="text"
                    class="px-3 py-3 placeholder-gray-400 text-gray-700 bg-white dark:bg-dark-eval-2 rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full pr-10"
                    placeholder="Search..." />
            </div>
        </div>
    </div>

    <div wire:loading.delay>
        <div class="d-flex justify-content-center">
            <x-loading />
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th class="pr-0 w-8">
                <input type="checkbox" class="rounded-tl-md rounded-bl-md" wire:model="selectPage" />
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
                        <input type="checkbox" value="{{ $warehouse->id }}" wire:model="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $warehouse->name }} -{{ $warehouse->phone }}
                    </x-table.td>
                    <x-table.td>
                        {{-- calculate quantity of all products in this category --}}
                        {{ $warehouse->products->sum('pivot.quantity') }}
                    </x-table.td>
                    <x-table.td>
                        {{ $warehouse->products->sum('pivot.quantity') * $warehouse->products->sum('pivot.cost') }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button alert wire:click="editModal({{ $warehouse->id }})" wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$emit('deleteModal', {{ $warehouse->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="4">
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

    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Warehouse') }}
        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" wire:model="warehouse.name"
                            required />
                    </div>
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="phone" :value="__('Phone')" />
                        <x-input id="phone" class="block mt-1 w-full" type="text"
                            wire:model="warehouse.mobile" />
                    </div>
                    <x-accordion title="{{ __('Details') }}" class="flex flex-wrap">
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="email" :value="__('Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="email" wire:model="warehouse.email" />
                    </div>
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text" wire:model="warehouse.city" />
                    </div>
                    <div class="xl:w-1/2 md:w-1/2 px-3 mb-6 md:mb-0">
                        <x-label for="country" :value="__('Country')" />
                        <x-input id="country" class="block mt-1 w-full" type="text"
                            wire:model="warehouse.country" />
                    </div>
                    </x-accordion>
                </div>
                <div class="w-full flex justify-end">
                    <x-button secondary wire:click="$set('editModal', false)">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button primary class="ml-3" wire:click="update">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>

    <livewire:warehouses.create />

</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
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
                        window.livewire.emit('delete', warehouseId)
                    }
                })
            })
        })
    </script>
@endpush
