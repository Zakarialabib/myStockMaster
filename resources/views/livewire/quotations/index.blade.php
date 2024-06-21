<div>
    @section('title', __('Quotations'))

    <x-theme.breadcrumb :title="__('Quotations List')" :parent="route('quotations.index')" :parentName="__('Quotations List')">

        @can('quotation_create')
            <x-button href="{{ route('quotation.create') }}" primary>
                {{ __('Create Quotation') }}
            </x-button>
        @endcan

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
                @can('quotation_delete')
                    <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                        <i class="fas fa-trash"></i>
                    </x-button>
                @endcan
            @endif
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <div class="my-2">
                <x-input wire:model.live="search" placeholder="{{ __('Search') }}" autofocus />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model.live="selectPage" />
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('date')" field="date" :direction="$sorts['date'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('customer_id')" field="customer_id" :direction="$sorts['customer_id'] ?? null">
                {{ __('Customer') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('total_amount')" field="total_amount" :direction="$sorts['total_amount'] ?? null">
                {{ __('Total') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('status')" field="status" :direction="$sorts['status'] ?? null">
                {{ __('Status') }}
            </x-table.th>
            <x-table.th />
        </x-slot>

        <x-table.tbody>
            @forelse ($quotations as $quotation)
                {{-- @dd($quotation); --}}
                <x-table.tr wire:loading.class.delay="opacity-50">
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $quotation->id }}" wire:model.live="selected" />
                    </x-table.td>
                    <x-table.td>
                        {{ $quotation->date }}
                    </x-table.td>
                    <x-table.td>
                        <a href="{{ route('customer.details', $quotation->customer->id) }}"
                            class="text-indigo-500 hover:text-indigo-600">
                            {{ $quotation->customer->name }}
                        </a>
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($quotation->total_amount) }}
                    </x-table.td>
                    <x-table.td>
                        @php
                            $badgeType = $quotation->status->getBadgeType();
                        @endphp

                        <x-badge :type="$badgeType">{{ $quotation->status->getName() }}</x-badge>
                    </x-table.td>
                    <x-table.td class="whitespace-no-wrap row-action--icon">
                        <x-dropdown align="right" width="56">
                            <x-slot name="trigger" class="inline-flex">
                                <x-button primary type="button" class="text-white flex items-center">
                                    <i class="fas fa-angle-double-down"></i>
                                </x-button>
                            </x-slot>
                            <x-slot name="content">
                                @can('quotation_sale')
                                    <x-dropdown-link href="{{ route('quotation-sales.create', $quotation) }}">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        {{ __('Make Sale') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('send_quotation_mails')
                                    <x-dropdown-link href="{{ route('quotation.email', $quotation) }}">
                                        <i class="fas fa-envelope mr-2"></i>
                                        {{ __('Send Mail') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('quotation_update')
                                    <x-dropdown-link href="{{ route('quotation.edit', $quotation->id) }}">
                                        <i class="fas fa-edit mr-2"></i>
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('quotation_show')
                                    <x-dropdown-link wire:click="dispatch('showModal', { id : {{ $quotation->id }} })"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-eye mr-2"></i>
                                        {{ __('View') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('quotation_delete')
                                    <x-dropdown-link type="button" wire:click="confirm('delete', {{ $quotation->id }})">
                                        <i class="fas fa-trash mr-2"></i>
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-dropdown>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="7">
                        <div class="flex justify-center items-center">
                            <span class="text-gray-400">{{ __('No results found') }}</span>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="px-6 py-3">
        {{ $quotations->links() }}
    </div>

    <livewire:quotations.show :quotation="$quotation" lazy />

</div>
