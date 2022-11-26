<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
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
            <x-table.th sortable wire:click="sortBy('reference')" :direction="$sorts['reference'] ?? null">
                {{ __('Reference') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('supplier_id')" :direction="$sorts['supplier_id'] ?? null">
                {{ __('Supplier') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
                {{ __('Status') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('email')" :direction="$sorts['email'] ?? null">
                {{ __('Total') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($purchasereturns as $purchasereturn)
                <x-table.tr>
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $purchasereturn->id }}" wire:model="selected" />
                    </x-table.td>
                    <x-table.td>
                        {{ $purchasereturn->reference }}
                    </x-table.td>
                    <x-table.td>
                        {{ $purchasereturn->date }}
                    </x-table.td>
                    <x-table.td>
                        {{ $purchasereturn->supplier->name }}
                    </x-table.td>
                    <x-table.td>
                        @if ($purchasereturn->status == \App\Models\PurchaseReturn::PurchaseReturnPending)
                            <x-badge warning>{{ __('Pending') }}</x-badge>
                        @elseif ($purchasereturn->status == \App\Models\PurchaseReturn::PurchaseReturnCanceled)
                            <x-badge info>{{ __('Canceled') }}</x-badge>
                        @elseif($purchasereturn->status == \App\Models\PurchaseReturn::PurchaseReturnCompleted)
                            <x-badge success>{{ __('Completed') }}</x-badge>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($salereturn->total_amount) }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-dropdown align="right" class="w-auto">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-button primary type="button" class="text-white flex items-center">
                                        <i class="fas fa-angle-double-down"></i>
                                    </x-button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('access_purchase_payments')
                                        <x-dropdown-link wire:click="$emit('showPayments', {{ $purchasereturn->id }})"
                                            primary wire:loading.attr="disabled">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ __('Payments') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('access_purchase_payments')
                                        @if ($purchasereturn->due_amount > 0)
                                            <x-dropdown-link wire:click="paymentModal({{ $purchasereturn->id }})" primary
                                                wire:loading.attr="disabled">
                                                <i class="fas fa-money-bill-wave"></i>
                                                {{ __('Add Payment') }}
                                            </x-dropdown-link>
                                        @endif
                                    @endcan

                                    @can('show_purchases')
                                        <x-dropdown-link wire:click="showModal({{ $purchasereturn->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-eye"></i>
                                            {{ __('View') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('edit_purchases')
                                        <x-dropdown-link href="{{ route('purchases.edit', $purchasereturn->id) }}"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-edit"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('delete_purchases')
                                        <x-dropdown-link wire:click="confirm('delete', {{ $purchasereturn->id }})"
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
                    <x-table.td colspan="7">
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
        {{ $purchasereturns->links() }}
    </div>


    {{-- Show PurchaseReturn --}}
    @if (null !== $showModal)
        <x-modal wire:model="showModal">
            <x-slot name="title">
                <div class="w-full flex">
                    {{ __('Show PurchaseReturn') }} - {{ __('Reference') }}:
                    <strong>{{ $purchasereturn->reference }}</strong>

                    <div class="float-right">
                        <x-button secondary href="{{ route('purchases.pdf', $purchasereturn->id) }}"
                            wire:loading.attr="disabled">
                            <i class="fas fa-file-pdf"></i>
                            {{ __('PDF') }}
                        </x-button>
                        <x-button secondary href="{{ route('purchases.pdf', $purchasereturn->id) }}"
                            wire:loading.attr="disabled">
                            <i class="fas fa-print"></i>
                            {{ __('Save') }}
                        </x-button>
                    </div>
                </div>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 mx-auto">
                    <div class="flex flex-row">
                        <div class="w-full px-4">
                            <div class="card">
                                <div class="card-header d-flex flex-wrap align-items-center">
                                    <div>
                                        {{ __('Reference') }}: <strong>{{ $purchase_return->reference }}</strong>
                                    </div>
                                    <a target="_blank" class="btn-secondary mfs-auto mfe-1 d-print-none"
                                        href="{{ route('purchase-returns.pdf', $purchase_return->id) }}">
                                        <i class="bi bi-printer"></i> {{ __('Print') }}
                                    </a>
                                    <a target="_blank" class="btn-info mfe-1 d-print-none"
                                        href="{{ route('purchase-returns.pdf', $purchase_return->id) }}">
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
                                            <h5 class="mb-2 border-bottom pb-2">{{ __('Supplier Info') }}:</h5>
                                            <div><strong>{{ $supplier->name }}</strong></div>
                                            @if ( settings()->show_address == true )
                                            <div>{{ $supplier->address }}</div>
                                            @endif
                                            @if ( settings()->show_email == true )
                                            <div>{{ __('Email') }}: {{ $supplier->email }}</div>
                                            @endif
                                            <div>{{ __('Phone') }}: {{ $supplier->phone }}</div>
                                        </div>

                                        <div class="w-1/4 mb-3">
                                            <h5 class="mb-2 border-bottom pb-2">{{ __('Invoice Info') }}:</h5>
                                            <div>{{ __('Invoice') }}:
                                                <strong>INV/{{ $purchase_return->reference }}</strong></div>
                                            <div>{{ __('Date') }}:
                                                {{ \Carbon\Carbon::parse($purchase_return->date)->format('d M, Y') }}
                                            </div>
                                            <div>
                                                {{ __('Status') }}: <strong>{{ $purchase_return->status }}</strong>
                                            </div>
                                            <div>
                                                {{ __('Payment Status') }}:
                                                <strong>{{ $purchase_return->payment_status }}</strong>
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
                                                @foreach ($purchase_return->purchaseReturnDetails as $item)
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
                                    <div class="row">
                                        <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0 col-sm-5 ml-md-auto">
                                            <table class="table">
                                                <tbody>
                                                    @if( $purchase_return->discount_percentage )
                                                    <tr>
                                                        <td class="left"><strong>{{ __('Discount') }}
                                                                ({{ $purchase_return->discount_percentage }}%)</strong>
                                                        </td>
                                                        <td class="right">
                                                            {{ format_currency($purchase_return->discount_amount) }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @if ( $purchase_return->tax_percentage )
                                                    <tr>
                                                        <td class="left">
                                                            <strong>{{ __('Tax') }}
                                                                ({{ $purchase_return->tax_percentage }}%)
                                                            </strong>
                                                        </td>
                                                        <td class="right">
                                                            {{ format_currency($purchase_return->tax_amount) }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @if ( settings()->show_shipping == true )
                                                    <tr>
                                                        <td class="left"><strong>{{ __('Shipping') }}</strong></td>
                                                        <td class="right">
                                                            {{ format_currency($purchase_return->shipping_amount) }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <td class="left"><strong>{{ __('Grand Total') }}</strong>
                                                        </td>
                                                        <td class="right">
                                                            <strong>{{ format_currency($purchase_return->total_amount) }}</strong>
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
    @endif

    {{-- PurchaseReturn Payment payment component   --}}
    <div>
        {{-- if showPayments livewire proprety empty don't show --}}
        @if (empty($purchasereturn))
            <livewire:purchase.payment.index :purchase="$purchasereturn" />
        @endif
    </div>
    {{-- End PurchaseReturn Payment payment component   --}}

    @if (!empty($paymentModal))
        <div>
            <x-modal wire:model="paymentModal">
                <x-slot name="title">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('PurchaseReturn Payment') }}
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
                        window.livewire.emit('delete', purchaseId)
                    }
                })
            })
        })
    </script>
@endpush
