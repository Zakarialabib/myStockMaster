<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 border border-gray-300 rounded-md shadow-sm py-2 px-4 bg-white text-sm leading-5 font-medium text-gray-700 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            <button wire:click="deleteSelected"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Delete Selected') }}
            </button>

        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <div class="flex items-center mr-3 pl-4">
                <input wire:model="search" type="text"
                    class="px-3 py-3 placeholder-gray-400 text-gray-700 relative bg-white dark:bg-dark-eval-2 rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full pr-10"
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
        <w-slot name="thead">
            <x-table.th class="pr-0 w-8">
                <input wire:model="selectPage" type="checkbox" />
            </x-table.th>
            <x-table.th>
                {{ __('Name') }}
            </x-table.th>
            <x-table.th>
                {{ __('Description') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
            </tr>
        </w-slot>
        <x-table.tbody>
            @forelese($warehouses as $warehouse)
            <x-table.tr>
                <x-table.td>
                    <input type="checkbox" value="{{ $warehouse->id }}" wire:model="selected">
                </x-table.td>
                <x-table.td>
                    {{ $warehouse->name }}
                </x-table.td>
                <x-table.td>
                    {{ $warehouse->description }}
                </x-table.td>
                <x-table.td>
                    <div class="flex justify-end">
                        <x-button.link wire:click="editModal({{ $warehouse->id }})">
                            {{ __('Edit') }}
                        </x-button.link>
                        <x-button.link wire:click="confirmWarehouseDeletion({{ $warehouse->id }})">
                            {{ __('Delete') }}
                        </x-button.link>
                    </div>
                </x-table.td>
            </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="4">
                        <div class="flex justify-center items-center">
                            <span class="text-gray-400">{{ __('No Warehouses found') }}</span>
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
                    <div class="flex flex-wrap">
                        <div class="xl:w-1/2 lg:w-1/2 md:w-1/2 sm:w-full">
                            <x-input.group label="Name" for="name" :error="$errors->first('editing.name')">
                                <x-input.text wire:model="editing.name" id="name" />
                            </x-input.group>
                        </div>
                        <div class="xl:w-1/2 lg:w-1/2 md:w-1/2 sm:w-full">
                            <x-input.group label="Description" for="description" :error="$errors->first('editing.description')">
                                <x-input.text wire:model="editing.description" id="description" />
                            </x-input.group>
                        </div>
                    </div>
                    <div class="w-full flex justify-end">
                        <x-button.primary wire:click="$set('editModal', false)">
                            {{ __('Cancel') }}
                        </x-button.primary>
                        <x-button.primary wire:click="update">
                            {{ __('Save') }}
                        </x-button.primary>
                    </div>
                </form>
            </x-slot>
        </x-modal>

        <x-modal wire:model="createModal">
            <x-slot name="title">
                {{ __('Create Warehouse') }}
            </x-slot>
            <x-slot name="content">
                <form wire:submit.prevent="create">
                    <div class="flex flex-wrap">
                        <div class="xl:w-1/2 lg:w-1/2 md:w-1/2 sm:w-full">
                            <x-input.group label="Name" for="name" :error="$errors->first('editing.name')">
                                <x-input.text wire:model="editing.name" id="name" />
                            </x-input.group>
                        </div>
                        <div class="xl:w-1/2 lg:w-1/2 md:w-1/2 sm:w-full">
                            <x-input.group label="Description" for="description" :error="$errors->first('editing.description')">
                                <x-input.text wire:model="editing.description" id="description" />
                            </x-input.group>
                        </div>
                    </div>
                    <div class="w-full flex justify-end">
                        <x-button.primary wire:click="$set('createModal', false)">
                            {{ __('Cancel') }}
                        </x-button.primary>
                        <x-button.primary wire:click="create">
                            {{ __('Save') }}
                        </x-button.primary>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    </div>

    @push('page_scripts')
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

