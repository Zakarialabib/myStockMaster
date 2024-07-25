<div>
    @section('title', __('Purchases'))

    <x-theme.breadcrumb :title="__('Purchases List')" :parent="route('quotations.index')" :parentName="__('Purchases List')">

        @can('purchase_create')
            <x-button primary href="{{ route('purchase.create') }}" wire:loading.attr="disabled">
                {{ __('Create Purchase order') }}
            </x-button>
        @endcan

    </x-theme.breadcrumb>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model.live="perPage"
                class="w-20 block p-3 leading-5 bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
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
                <x-input wire:model.live.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
            </div>
        </div>


        <div class="grid gap-4 grid-cols-2 justify-center mb-2">
            <div class="w-full flex flex-wrap">
                <div class="w-full md:w-1/2 px-2">
                    <label>{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                    <x-input wire:model.live="startDate" type="date" name="startDate" />
                    @error('startDate')
                        <span class="text-danger mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-2">
                    <label>{{ __('End Date') }} <span class="text-red-500">*</span></label>
                    <x-input wire:model.live="endDate" type="date" name="endDate" />
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
    </div>
    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model.live="selectPage" />
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('reference')" :direction="$sorts['reference'] ?? null">
                {{ __('Reference') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('supplier_id')" :direction="$sorts['supplier_id'] ?? null">
                {{ __('Supplier') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('payment_id')" :direction="$sorts['payment_id'] ?? null">
                {{ __('Payment status') }}
            </x-table.th>
            <x-table.th>
                {{ __('Total') }}
            </x-table.th>
            <x-table.th>
                {{ __('Due amount') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($purchases as $purchase)
                <x-table.tr>
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $purchase->id }}" wire:model.live="selected" />
                    </x-table.td>
                    <x-table.td>
                        {{ $purchase->reference }}
                    </x-table.td>
                    <x-table.td>
                        {{ format_date($purchase->date) }}
                    </x-table.td>
                    <x-table.td>
                        @if ($purchase->supplier)
                            <a class="text-indigo-500 hover:text-indigo-600  font-bold tracking-wide"
                                href="{{ route('supplier.details', $purchase->supplier->uuid) }}">
                                {{ $purchase->supplier->name }}
                            </a>
                        @else
                            {{ $purchase->supplier->name }}
                        @endif
                    </x-table.td>
                    <x-table.td>
                        @php
                            $badgeType = $purchase->status->getBadgeType();
                        @endphp

                        <x-badge :type="$badgeType">
                            {{ $purchase->payment_id }}
                            {{-- {{ \app\Enums\PaymentStatus::getName($purchase->payment_id) }} --}}
                        </x-badge>

                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($purchase->total_amount) }}
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($purchase->due_amount) }}
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
                                    @can('purchase_payment_access')
                                        <x-dropdown-link wire:click="$dispatch('showPayments', {{ $purchase->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ __('Payments') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('purchase_payment_access')
                                        @if ($purchase->due_amount > 0)
                                            <x-dropdown-link wire:click="$dispatch('paymentModal', {{ $purchase->id }})"
                                                wire:loading.attr="disabled">
                                                <i class="fas fa-money-bill-wave"></i>
                                                {{ __('Add Payment') }}
                                            </x-dropdown-link>
                                        @endif
                                    @endcan

                                    @can('purchase_access')
                                        <x-dropdown-link wire:click="$dispatch('showModal', {{ $purchase->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-eye"></i>
                                            {{ __('View') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('purchase_update')
                                        <x-dropdown-link href="{{ route('purchases.edit', $purchase->uuid) }}"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-edit"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <x-dropdown-link target="_blank" href="{{ route('purchases.pdf', $purchase->id) }}"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-print"></i>
                                        {{ __('Print') }}
                                    </x-dropdown-link>

                                    @can('purchase_delete')
                                        <x-dropdown-link wire:click="$dispatch('deleteModal', {{ $purchase->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-trash"></i>
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="8">
                        <div class="flex justify-center items-center">
                            <i class="fas fa-box-open text-4xl text-gray-400"></i>
                            {{ __('No results found') }}
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>
    <div class="mt-4">
        {{ $purchases->links() }}
    </div>

    @livewire('purchase.show', ['purchase' => $purchase])

    @livewire('purchase.payment-form', ['purchase' => $purchase])

    {{-- @if (empty($showPayments))
        <livewire:purchase.payment.index :purchase="$purchase" />
    @endif --}}


    @push('scripts')
        <script>
            document.addEventListener('livewire:init', function() {
                window.livewire.on('deleteModal', purchaseId => {
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
                            window.Livewire.dispatch('delete', purchaseId)
                        }
                    })
                })
            })
        </script>
    @endpush


</div>
