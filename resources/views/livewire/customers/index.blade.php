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
                    <i class="fas fa-ellipsis-v"></i>
                     {{('Actions')}}
                     </x-primary-button>
                </x-slot>

                <x-slot name="content">

                    <button
                    class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                    type="button"  wire:click="$toggle('showDeleteModal')" wire:loading.attr="disabled"
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
    <div wire:loading.delay>
        Loading...
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th class="pr-0 w-8">
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('email')" :direction="$sorts['email'] ?? null">
                {{ __('Email') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('phone')" :direction="$sorts['phone'] ?? null">
                {{ __('Phone') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('address')" :direction="$sorts['address'] ?? null">
                {{ __('Address') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('city')" :direction="$sorts['city'] ?? null">
                {{ __('City') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-slot name="tbody">
            @if ($selectPage)
                <x-table.tr class="bg-blue-100 dark:bg-dark-eval-2" wire:key="row-message">
                    <x-table.th colspan="12">
                        @unless ($selectAll)
                            <div>
                                <span>{{__('You have selected')}} <strong>{{ $customers->count() }}</strong> {{__('customers, do you want to select all')}} <strong>{{ $customers->total() }}</strong>?</span>
                                <x-primary-button wire:click="selectAll" class="ml-1">{{__('Select All')}}</x-primary-button>
                            </div>
                        @else
                            <span>{{__('You are currently selecting all')}} <strong>{{ $customers->total() }}</strong> {{__('customers')}}.</span>
                        @endif
                    </x-table.th>
                </x-table.tr>
            @endif

            @forelse ($customers as $customer)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $customer->id }}">
                    <x-table.td class="pr-0">
                        <input type="checkbox" wire:model="selected" value="{{ $customer->id }}" />
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->id }}
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->email }}
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->phone }}
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->address }}
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->city }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-end">
                            <x-primary-button wire:click="editModal({{ $customer->id }})">
                                {{ __('Edit') }}
                            </x-primary-button>
                            <x-primary-button wire:click="showModal({{ $customer->id }})">
                                {{ __('Show') }}
                            </x-primary-button>
                            <button
                                    class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                                    type="button" wire:click="confirm('delete', {{ $adjustment->id }})"
                                    wire:loading.attr="disabled">
                                    {{__('Delete')}}
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
        </x-slot>
    </x-table>

    <div class="px-6 py-3">
        {{ $customers->links() }}
    </div>
</div>

<x-modal.dialog wire:model="showModal">
    <x-slot name="title">
        {{ __('Show User') }}
    </x-slot>

    <x-slot name="content">
        <div class="mt-4">
            <x-input-label for="name" :value=" __('Name') " />
            <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model="customer.name" disabled />
            <x-input-error :messages="$errors->get('customer.name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="email" :value=" __('Email')" />
            <x-text-input id="email" type="text" class="mt-1 block w-full" wire:model="customer.email" disabled />
            <x-input-error :messages="$errors->get('customer.email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" :value=" __('Password')" />
            <x-text-input id="password" type="text" class="mt-1 block w-full" wire:model="customer.password" disabled />
            <x-input-error :messages="$errors->get('customer.password')"  class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-primary-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
            {{ __('Close') }}
        </x-primary-button>
    </x-slot>
</x-modal.dialog>

<!-- Delete Contact Modal -->
<form wire:submit.prevent="deleteSelected">
    <x-modal.confirmation wire:model.defer="showDeleteModal">
        <x-slot name="title">{{ __('Delete Selected Customers') }}</x-slot>

        <x-slot name="content">
            <div class="py-8 text-cool-gray-700">{{ __('Are you sure you? This action is irreversible.') }}</div>
        </x-slot>

        <x-slot name="footer">
            <button class="btn border-gray-300 text-gray-700 dark:text-gray-300 active:bg-gray-50 dark:active:text-gray-800 hover:text-gray-500 dark:active:bg-dark-eval-1 active:text-gray-300 dark:hover:text-gray-700" wire:click="$set('showDeleteModal', false)">{{ __('Go back') }}</button>

            <button class="btn text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 border-indigo-600" type="submit">{{ __('Delete') }}</button>
        </x-slot>
    </x-modal.confirmation>
</form>


@push('page_scripts')

    <script>
        window.addEventListener('showDeleteModal', event => {
            $('#deleteModal').modal('show');
        });

        window.addEventListener('hideDeleteModal', event => {
            $('#deleteModal').modal('hide');
        });
    </script>

<script>
    window.addEventListener('confirm', event => {
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
                window.livewire.emit('delete', event.detail.id)
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        })
    })
</script>
@endpush