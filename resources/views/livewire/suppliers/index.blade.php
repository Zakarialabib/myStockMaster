<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($selected)
            <x-button danger type="button" wire:click="$toggle('showDeleteModal')" wire:loading.attr="disabled">
                <i class="fas fa-trash"></i>
            </x-button>
            @endif
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <div class="flex items-center mr-3 pl-4">
                <input type="text" wire:model.debounce.300ms="search"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th >
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('phone')" :direction="$sorts['phone'] ?? null">
                {{ __('Phone') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('address')" :direction="$sorts['address'] ?? null">
                {{ __('Address') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($suppliers as $supplier)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $supplier->id }}">
                    <x-table.td class="pr-0">
                        <input type="checkbox" wire:model="selected" value="{{ $supplier->id }}" />
                    </x-table.td>
                    <x-table.td>
                        {{ $supplier->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $supplier->phone }}
                    </x-table.td>
                    <x-table.td>
                        {{ $supplier->address }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button secondary wire:click="showModal({{ $supplier->id }})"
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary wire:click="editModal({{ $supplier->id }})" 
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$emit('deleteModal', {{ $supplier->id }})"
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="12">
                        <div class="flex justify-center items-center space-x-2">
                            <i class="fas fa-box-open text-3xl text-gray-400"></i>
                            <span class="text-gray-400">{{ __('No suppliers found.') }}</span>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="px-6 py-3">
        {{ $suppliers->links() }}
    </div>
    
    @if (null !== $showModal)
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Supplier') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap -mx-2 mb-3">
                <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" class="block mt-1 w-full" disabled type="text"
                        wire:model.defer="supplier.name" />
                </div>

                <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                    <x-label for="phone" :value="__('Phone')" />
                    <x-input id="phone" class="block mt-1 w-full" disabled type="text"
                        wire:model.defer="supplier.phone" />
                </div>


                <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                    <x-label for="email" :value="__('Email')" />
                    <x-input id="email" class="block mt-1 w-full" disabled type="email"
                        wire:model.defer="supplier.email" />
                </div>

                <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                    <x-label for="address" :value="__('Address')" />
                    <x-input id="address" class="block mt-1 w-full" disabled type="text"
                        wire:model.defer="supplier.address" />
                </div>

                <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                    <x-label for="city" :value="__('City')" />
                    <x-input id="city" class="block mt-1 w-full" type="text" disabled
                        wire:model.defer="supplier.city" />
                </div>

                <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                    <x-label for="tax_number" :value="__('Tax Number')" />
                    <x-input id="tax_number" class="block mt-1 w-full" type="text"
                        wire:model.defer="supplier.tax_number" disabled />
                </div>
            </div>
        </x-slot>
    </x-modal>
    @endif
    
    @if (null !== $editModal)
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Supplier') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                        <x-label for="name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" type="text"
                            wire:model.defer="supplier.name" required />
                        <x-input-error :messages="$errors->get('supplier.name')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                        <x-label for="phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text"
                            wire:model.defer="supplier.phone" />
                        <x-input-error :messages="$errors->get('supplier.phone')" class="mt-2" />
                    </div>
                    <x-accordion title="{{ __('Details') }}">
                        <div class="flex flex-wrap -mx-2 mb-3">
                            <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                                <x-label for="email" :value="__('Email')" />
                                <x-input id="email" class="block mt-1 w-full" type="email"
                                    wire:model.defer="supplier.email" />
                                <x-input-error :messages="$errors->get('supplier.email')" class="mt-2" />
                            </div>

                            <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                                <x-label for="address" :value="__('Address')" />
                                <x-input id="address" class="block mt-1 w-full" type="text"
                                    wire:model.defer="supplier.address" />
                                <x-input-error :messages="$errors->get('supplier.address')" class="mt-2" />
                            </div>

                            <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                                <x-label for="city" :value="__('City')" />
                                <x-input id="city" class="block mt-1 w-full" type="text"
                                    wire:model.defer="supplier.city" />
                                <x-input-error :messages="$errors->get('supplier.city')" class="mt-2" />
                            </div>

                            <div class="w-full lg:w-1/2 px-3 mb-4 lg:mb-0">
                                <x-label for="tax_number" :value="__('Tax Number')" />
                                <x-input id="tax_number" class="block mt-1 w-full" type="text"
                                    wire:model.defer="supplier.tax_number" />
                                <x-input-error :messages="$errors->get('supplier.tax_number')" for="" class="mt-2" />
                            </div>
                        </div>
                    </x-accordion>

                    <div class="w-full flex justify-start px-3">
                        <x-button primary type="submit" wire:click="update" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>

    <livewire:suppliers.create />

     {{-- Import modal --}}

     <x-modal wire:model="importModal">
        <x-slot name="title">
            {{ __('Import Excel') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="import">
                <div class="mb-4">
                    <div class="w-full px-3 mt-4">
                        <x-label for="import" :value="__('Import')" />
                        <x-input id="import" class="block mt-1 w-full" type="file" name="import"
                            wire:model.defer="import_file" />
                        <x-input-error :messages="$errors->get('import')" for="import" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-start px-3">
                        <x-button primary type="submit" wire:click="import" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>

    {{-- End Import modal --}}


</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', supplierId => {
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
                        window.livewire.emit('delete', supplierId)
                    }
                })
            })
        })
    </script>
@endpush
