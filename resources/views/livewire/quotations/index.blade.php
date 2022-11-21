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
            <div class="my-2 my-md-0">
                <input type="text" wire:model.debounce.300ms="search"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>
    <div wire:loading.delay class="flex justify-center">
        <x-loading />
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th >
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                {{ __('Id') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('customer_id')" :direction="$sorts['customer_id'] ?? null">
                {{ __('Customer') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('total')" :direction="$sorts['total'] ?? null">
                {{ __('Total') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
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
                        {{ $quotation->reference }}
                    </x-table.td>
                    <x-table.td>
                        {{ $quotation->date }}
                    </x-table.td>
                    <x-table.td>
                        {{ $quotation->customer->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $quotation->total_amount }}
                    </x-table.td>
                    <x-table.td>
                        @if ($quotation->status == 'Pending')
                            <x-badge info>
                                {{ $quotation->status }}
                            </x-badge>
                        @else
                            <x-badge success>
                                {{ $quotation->status }}
                            </x-badge>
                        @endif
                    </x-table.td>
                    <x-table.td class="whitespace-no-wrap row-action--icon">
                        <x-dropdown align="right" class="w-auto">
                            <x-slot name="trigger" class="inline-flex">
                                <x-button primary type="button" class="text-white flex items-center">
                                    {{ __('Actions') }}
                                </x-button>
                            </x-slot>
                            <x-slot name="content">
                                @can('create_quotation_sales')
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
                                @can('edit_quotations')
                                    <x-dropdown-link href="{{ route('quotations.edit', $quotation->id) }}">
                                        <i class="fas fa-edit mr-2"></i>
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('show_quotations')
                                    <x-dropdown-link href="{{ route('quotations.show', $quotation->id) }}">
                                        <i class="fas fa-eye mr-2"></i>
                                        {{ __('Show') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('delete_quotations')
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

    <x-modal wire:model="create">
        <x-slot name="title">
            {{ __('Create Quotation') }}
        </x-slot>

        <x-slot name="content">
            <div class"w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
                <x-label for="date" :value="__('Date')" />
                <x-input id="date" class="block mt-1 w-full" type="date" wire:model.defer="date" />
                <x-input-error :messages="$errors->get('date')" for="date" class="mt-2" />
            </div>

            <div class"w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
                <x-label for="customer_id" :value="__('Customer')" />
                <x-select-list
                    class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                    id="customer_id" name="customer_id" wire:model="product.customer_id" :options="$this->listsForFields['customers']" />

                <x-input-error :messages="$errors->get('customer_id')" for="customer_id" class="mt-2" />
            </div>
        </x-slot>
    </x-modal>

    <x-modal wire:model="update">
        <x-slot name="title">
            {{ __('Update Quotation') }}
        </x-slot>

        <x-slot name="content">
            <div class"w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
                <x-label for="date" :value="__('Date')" />
                <x-input id="date" class="block mt-1 w-full" type="date" wire:model.defer="date" />
                <x-input-error :messages="$errors->get('date')" for="date" class="mt-2" />
            </div>

            <div class"w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
                <x-label for="customer_id" :value="__('Customer')" />
                <x-select-list
                    class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                    id="customer_id" name="customer_id" wire:model="product.customer_id" :options="$this->listsForFields['customers']" />
                <x-input-error :messages="$errors->get('customer_id')" for="customer_id" class="mt-2" />
            </div>
        </x-slot>
    </x-modal>

    <x-modal wire:model="show">
        <x-slot name="title">
            {{ __('Show Quotation') }}
        </x-slot>

        <x-slot name="content">
            <div class="w-full px-4">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center">
                        <div>
                            {{ __('Reference') }}: <strong>{{ $quotation->reference }}</strong>
                        </div>
                        <a target="_blank" class="btn-secondary mfs-auto mfe-1 d-print-none"
                            href="{{ route('quotations.pdf', $quotation->id) }}">
                            <i class="bi bi-printer"></i> {{ __('Print') }}
                        </a>
                        <a target="_blank" class="btn-info mfe-1 d-print-none"
                            href="{{ route('quotations.pdf', $quotation->id) }}">
                            <i class="bi bi-save"></i> {{ __('Save') }}
                        </a>
                    </div>
                    <div class="p-4">
                        <div class="row mb-4">
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Company Info') }}:</h5>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                <div>{{ __('Email') }}: {{ settings()->company_email }}</div>
                                <div>{{ __('Phone') }}: {{ settings()->company_phone }}</div>
                            </div>

                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Customer Info') }}:</h5>
                                <div><strong>{{ $customer->name }}</strong></div>
                                <div>{{ $customer->address }}</div>
                                <div>{{ __('Email') }}: {{ $customer->cemail }}</div>
                                <div>{{ __('Phone') }}: {{ $customer->cphone }}</div>
                            </div>

                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Invoice Info') }}:</h5>
                                <div>{{ __('Invoice') }}: <strong>INV/{{ $quotation->reference }}</strong></div>
                                <div>{{ __('Date') }}:
                                    {{ \Carbon\Carbon::parse($quotation->date)->format('d M, Y') }}</div>
                                <div>
                                    {{ __('Status') }}: <strong>{{ $quotation->status }}</strong>
                                </div>
                                <div>
                                    {{ __('Payment Status') }}: <strong>{{ $quotation->payment_status }}</strong>
                                </div>
                            </div>

                        </div>

                        <div class="">
                            <table class="table-auto">
                                <thead>
                                    <tr>
                                        <th class="align-middle">{{ __('Product') }}</th>
                                        <th class="align-middle">{{ __('Net Unit Price') }}</th>
                                        <th class="align-middle">{{ __('Quantity') }}</th>
                                        <th class="align-middle">{{ __('Discount') }}</th>
                                        <th class="align-middle">{{ __('Tax') }}</th>
                                        <th class="align-middle">{{ __('Sub Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotation->quotationDetails as $item)
                                        <tr>
                                            <td class="align-middle">
                                                {{ $item->name }} <br>
                                                <span class="badge badge-success">
                                                    {{ $item->code }}
                                                </span>
                                            </td>

                                            <td class="align-middle">{{ format_currency($item->unit_price) }}</td>

                                            <td class="align-middle">
                                                {{ $item->quantity }}
                                            </td>

                                            <td class="align-middle">
                                                {{ format_currency($item->product_discount_amount) }}
                                            </td>

                                            <td class="align-middle">
                                                {{ format_currency($item->product_tax_amount) }}
                                            </td>

                                            <td class="align-middle">
                                                {{ format_currency($item->sub_total) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0 col-sm-5 ml-md-auto">
                                <table class="table-auto">
                                    <tbody>
                                        <tr>
                                            <td class="left"><strong>{{ __('Discount') }}
                                                    ({{ $quotation->discount_percentage }}%)</strong></td>
                                            <td class="right">{{ format_currency($quotation->discount_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="left"><strong>{{ __('Tax') }}
                                                    ({{ $quotation->tax_percentage }}%)</strong></td>
                                            <td class="right">{{ format_currency($quotation->tax_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="left"><strong>{{ __('Shipping') }}</strong></td>
                                            <td class="right">{{ format_currency($quotation->shipping_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="left"><strong>{{ __('Grand Total') }}</strong></td>
                                            <td class="right">
                                                <strong>{{ format_currency($quotation->total_amount) }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
