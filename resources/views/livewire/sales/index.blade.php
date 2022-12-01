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
                <x-button danger type="button" wire:click="$toggle('showDeleteModal')" wire:loading.attr="disabled">
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
            @endif
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <div class="my-2">
                <input type="text" wire:model.debounce.300ms="search"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('customer_id')" :direction="$sorts['customer_id'] ?? null">
                {{ __('Customer') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('payment_status')" :direction="$sorts['payment_status'] ?? null">
                {{ __('Payment status') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('due_amount')" :direction="$sorts['due_amount'] ?? null">
                {{ __('Due Amount') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('total')" :direction="$sorts['total'] ?? null">
                {{ __('Total') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
                {{ __('Status') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($sales as $sale)
                <x-table.tr wire:loading.class.delay="opacity-50">
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $sale->id }}" wire:model="selected" />
                    </x-table.td>
                    <x-table.td>
                        {{ $sale->date }}
                    </x-table.td>
                    <x-table.td>
                        {{ $sale->customer->name }}
                    </x-table.td>
                    <x-table.td>
                        @if ($sale->payment_status == \App\Models\Sale::PaymentPaid)
                            <x-badge success>{{ __('Paid') }}</x-badge>
                        @elseif ($sale->payment_status == \App\Models\Sale::PaymentPartial)
                            <x-badge warning>{{ __('Partially Paid') }}</x-badge>
                        @elseif($sale->payment_status == \App\Models\Sale::PaymentDue)
                            <x-badge danger>{{ __('Due') }}</x-badge>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($sale->due_amount) }}
                    </x-table.td>

                    <x-table.td>
                        {{ format_currency($sale->total_amount) }}
                    </x-table.td>

                    <x-table.td>
                        @if ($sale->status == \App\Models\Sale::SalePending)
                            <x-badge warning>{{ __('Pending') }}</x-badge>
                        @elseif ($sale->status == \App\Models\Sale::SaleOrdered)
                            <x-badge info>{{ __('Ordered') }}</x-badge>
                        @elseif($sale->status == \App\Models\Sale::SaleCompleted)
                            <x-badge success>{{ __('Completed') }}</x-badge>
                        @endif
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
                                    <x-dropdown-link wire:click="showModal({{ $sale->id }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-eye"></i>
                                        {{ __('View') }}
                                    </x-dropdown-link>
                                    @can('edit_sales')
                                        <x-dropdown-link href="{{ route('sales.edit', $sale) }}"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-edit"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('delete_sales')
                                        <x-dropdown-link wire:click="$emit('deleteModal', {{ $sale->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-trash"></i>
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <x-dropdown-link target="_blank" href="{{ route('sales.pos.pdf', $sale->id) }}"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-print"></i>
                                        {{ __('Print') }}
                                    </x-dropdown-link>

                                    @can('access_sale_payments')
                                        <x-dropdown-link wire:click="$emit('showPayments', {{ $sale->id }})" primary
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ __('Payments') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('access_sale_payments')
                                        @if ($sale->due_amount > 0)
                                            <x-dropdown-link wire:click="paymentModal({{ $sale->id }})" primary
                                                wire:loading.attr="disabled">
                                                <i class="fas fa-money-bill-wave"></i>
                                                {{ __('Add Payment') }}
                                            </x-dropdown-link>
                                        @endif
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td>
                        <div class="flex justify-center items-center">
                            <span class="text-gray-400 dark:text-gray-300">{{ __('No results found') }}</span>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="px-6 py-3">
        {{ $sales->links() }}
    </div>

    {{-- Show Sale --}}
    @if (null !== $showModal)
        <div>
            <x-modal wire:model="showModal">
                <x-slot name="title">
                    {{ __('Show Sale') }} - {{ __('Reference') }}: <strong>{{ $sale->reference }}</strong>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 mx-auto">
                        <div class="flex flex-row">
                            <div class="w-full">
                                <div class="p-2 d-flex flex-wrap items-center">
                                    <x-button secondary class="d-print-none" target="_blank"
                                        wire:loading.attr="disabled" href="{{ route('sales.pdf', $sale->id) }}"
                                        class="ml-auto">
                                        <i class="fas fa-print"></i> {{ __('Print') }}
                                    </x-button>
                                    <x-button secondary class="d-print-none" target="_blank"
                                        wire:loading.attr="disabled" href="{{ route('sales.pdf', $sale->id) }}"
                                        class="ml-2">
                                        <i class="fas fa-download"></i> {{ __('Download') }}
                                    </x-button>
                                </div>
                                <div class="p-4">
                                    <div class="flex flex-row mb-4">
                                        <div class="md:w-1/3 mb-3 md:mb-0">
                                            <h5 class="mb-2 border-bottom pb-2">{{ __('Company Info') }}:</h5>
                                            <div><strong>{{ settings()->company_name }}</strong></div>
                                            <div>{{ settings()->company_address }}</div>
                                            
                                            <div>{{ __('Email') }}: {{ settings()->company_email }}</div>
                                            <div>{{ __('Phone') }}: {{ settings()->company_phone }}</div>
                                        </div>

                                        <div class="md:w-1/3 mb-3 md:mb-0">
                                            <h5 class="mb-2 border-bottom pb-2">{{ __('Customer Info') }}:</h5>
                                            <div><strong>{{ $sale->customer->name }}</strong></div>
                                            <div>{{ $sale->customer->address }}</div>
                                            <div>{{ __('Email') }}: {{ $sale->customer->email }}</div>
                                            <div>{{ __('Phone') }}: {{ $sale->customer->phone }}</div>
                                        </div>

                                        <div class="md:w-1/3 mb-3 md:mb-0">
                                            <h5 class="mb-2 border-bottom pb-2">{{ __('Invoice Info') }}:</h5>
                                            <div>{{ __('Invoice') }}: <strong>{{ settings()->sale_prefix }}/{{ $sale->reference }}</strong>
                                            </div>
                                            <div>{{ __('Date') }}:
                                                {{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</div>
                                            <div>
                                                {{ __('Status') }} :
                                                @if ($sale->status == \App\Models\Sale::SalePending)
                                                    <x-badge warning>{{ __('Pending') }}</x-badge>
                                                @elseif ($sale->status == \App\Models\Sale::SaleOrdered)
                                                    <x-badge info>{{ __('Ordered') }}</x-badge>
                                                @elseif($sale->status == \App\Models\Sale::SaleCompleted)
                                                    <x-badge success>{{ __('Completed') }}</x-badge>
                                                @endif
                                            </div>
                                            <div>
                                                {{ __('Payment Status') }} :
                                                @if ($sale->payment_status == \App\Models\Sale::PaymentPaid)
                                                    <x-badge success>{{ __('Paid') }}</x-badge>
                                                @elseif ($sale->payment_status == \App\Models\Sale::PaymentPartial)
                                                    <x-badge warning>{{ __('Partially Paid') }}</x-badge>
                                                @elseif($sale->payment_status == \App\Models\Sale::PaymentDue)
                                                    <x-badge danger>{{ __('Due') }}</x-badge>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="">
                                        <x-table>
                                            <x-slot name="thead">
                                                <x-table.th>{{ __('Product') }}</x-table.th>
                                                <x-table.th>{{ __('Quantity') }}</x-table.th>
                                                <x-table.th>{{ __('Unit Price') }}</x-table.th>
                                                <x-table.th>{{ __('Subtotal') }}</x-table.th>
                                            </x-slot>

                                            <x-table.tbody>
                                                @foreach ($sale->saleDetails as $item)
                                                    <x-table.tr>
                                                        <x-table.td>
                                                            {{ $item->name }} <br>
                                                            <x-badge success>
                                                                {{ $item->code }}
                                                            </x-badge>
                                                        </x-table.td>
                                                        <x-table.td>
                                                            {{ format_currency($item->unit_price) }}
                                                        </x-table.td>

                                                        <x-table.td>
                                                            {{ $item->quantity }}
                                                        </x-table.td>

                                                        <x-table.td>
                                                            {{ format_currency($item->sub_total) }}
                                                        </x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-table.tbody>
                                        </x-table>
                                    </div>
                                    <div class="flex flex-row">
                                        <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0 col-sm-5 ml-md-auto">
                                            <table class="table">
                                                <tbody>
                                                    @if ($sale->discount_percentage)
                                                    <tr>
                                                        <td class="left"><strong>{{ __('Discount') }}
                                                                ({{ $sale->discount_percentage }}%)</strong></td>
                                                        <td class="right">
                                                            {{ format_currency($sale->discount_amount) }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @if ($sale->tax_percentage)
                                                    <tr>
                                                        <td class="left"><strong>{{ __('Tax') }}
                                                                ({{ $sale->tax_percentage }}%)</strong></td>
                                                        <td class="right">{{ format_currency($sale->tax_amount) }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @if ( settings()->show_shipping == true )
                                                    <tr>
                                                        <td class="left"><strong>{{ __('Shipping') }}</strong></td>
                                                        <td class="right">
                                                            {{ format_currency($sale->shipping_amount) }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <td class="left"><strong>{{ __('Grand Total') }}</strong>
                                                        </td>
                                                        <td class="right">
                                                            <strong>{{ format_currency($sale->total_amount) }}</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-modal>
        </div>
    @endif
    {{-- End Show Sale --}}

    {{-- Import modal --}}
    <div>
        <x-modal wire:model="importModal">
            <x-slot name="title">
                {{ __('Import Excel') }}
            </x-slot>

            <x-slot name="content">
                <form wire:submit.prevent="import">
                    <div class="mb-4">

                        <div class="w-full px-3">
                            <x-label for="import" :value="__('Import')" />
                            <x-input id="import" class="block mt-1 w-full" type="file" name="import"
                                wire:model.defer="import_file" />
                            <x-input-error :messages="$errors->get('import')" for="import" class="mt-2" />
                        </div>

                        <div class="w-full px-3">
                            <x-button primary type="submit" class="w-full text-center" 
                             wire:loading.attr="disabled">
                                {{ __('Import') }}
                            </x-button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    </div>

    {{-- End Import modal --}}

    {{-- Sales Payment payment component   --}}
    @if (empty($showPayments))
        <livewire:sales.payment.index :sale="$sale" />
    @endif
    {{-- End Sales Payment payment component   --}}

    @if (!empty($paymentModal))
        <div>
            <x-modal wire:model="paymentModal">
                <x-slot name="title">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Sale Payment') }}
                    </h2>

                </x-slot>
                <x-slot name="content">
                    <form wire:submit.prevent="paymentSave">

                        <x-validation-errors class="mb-4" :errors="$errors" />

                        <div class="flex flex-wrap -mx-2 mb-3">

                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <x-label for="reference" :value="__('Reference')" required />
                                <x-input type="text" wire:model="reference" id="reference"
                                    class="block w-full mt-1" required />
                                <x-input-error :messages="$errors->first('reference')" />
                            </div>
                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <x-label for="date" :value="__('Date')" required />
                                <x-input type="date" wire:model="date" id="date" class="block w-full mt-1"
                                    required />
                                <x-input-error :messages="$errors->first('date')" />
                            </div>


                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <x-label for="amount" :value="__('Amount')" required />
                                <x-input type="text" wire:model.defer="amount" id="amount"
                                    class="block w-full mt-1" required />
                                <x-input-error :messages="$errors->first('amount')" />
                            </div>

                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <x-label for="payment_method" :value="__('Payment Method')" required />
                                <select wire:model="payment_method"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                    name="payment_method" id="payment_method" required>
                                    <option value="Cash">{{ __('Cash') }}</option>
                                    <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                                    <option value="Cheque">{{ __('Cheque') }}</option>
                                    <option value="Other">{{ __('Other') }}</option>
                                </select>
                                <x-input-error :messages="$errors->first('payment_method')" />
                            </div>
                        </div>

                        <div class="mb-4  px-3">
                            <x-label for="note" :value="__('Note')" />
                            <textarea wire:model="note"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                rows="2" name="note">{{ old('note') }}</textarea>
                        </div>

                        <div class="w-full flex justfiy-start px-3">
                            <x-button wire:click="paymentSave" primary type="button" wire:loading.attr="disabled">
                                {{ __('Save') }}
                            </x-button>
                        </div>
                    </form>
                </x-slot>
            </x-modal>
        </div>
    @endif


</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', saleId => {
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
                        window.livewire.emit('delete', saleId)
                    }
                })
            })
        })
    </script>
@endpush
