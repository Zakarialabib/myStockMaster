<div>
    @section('title', __('Customer'))

    <x-theme.breadcrumb :title="__('Customer List')" :parent="route('customers.index')" :parentName="__('Customer List')">
        <x-dropdown align="right" width="48" class="w-auto mr-2">
            <x-slot name="trigger" class="inline-flex">
                <x-button secondary type="button" class="text-white flex items-center">
                    <i class="fas fa-angle-double-down w-4 h-4"></i>
                </x-button>
            </x-slot>
            <x-slot name="content">
                <x-dropdown-link wire:click="importExcel" wire:loading.attr="disabled">
                    {{ __('Excel Import') }}
                </x-dropdown-link>
                <x-dropdown-link wire:click="exportAll" wire:loading.attr="disabled">
                    {{ __('Export PDF') }}
                </x-dropdown-link>
                <x-dropdown-link wire:click="downloadAll" wire:loading.attr="disabled">
                    {{ __('Export Excel') }}
                </x-dropdown-link>
            </x-slot>
        </x-dropdown>
        @can('customer_create')
            <x-button primary type="button" wire:click="dispatchTo('customers.create', 'createModal')">
                {{ __('Create Customer') }}
            </x-button>
        @endcan
    </x-theme.breadcrumb>

    <div class="flex flex-wrap justify-center items-center">
        <div class="md:w-1/3 sm:w-full flex flex-wrap space-x-2">
            <select wire:model.live="perPage"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-auto sm:text-sm border-gray-300 rounded-md focus:outline-none focus:shadow-outline-blue transition duration-150 ease-in-out">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @if ($selected)
                <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                    <i class="fas fa-trash"></i>
                </x-button>
                <x-button success type="button" wire:click="downloadSelected" wire:loading.attr="disabled">
                    {{ __('EXCEL') }}
                </x-button>
                <x-button warning type="button" wire:click="exportSelected" wire:loading.attr="disabled">
                    {{ __('PDF') }}
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
        <div class="md:w-1/3 sm:w-full px-3">
            <select required id="customer_group_id" name="customer_group_id" wire:model.live="customer_group_id"
                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                <option value="">{{ __('Select group') }}</option>
                @foreach ($this->customerGroups as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('customer_group_id')" class="mt-2" />
        </div>
        <div class="md:w-1/3 sm:w-full">
            <x-input wire:model.live="search" placeholder="{{ __('Search') }}" autofocus />
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input wire:model.live="selectPage" type="checkbox" />
            </x-table.th>
            <x-table.th sortable :direction="$sorts['name'] ?? null" field="name" wire:click="sortingBy('name')">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th sortable :direction="$sorts['phone'] ?? null" field="phone" wire:click="sortingBy('phone')">
                {{ __('Phone') }}
            </x-table.th>
            <x-table.th>
                {{ __('Customer Group') }}
            </x-table.th>
            <x-table.th>
                {{ __('Tax number') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($customers as $customer)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $customer->id }}">
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $customer->id }}" wire:model.live="selected" />
                    </x-table.td>
                    <x-table.td>
                        <button type="button" wire:click="dispatchTo('customers.show','showModal', { id : '{{ $customer->id }}' })"
                            class="text-indigo-500 hover:text-indigo-600">
                            {{ $customer->name }}
                        </button>
                    </x-table.td>
                    <x-table.td>
                        <a href="tel:{{ $customer->phone }}" target="__blank" class="text-blue-500 hover:underline">
                            {{ $customer->phone }}
                        </a>
                    </x-table.td>
                    <x-table.td>
                        {{ $customer?->customerGroup?->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $customer->tax_number }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-button primary type="button" class="text-white flex items-center">
                                        <i class="fas fa-angle-double-down"></i>
                                    </x-button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link
                                        wire:click="dispatchTo('customers.show','showModal', { id : '{{ $customer->id }}' })"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-eye"></i>
                                        {{ __('View') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('customer.details', $customer->id) }}">
                                        <i class="fas fa-book"></i>
                                        {{ __('Details') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link wire:click="dispatchTo('customers.edit','editModal', { id : '{{ $customer->id }}'})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click="dispatch('deleteModal', {{ $customer->id }})"
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

    <div class="pt-3">
        {{ $customers->links() }}
    </div>

    <livewire:customers.show :customer="$customer" />

    <livewire:customers.edit :customer="$customer" />

    <livewire:customers.create />

    <x-modal wire:model="importModal">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                {{ __('Import Excel') }}
                <x-button primary wire:click="downloadSample" type="button">
                    {{ __('Download Sample') }}
                </x-button>
            </div>
        </x-slot>

        <x-slot name="content">
            <form wire:submit="importExcel">
                <div class="space-y-4">
                    <div class="mt-4">
                        <x-label for="file" :value="__('Import')" />
                        <x-input id="file" class="block mt-1 w-full" type="file" name="file"
                            wire:model="file" />
                        <x-input-error :messages="$errors->get('file')" for="file" class="mt-2" />
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
</div>
