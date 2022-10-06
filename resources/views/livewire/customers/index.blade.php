<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            <x-dropdown align="right" width="48">

                <x-slot name="trigger">
                    <x-primary-button class="flex items-center">
                        {{__('Actions')}}
                    </x-primary-button>
                </x-slot>

                <x-slot name="content">

                    <button
                        class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                        type="button" wire:click="$toggle('showDeleteModal')" wire:loading.attr="disabled"
                        {{ $this->selectedCount ? '' : 'disabled' }}>
                        {{ __('Delete Selected') }}
                    </button>
                    <button
                        class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                        type="button" wire:click="confirm('downloadSelected')" wire:loading.attr="disabled"
                        {{ $this->selectedCount ? '' : 'disabled' }}>
                        {{ __('Export Selected Excel') }}
                    </button>
                    <button
                        class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                        type="button" wire:click="confirm('downloadAll')" wire:loading.attr="disabled">
                        {{ __('Export All Excel') }}
                    </button>
                    <button
                        class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                        type="button" wire:click="confirm('import')" wire:loading.attr="disabled">
                        {{ __('Import') }}
                    </button>
                    <button
                        class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                        type="button" wire:click="confirm('exportSelected')" wire:loading.attr="disabled">
                        {{ __('Export selected pdf') }}
                    </button>
                    <button
                        class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                        type="button" wire:click="confirm('exportAll')" wire:loading.attr="disabled">
                        {{ __('Export All Pdf') }}
                    </button>

                </x-slot>

            </x-dropdown>

        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <div class="my-2 my-md-0">
                <input type="text" wire:model.debounce.300ms="search"
                    class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>
    <div wire:loading.delay class="flex justify-center">
        <x-loading />
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th class="pr-0 w-8">
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('phone')" :direction="$sorts['phone'] ?? null">
                {{ __('Phone') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($customers as $customer)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $customer->id }}">
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $customer->id }}" wire:model="selected" />
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->customer_name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->customer_phone }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-end">
                            <x-primary-button wire:click="editModal({{ $customer->id }})"
                                wire:loading.attr="disabled">
                                {{ __('Edit') }}
                            </x-primary-button>
                            <x-primary-button wire:click="showModal({{ $customer->id }})"
                                wire:loading.attr="disabled">
                                {{ __('Show') }}
                            </x-primary-button>
                            <button
                                class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                                type="button" wire:click="$emit('deleteModal', {{ $customer->id }})"
                                wire:loading.attr="disabled">
                                {{ __('Delete') }}
                            </button>
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

    <div class="px-6 py-3">
        {{ $customers->links() }}
    </div>


    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('Create Customer') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit.prevent="create">
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="customer.customer_name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model.defer="customer.customer_name" />
                        <x-input-error :messages="$errors->get('customer.customer_name')" for="customer.customer_name" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="customer.customer_phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text"
                            wire:model.defer="customer.customer_phone" />
                        <x-input-error :messages="$errors->get('customer.customer_phone')" for="customer.customer_phone" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="email" :value="__('Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="email"
                            wire:model.defer="customer.customer_email" />
                        <x-input-error :messages="$errors->get('customer.customer_email')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="address" :value="__('Address')" />
                        <x-input id="address" class="block mt-1 w-full" type="text"
                            wire:model.defer="customer.address" />
                        <x-input-error :messages="$errors->get('customer.address')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text"
                            wire:model.defer="customer.city" />
                        <x-input-error :messages="$errors->get('customer.city')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="tax_number" :value="__('Tax Number')" />
                        <x-input id="tax_number" class="block mt-1 w-full" type="text"
                            wire:model.defer="customer.tax_number" />
                        <x-input-error :messages="$errors->get('customer.tax_number')" class="mt-2" />
                    </div>

                    <div class="w-full flex items-center justify-end mt-4">
                        <x-primary-button wire:click="create" wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-primary-button>
                        <x-primary-button wire:click="$set('createModal', false)" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>


    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show User') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap">
                <div>
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" class="block mt-1 w-full" disabled type="text"
                        wire:model.defer="customer.customer_name" />
                </div>

                <div>
                    <x-label for="phone" :value="__('Phone')" />
                    <x-input id="phone" class="block mt-1 w-full" disabled type="text"
                        wire:model.defer="customer.customer_phone" />
                </div>

                <div>
                    <x-label for="email" :value="__('Email')" />
                    <x-input id="email" class="block mt-1 w-full" disabled type="email"
                        wire:model.defer="customer.customer_email" />
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

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button wire:click="$set('showModal', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-primary-button>
                </div>
            </div>
        </x-slot>
    </x-modal>

    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit User') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" type="text"
                            wire:model.defer="customer.customer_name" required />
                        <x-input-error :messages="$errors->get('customer.customer_name')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text"
                            wire:model.defer="customer.customer_phone" />
                        <x-input-error :messages="$errors->get('customer.customer_phone')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="email" :value="__('Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="email"
                            wire:model.defer="customer.customer_email" />
                        <x-input-error :messages="$errors->get('customer.customer_email')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="address" :value="__('Address')" />
                        <x-input id="address" class="block mt-1 w-full" type="text"
                            wire:model.defer="customer.address" />
                        <x-input-error :messages="$errors->get('customer.address')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text"
                            wire:model.defer="customer.city" />
                        <x-input-error :messages="$errors->get('customer.city')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="tax_number" :value="__('Tax Number')" />
                        <x-input id="tax_number" class="block mt-1 w-full" type="text"
                            wire:model.defer="customer.tax_number" />
                        <x-input-error :messages="$errors->get('customer.tax_number')" for="" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button wire:click="update" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-primary-button>
                        <x-primary-button wire:click="$set('editModal', false)" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>

@push('page_scripts')
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
