<div>
    <div class="flex flex-wrap justify-center">
        <div class="md:w-1/2 sm:w-full flex flex-wrap my-2 space-x-2">
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
                <x-button success type="button" wire:click="downloadSelected" wire:loading.attr="disabled">
                    {{ __('EXCEL') }}
                </x-button>
                <x-button warning type="button" wire:click="exportSelected" wire:loading.attr="disabled">
                    {{ __('PDF') }}
                </x-button>
            @endif

        </div>
        <div class="md:w-1/2 sm:w-full my-2">
            <div class="my-2">
                <input type="text" wire:model.lazy="search"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>
    
    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input wire:model="selectPage" type="checkbox" />
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('phone')" :direction="$sorts['phone'] ?? null">
                {{ __('Phone') }}
            </x-table.th>
            <x-table.th>
                {{__('Actions')}}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($customers as $customer)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $customer->id }}">
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $customer->id }}" wire:model="selected" />
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->phone }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-dropdown align="right" class="w-auto">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-button primary type="button" class="text-white flex items-center">
                                        <i class="fas fa-angle-double-down"></i>
                                    </x-button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link wire:click="showModal({{ $customer->id }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-eye"></i>
                                        {{ __('View') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('customer.details', $customer->id) }}">
                                        <i class="fas fa-book"></i>
                                        {{ __('Details') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link wire:click="editModal({{ $customer->id }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click="$emit('deleteModal', {{ $customer->id }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-trash"></i>
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>

                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="12">
                        <div class="flex justify-center items-center space-x-2">
                            <i class="fas fa-box-open text-3xl text-gray-400"></i>
                            <span class="text-gray-400">{{ __('No customers found.') }}</span>
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
                <p wire:click="resetSelected" wire:loading.attr="disabled"
                    class="text-sm leading-5 font-medium text-red-500 cursor-pointer ">
                    {{ __('Clear Selected') }}
                </p>
            @endif
            {{ $customers->links() }}
        </div>
    </div>
    @if (null !== $showModal)
        <x-modal wire:model="showModal">
            <x-slot name="title">
                {{ __('Show User') }}
            </x-slot>

            <x-slot name="content">
                <div class="flex flex-wrap">
                    <div>
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" disabled type="text"
                            wire:model.defer="customer.name" />
                    </div>

                    <div>
                        <x-label for="phone" :value="__('Phone')" />
                        <x-input id="phone" class="block mt-1 w-full" disabled type="text"
                            wire:model.defer="customer.phone" />
                    </div>

                    <div>
                        <x-label for="email" :value="__('Email')" />
                        <x-input id="email" class="block mt-1 w-full" disabled type="email"
                            wire:model.defer="customer.email" />
                    </div>

                    <div>
                        <x-label for="address" :value="__('Address')" />
                        <x-input id="address" class="block mt-1 w-full" disabled type="text"
                            wire:model.defer="customer.address" />
                    </div>

                    <div>
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text" disabled
                            wire:model.defer="customer.city" />

                    </div>
                    <div>
                        <x-label for="tax_number" :value="__('Tax Number')" />
                        <x-input id="tax_number" class="block mt-1 w-full" type="text"
                            wire:model.defer="customer.tax_number" disabled />
                    </div>
                </div>
            </x-slot>
        </x-modal>
    @endif
    @if (null !== $editModal)
        <x-modal wire:model="editModal">
            <x-slot name="title">
                {{ __('Edit User') }}
            </x-slot>

            <x-slot name="content">
                <form wire:submit.prevent="update">
                    <div class="flex flex-wrap">
                        <div class="w-full sm:w-1/2 px-3 mb-6">
                            <x-label for="name" :value="__('Name')" required />
                            <x-input id="name" class="block mt-1 w-full" type="text"
                                wire:model.defer="customer.name" required />
                            <x-input-error :messages="$errors->get('customer.name')" class="mt-2" />
                        </div>

                        <div class="w-full sm:w-1/2 px-3 mb-6">
                            <x-label for="phone" :value="__('Phone')" required />
                            <x-input id="phone" class="block mt-1 w-full" required type="text"
                                wire:model.defer="customer.phone" />
                            <x-input-error :messages="$errors->get('customer.phone')" class="mt-2" />
                        </div>
                        <x-accordion title="{{ __('Details') }}" class="flex flex-wrap">
                            <div class="w-full sm:w-1/2 px-3 mb-6">
                                <x-label for="email" :value="__('Email')" />
                                <x-input id="email" class="block mt-1 w-full" type="email"
                                    wire:model.defer="customer.email" />
                                <x-input-error :messages="$errors->get('customer.email')" class="mt-2" />
                            </div>

                            <div class="w-full sm:w-1/2 px-3 mb-6">
                                <x-label for="address" :value="__('Address')" />
                                <x-input id="address" class="block mt-1 w-full" type="text"
                                    wire:model.defer="customer.address" />
                                <x-input-error :messages="$errors->get('customer.address')" class="mt-2" />
                            </div>

                            <div class="w-full sm:w-1/2 px-3 mb-6">
                                <x-label for="city" :value="__('City')" />
                                <x-input id="city" class="block mt-1 w-full" type="text"
                                    wire:model.defer="customer.city" />
                                <x-input-error :messages="$errors->get('customer.city')" class="mt-2" />
                            </div>

                            <div class="w-full sm:w-1/2 px-3 mb-6">
                                <x-label for="tax_number" :value="__('Tax Number')" />
                                <x-input id="tax_number" class="block mt-1 w-full" type="text"
                                    wire:model.defer="customer.tax_number" />
                                <x-input-error :messages="$errors->get('customer.tax_number')" for="" class="mt-2" />
                            </div>
                        </x-accordion>

                        <div class="w-full px-3">
                            <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                                {{ __('Update') }}
                            </x-button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    @endif
    {{-- Import modal --}}

    <x-modal wire:model="import">
        <x-slot name="title">
            {{ __('Import Customers') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="importExcel">
                <div class="space-y-4">
                    <div class="mt-4">
                        <x-label for="import" :value="__('Import')" />
                        <x-input id="import" class="block mt-1 w-full" type="file" name="import"
                            wire:model.defer="import" />
                        <x-input-error :messages="$errors->get('import')" for="import" class="mt-2" />
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
                        <x-button primary type="submit" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>

    {{-- End Import modal --}}

    <livewire:customers.create />

</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', customerId => {
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
                        window.livewire.emit('delete', customerId)
                    }
                })
            })
        })
    </script>
@endpush
