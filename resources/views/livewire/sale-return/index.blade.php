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
            <x-table.th sortable wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('customer_id')" :direction="$sorts['customer_id'] ?? null">
                {{ __('Customer') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('payment_status')" :direction="$sorts['payment_status'] ?? null">
                {{ __('Payment status') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('due_amount')" :direction="$sorts['due_amount'] ?? null">
                {{ __('Due Amount') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('total')" :direction="$sorts['total'] ?? null">
                {{ __('Total') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
                {{ __('Status') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($salereturns as $salereturn)
                <x-table.tr wire:loading.class.delay="opacity-50">
                    <x-table.td>
                        <input type="checkbox" value="{{ $salereturn->id }}" wire:model="selected" />
                    </x-table.td>
                    <x-table.td>
                        {{ $salereturn->date }}
                    </x-table.td>
                    <x-table.td>
                        {{ $salereturn->customer->name }}
                    </x-table.td>
                    <x-table.td>
                        @if ($salereturn->payment_status == \App\Models\SaleReturn::PaymentPaid)
                            <x-badge success>{{ __('Paid') }}</x-badge>
                        @elseif ($salereturn->payment_status == \App\Models\SaleReturn::PaymentPartial)
                            <x-badge warning>{{ __('Partially Paid') }}</x-badge>
                        @elseif($salereturn->payment_status == \App\Models\SaleReturn::PaymentDue)
                            <x-badge danger>{{ __('Due') }}</x-badge>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($salereturn->due_amount) }}
                    </x-table.td>

                    <x-table.td>
                        {{ format_currency($salereturn->total_amount) }}
                    </x-table.td>

                    <x-table.td>
                        @if ($salereturn->status == \App\Models\SaleReturn::SaleReturnPending)
                            <x-badge warning>{{ __('Pending') }}</x-badge>
                        @elseif ($salereturn->status == \App\Models\SaleReturn::SaleReturnCompleted)
                            <x-badge success>{{ __('Completed') }}</x-badge>
                        @elseif($salereturn->status == \App\Models\SaleReturn::SaleReturnCanceld)
                            <x-badge danger>{{ __('Canceled') }}</x-badge>
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
                                    @can('show_salereturn')
                                        <x-dropdown-link wire:click="showModal({{ $salereturn->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-eye"></i>
                                            {{ __('View') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('edit_salereturn')
                                        <x-dropdown-link href="{{ route('sale-returns.edit', $salereturn) }}"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-edit"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('delete_salereturn')
                                        <x-dropdown-link wire:click="$emit('deleteModal', {{ $salereturn->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-trash"></i>
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('show_salereturn_payments')
                                        <x-dropdown-link wire:click="$emit('showPayments', {{ $salereturn->id }})" primary
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ __('Payments') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('create_salereturn_payments')
                                        @if ($salereturn->due_amount > 0)
                                            <x-dropdown-link wire:click="paymentModal({{ $salereturn->id }})" primary
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
        {{ $salereturns->links() }}
    </div>

    {{-- Show SaleReturn --}}
    @if (null !== $showModal)
        <div>
            <x-modal wire:model="showModal">
                <x-slot name="title">
                    {{ __('Show SaleReturn') }} - {{ __('Reference') }}:
                    <strong>{{ $salereturn->reference }}</strong>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 mx-auto">
                        <div class="flex flex-row">
                            <div class="w-full px-4">
                                <div class="card">
                                    <div class="card-header d-flex flex-wrap align-items-center">
                                        <div>
                                            {{ __('Reference') }}: <strong>{{ $this->salereturn->reference }}</strong>
                                        </div>
                                        <a target="_blank" class="btn-secondary mfs-auto mfe-1 d-print-none"
                                            href="{{ route('sale-returns.pdf', $this->salereturn->id) }}">
                                            <i class="bi bi-printer"></i> {{ __('Print') }}
                                        </a>
                                        <a target="_blank" class="btn-info mfe-1 d-print-none"
                                            href="{{ route('sale-returns.pdf', $this->salereturn->id) }}">
                                            <i class="bi bi-save"></i> {{ __('Save') }}
                                        </a>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex flex-row mb-4">
                                            <div class="w-1/4 mb-3">
                                                <h5 class="mb-2 border-bottom pb-2">{{ __('Company Info') }}:</h5>
                                                <div><strong>{{ settings()->company_name }}</strong></div>
                                                <div>{{ settings()->company_address }}</div>
                                                @if ( settings()->show_email == true )
                                                <div>{{ __('Email') }}: {{ settings()->company_email }}</div>
                                                @endif
                                                <div>{{ __('Phone') }}: {{ settings()->company_phone }}</div>
                                            </div>

                                            <div class="w-1/4 mb-3">
                                                <h5 class="mb-2 border-bottom pb-2">{{ __('Customer Info') }}:</h5>
                                                <div><strong>{{ $this->salereturn->customer->name }}</strong></div>
                                                @if ( settings()->show_address == true )
                                                <div>{{ $this->salereturn->customer->address }}</div>
                                                @endif
                                                @if ( settings()->show_email == true )
                                                <div>{{ $this->salereturn->customer->email }}</div>
                                                @endif
                                                <div>{{ __('Phone') }}: {{ $this->salereturn->customer->phone }}
                                                </div>
                                            </div>

                                            <div class="w-1/4 mb-3">
                                                <h5 class="mb-2 border-bottom pb-2">{{ __('Invoice Info') }}:</h5>
                                                <div>{{ __('Invoice') }}:
                                                    <strong>INV/{{ $this->salereturn->reference }}</strong></div>
                                                <div>{{ __('Date') }}:
                                                    {{ \Carbon\Carbon::parse($this->salereturn->date)->format('d M, Y') }}
                                                </div>
                                                <div>
                                                    {{ __('Status') }}: <strong>
                                                        @if ($this->salereturn == \App\Models\SaleReturn::SaleReturnPending)
                                                            <x-badge warning>{{ __('Pending') }}</x-badge>
                                                        @elseif ($this->salereturn == \App\Models\SaleReturn::SaleReturnCompleted)
                                                            <x-badge success>{{ __('Completed') }}</x-badge>
                                                        @elseif($this->salereturn == \App\Models\SaleReturn::SaleReturnCanceld)
                                                            <x-badge danger>{{ __('Canceled') }}</x-badge>
                                                        @endif
                                                    </strong>
                                                </div>
                                                <div>
                                                    {{ __('Payment Status') }}:
                                                    <strong>{{ $this->salereturn->payment_status }}</strong>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="table-responsive-sm">
                                            <table class="table table-striped">
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
                                                    @foreach ($this->salereturn->saleReturnDetails as $item)
                                                        <tr>
                                                            <td class="align-middle">
                                                                {{ $item->name }} <br>
                                                                <span class="badge badge-success">
                                                                    {{ $item->code }}
                                                                </span>
                                                            </td>

                                                            <td class="align-middle">
                                                                {{ format_currency($item->unit_price) }}</td>

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
                                        <div class="flex flex-row">
                                            <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0 col-sm-5 ml-md-auto">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="left"><strong>{{ __('Discount') }}
                                                                    ({{ $this->salereturn->discount_percentage }}%)</strong>
                                                            </td>
                                                            <td class="right">
                                                                {{ format_currency($this->salereturn->discount_amount) }}
                                                            </td>
                                                        </tr>
                                                        @if()
                                                        <tr>
                                                            <td class="left"><strong>{{ __('Tax') }}
                                                                    ({{ $this->salereturn->tax_percentage }}%)</strong>
                                                            </td>
                                                            <td class="right">
                                                                {{ format_currency($this->salereturn->tax_amount) }}
                                                            </td>
                                                        </tr>
                                                        @if ( settings()->show_shipping == true )
                                                        <tr>
                                                            <td class="left"><strong>{{ __('Shipping') }}</strong>
                                                            </td>
                                                            <td class="right">
                                                                {{ format_currency($this->salereturn->shipping_amount) }}
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <td class="left">
                                                                <strong>{{ __('Grand Total') }}</strong></td>
                                                            <td class="right">
                                                                <strong>{{ format_currency($this->salereturn->total_amount) }}</strong>
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
                    </div>
                </x-slot>
            </x-modal>
        </div>
    @endif
    {{-- End Show SaleReturn --}}

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
    <div>
        {{-- if showPayments livewire proprety empty don't show --}}
        @if (empty($showPayments))
            <livewire:sales.payment.index :sale="$salereturn" />
        @endif
    </div>
    {{-- End Sales Payment payment component   --}}

    @if (!empty($paymentModal))
        <div>
            <x-modal wire:model="paymentModal">
                <x-slot name="title">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('SaleReturn Payment') }}
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
