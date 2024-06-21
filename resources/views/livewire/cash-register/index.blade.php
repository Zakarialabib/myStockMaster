<div>
    @section('title', __('Cash Register'))
    <x-theme.breadcrumb :title="__('Cash Register List')" :parent="route('cash-register.index')" :parentName="__('Cash Register List')">
        <x-button primary type="button" wire:click="dispatchTo('cash-register.create', 'createModal')">
            {{ __('Create Cash Register') }}
        </x-button>
    </x-theme.breadcrumb>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap gap-6 w-full items-center">
            <select wire:model.live="perPage"
                class="w-auto shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm border-gray-300 rounded-md focus:outline-none focus:shadow-outline-blue transition duration-150 ease-in-out">
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
                <p class="text-sm  my-auto">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full ">
            <x-input wire:model.live="search" placeholder="{{ __('Search') }}" autofocus />
        </div>
    </div>


    <div class="grid gap-4 grid-cols-2 justify-center mb-2">
        <div class="w-full flex flex-wrap">
            <div class="w-full md:w-1/2 px-2">
                <label>{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                <x-input wire:model.live="startDate" type="date" name="startDate" value="$startDate" />
                @error('startDate')
                    <span class="text-danger mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full md:w-1/2 px-2">
                <label>{{ __('End Date') }} <span class="text-red-500">*</span></label>
                <x-input wire:model.live="endDate" type="date" name="endDate" value="$endDate" />
                @error('endDate')
                    <span class="text-danger mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="gap-2 inline-flex items-center mx-0 px-2">
            <x-button type="button" primary wire:click="filterByType('day')">{{ __('Today') }}</x-button>
            <x-button type="button" info wire:click="filterByType('month')">{{ __('This Month') }}</x-button>
            <x-button type="button" warning wire:click="filterByType('year')">{{ __('This Year') }}</x-button>
        </div>
    </div>
    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model.live="selectPage" />
            </x-table.th>
            <x-table.th sortable :direction="$sorts['user_id'] ?? null" field="user_id" wire:click="sortingBy('user_id')">
                {{ __('User') }}
            </x-table.th>
            <x-table.th sortable :direction="$sorts['warehouse_id'] ?? null" field="warehouse_id" wire:click="sortingBy('warehouse_id')">
                {{ __('Warehouse') }}
            </x-table.th>
            <x-table.th sortable :direction="$sorts['cash_in_hand'] ?? null" field="cash_in_hand" wire:click="sortingBy('cash_in_hand')">
                {{ __('Cash in hand') }}
            </x-table.th>
            <x-table.th sortable :direction="$sorts['created_at'] ?? null" field="created_at" wire:click="sortingBy('created_at')">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th>
                {{ __('Status') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($cashRegisters as $cashRegister)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $cashRegister->id }}">
                    <x-table.td class="pr-0">
                        <input wire:model.live="selected" type="checkbox" value="{{ $cashRegister->id }}" />
                    </x-table.td>
                    <x-table.td>
                        {{ $cashRegister->user->name ?? '' }}
                    </x-table.td>
                    <x-table.td>
                        {{ $cashRegister->warehouse->name ?? '' }}
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($cashRegister->cash_in_hand) }}
                    </x-table.td>
                    <x-table.td>
                        {{ format_date($cashRegister->created_at) }}
                    </x-table.td>
                    <x-table.td>
                        @if ($cashRegister->status == true)
                            <x-badge type="success">{{ __('Open') }}</x-badge>
                        @else
                            <x-badge type="danger">{{ __('Close') }}</x-badge>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">

                            <x-button primary wire:click="$dispatch('showModal',{ id : '{{ $cashRegister->id }}' })"
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>

                            <x-button danger wire:click="$dispatch('deleteModal', {{ $cashRegister->id }})"
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="7">
                        <div class="flex justify-center items-center space-x-2">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"
                                    clip-rule="evenodd"></path>
                                <path fill-rule="evenodd"
                                    d="M10 4a1 1 0 100 2 1 1 0 000-2zm0 8a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span
                                class="font-medium py-8 text-gray-400 text-xl">{{ __('No cashRegisters found...') }}</span>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="pt-3">
        {{ $cashRegisters->links() }}
    </div>

    <livewire:cashRegister.show />

    <livewire:cashRegister.create />

</div>
