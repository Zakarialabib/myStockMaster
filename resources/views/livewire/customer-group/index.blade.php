<div>
    @section('title', __('Customer Groups'))

    <x-theme.breadcrumb :title="__('Customer Groups')" :parent="route('customer-group.index')" :parentName="__('Customer Group List')">
        <x-button primary type="button" wire:click="dispatchTo('customer-group.create','createModal')">
            {{ __('Create Customer Group') }}
        </x-button>
    </x-theme.breadcrumb>

    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
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
                <x-input wire:model="search" placeholder="{{ __('Search') }}" autofocus />
            </div>
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
            <x-table.th sortable :direction="$sorts['percentage'] ?? null" field="percentage" wire:click="sortingBy('percentage')">
                {{ __('Percentage') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse($customergroups as $customergroup)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $customergroup->id }}">
                    <x-table.td>
                        <input type="checkbox" value="{{ $customergroup->id }}" wire:model.live="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $customergroup->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $customergroup->percentage }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button info type="button" wire:click="openShowModal({ id :'{{ $customergroup->id }}'})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary type="button"
                                wire:click="dispatchTo('customer-group.edit','editModal', { id : '{{ $customergroup->id }}'})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger type="button" wire:click="deleteModal({ id :'{{ $customergroup->id }}'})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="4">
                        <div class="flex justify-center">
                            {{ __('No Customer Groups found.') }}
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="pt-3">
        {{ $customergroups->links() }}
    </div>

    <livewire:customer-group.edit :customergroup="$customergroup" lazy />

    <livewire:customer-group.create />

    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Customer Group') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap justify-center">
                <div class="w-full">
                    <x-label for="name" :value="__('Name')" />
                    {{ $customergroup?->name }}
                </div>
                <div class="w-full">
                    <x-label for="percentage" :value="__('Percentage')" />
                    {{ $customergroup?->percentage }}
                </div>
            </div>
        </x-slot>
    </x-modal>
</div>
