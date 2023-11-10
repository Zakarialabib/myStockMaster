<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($selected)
                <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                    <i class="fas fa-trash"></i>
                </x-button>
            @endif
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <div class="my-2">
                <x-input wire:model.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('customer_id')" :direction="$sorts['customer_id'] ?? null">
                {{ __('Customer') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('total')" :direction="$sorts['total'] ?? null">
                {{ __('Total') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
                {{ __('Status') }}
            </x-table.th>
            <x-table.th />
        </x-slot>

        <x-table.tbody>
            @forelse ($quotations as $quotation)
                <x-table.tr wire:loading.class.delay="opacity-50">
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $quotation->id }}" wire:model="selected" />
                    </x-table.td>
                    <x-table.td>
                        {{ $quotation->date }}
                    </x-table.td>
                    <x-table.td>
                        <a href="{{ route('customer.details', $quotation->customer->uuid) }}"
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
                                    <x-dropdown-link href="{{ route('quotations.edit', $quotation->id) }}">
                                        <i class="fas fa-edit mr-2"></i>
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('quotation_access')
                                    <x-dropdown-link wire:click="showModal({{ $quotation->id }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-eye mr-2"></i>
                                        {{ __('View') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('quotation_delete')
                                    <x-dropdown-link type="button" wire:click="delete({{ $quotation->id }})">
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
                            <span class="text-gray-400 dark:text-gray-300">{{ __('No results found') }}</span>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="px-6 py-3">
        {{ $quotations->links() }}
    </div>

    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Quotation') }} - {{ $quotation?->reference }}
        </x-slot>

        <x-slot name="content">
            <div class="w-full">
                <div class="container flex flex-wrap py-3 items-center">
                    @if ($quotation != null)
                        <x-button target="_blank" secondary class="d-print-none"
                            href="{{ route('quotations.pdf', $quotation->id) }}">
                            {{ __('Print') }}
                        </x-button>
                    @endif
                </div>
                <div class="p-4">
                    <div class="flex flex-row mb-4">
                        <div class="md-w-1/4 sm:w-full px-2 mb-2">
                            <h5 class="mb-2 border-b pb-2">{{ __('Company Info') }}:</h5>
                            <div><strong>{{ settings()->company_name }}</strong></div>
                            <div>{{ settings()->company_address }}</div>
                            @if (settings()->show_email == true)
                                <div>{{ __('Email') }}: {{ settings()->company_email }}</div>
                            @endif
                            <div>{{ __('Phone') }}: {{ settings()->company_phone }}</div>
                        </div>

                        <div class="md-w-1/4 sm:w-full px-2 mb-2">
                            <h5 class="mb-2 border-b pb-2">{{ __('Customer Info') }}:</h5>
                            <div><strong>{{ $quotation?->customer->name }}</strong></div>
                            @if (settings()->show_address == true)
                                <div>{{ $quotation?->customer->address }}</div>
                            @endif
                            @if (settings()->show_email == true)
                                <div>{{ __('Email') }}: {{ $quotation?->customer->email }}</div>
                            @endif
                            <div>{{ __('Phone') }}: {{ $quotation?->customer->phone }}</div>
                        </div>

                        <div class="md-w-1/4 sm:w-full px-2 mb-2">
                            <h5 class="mb-2 border-b pb-2">{{ __('Invoice Info') }}:</h5>
                            <div>{{ __('Invoice') }}:
                                <strong>{{ $quotation?->reference }}</strong>
                            </div>
                            <div>{{ __('Date') }}:
                                {{ format_date($quotation?->date) }}</div>
                            <div>
                                {{ __('Status') }}: <strong>{{ $quotation?->status }}</strong>
                            </div>
                        </div>
                    </div>

                    <x-table>
                        <x-slot name="thead">
                            <x-table.th>{{ __('Product') }}</x-table.th>
                            <x-table.th>{{ __('Net Unit Price') }}</x-table.th>
                            <x-table.th>{{ __('Quantity') }}</x-table.th>
                            <x-table.th>{{ __('Discount') }}</x-table.th>
                            <x-table.th>{{ __('Tax') }}</x-table.th>
                            <x-table.th>{{ __('Sub Total') }}</x-table.th>
                        </x-slot>
                        <x-table.tbody>
                            @if ($quotation != null)
                                @foreach ($quotation?->quotationDetails as $item)
                                    <x-table.tr>
                                        <x-table.td>
                                            {{ $item->name }} <br>
                                            <span class="badge badge-success">
                                                {{ $item->code }}
                                            </span>
                                        </x-table.td>

                                        <x-table.td>{{ format_currency($item->unit_price) }}</x-table.td>

                                        <x-table.td>
                                            {{ $item->quantity }}
                                        </x-table.td>

                                        <x-table.td>
                                            {{ format_currency($item->product_discount_amount) }}
                                        </x-table.td>

                                        <x-table.td>
                                            {{ format_currency($item->product_tax_amount) }}
                                        </x-table.td>

                                        <x-table.td>
                                            {{ format_currency($item->sub_total) }}
                                        </x-table.td>
                                    </x-table.tr>
                                @endforeach
                            @endif
                        </x-table.tbody>
                    </x-table>

                    <div class="flex flex-row">
                        <div class="w-full px-4 mb-4">
                            <x-table-responsive>
                                <x-table.tr>
                                    <x-table.td>
                                        <strong>{{ __('Discount') }}
                                            ({{ $quotation?->discount_percentage }}%)</strong>
                                    </x-table.td>
                                    <x-table.td>
                                        {{ format_currency($quotation?->discount_amount) }}
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.td>
                                        <strong>{{ __('Tax') }}
                                            ({{ $quotation?->tax_percentage }}%)</strong>
                                    </x-table.td>
                                    <x-table.td>
                                        {{ format_currency($quotation?->tax_amount) }}
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.td>
                                        <strong>{{ __('Shipping') }}</strong>
                                    </x-table.td>
                                    <x-table.td>
                                        {{ format_currency($quotation?->shipping_amount) }}
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.td>
                                        <strong>{{ __('Grand Total') }}</strong>
                                    </x-table.td>
                                    <x-table.td>
                                        <strong>
                                            {{ format_currency($quotation?->total_amount) }}</strong>
                                    </x-table.td>
                                </x-table.tr>
                            </x-table-responsive>
                        </div>
                    </div>
                </div>
            </div>

        </x-slot>
    </x-modal>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', quotationId => {
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
                        window.livewire.emit('delete', quotationId)
                    }
                })
            })
        })
    </script>
@endpush
