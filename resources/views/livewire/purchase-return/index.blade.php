<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model.live="perPage"
                class="w-20 block p-3 leading-5 bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <div class="my-2">
                <x-input wire:model.live.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
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
                        <input type="checkbox" value="{{ $purchasereturn->id }}" wire:model.live="selected" />
                    </x-table.td>
                    <x-table.td>
                        {{ $purchasereturn->reference }}
                    </x-table.td>
                    <x-table.td>
                        {{ $purchasereturn->date }}
                    </x-table.td>
                    <x-table.td>
                        <a href="{{ route('supplier.details', $purchasereturn->supplier->uuid) }}"
                            class="text-indigo-500 hover:text-indigo-600">
                            {{ $purchasereturn->supplier->name }}
                        </a>
                    </x-table.td>
                    <x-table.td>
                        @php
                            $type = $purchase_return->status->getBadgeType();
                        @endphp
                        <x-badge :type="$type">{{ $purchase_return->status->getName() }}</x-badge>
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($salereturn->total_amount) }}
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
                                        <x-dropdown-link wire:click="$dispatch('showPayments', {{ $purchasereturn->id }})"
                                            primary wire:loading.attr="disabled">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ __('Payments') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('purchase_payment_access')
                                        @if ($purchasereturn->due_amount > 0)
                                            <x-dropdown-link wire:click="paymentModal({{ $purchasereturn->id }})" primary
                                                wire:loading.attr="disabled">
                                                <i class="fas fa-money-bill-wave"></i>
                                                {{ __('Add Payment') }}
                                            </x-dropdown-link>
                                        @endif
                                    @endcan

                                    @can('purchase_return_access')
                                        <x-dropdown-link wire:click="showModal({{ $purchasereturn->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-eye"></i>
                                            {{ __('View') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('purchase_return_update')
                                        <x-dropdown-link href="{{ route('purchases.edit', $purchasereturn->id) }}"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-edit"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('purchase_return_delete')
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
    <x-modal wire:model.live="showModal">
        <x-slot name="title">
            <div class="w-full flex">
                {{ __('Show PurchaseReturn') }} - {{ __('Reference') }}:
                <strong>{{ $purchasereturn?->reference }}</strong>

                <div class="float-right">
                    @if ($purchasereturn != null)
                        <x-button secondary href="{{ route('purchase-returns.pdf', $purchasereturn->id) }}"
                            wire:loading.attr="disabled" target="_blank">
                            <i class="fas fa-file-pdf"></i>
                            {{ __('PDF') }}
                        </x-button>
                    @endif
                </div>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="px-4 mx-auto">
                <div class="flex flex-row">
                    <div class="w-full px-4">
                        <div class="flex flex-row mb-4">
                            <div class="w-1/4 mb-3">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Company Info') }}:</h5>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                @if (settings()->show_email == true)
                                    <div>{{ __('Email') }}: {{ settings()->company_email }}</div>
                                @endif
                                <div>{{ __('Phone') }}: {{ settings()->company_phone }}</div>
                            </div>

                            <div class="w-1/4 mb-3">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Supplier Info') }}:</h5>
                                <div><strong>{{ $purchasereturn?->supplier->name }}</strong></div>
                                @if (settings()->show_address == true)
                                    <div>{{ $purchasereturn?->supplier->address }}</div>
                                @endif
                                @if (settings()->show_email == true)
                                    <div>{{ __('Email') }}: {{ $purchasereturn?->supplier->email }}</div>
                                @endif
                                <div>{{ __('Phone') }}: {{ $purchasereturn?->supplier->phone }}</div>
                            </div>

                            <div class="w-1/4 mb-3">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Invoice Info') }}:</h5>
                                <div>{{ __('Invoice') }}:
                                    <strong>INV/{{ $purchasereturn?->reference }}</strong>
                                </div>
                                <div>{{ __('Date') }}:
                                    {{ format_date($purchasereturn?->date) }}
                                </div>
                                <div>
                                    {{ __('Status') }}: <strong>{{ $purchasereturn?->status }}</strong>
                                </div>
                                <div>
                                    {{ __('Payment Status') }}:
                                    <strong>{{ $purchasereturn?->payment_status }}</strong>
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
                                    @if ($purchasereturn != null)
                                        @foreach ($purchasereturn->purchaseReturnDetails as $item)
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
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="w-full px-4 mb-4">
                                <table class="table">
                                    <tbody>
                                        @if ($purchasereturn?->discount_percentage)
                                            <tr>
                                                <td class="left"><strong>{{ __('Discount') }}
                                                        ({{ $purchasereturn?->discount_percentage }}%)</strong>
                                                </td>
                                                <td class="right">
                                                    {{ format_currency($purchasereturn?->discount_amount) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($purchasereturn?->tax_percentage)
                                            <tr>
                                                <td class="left">
                                                    <strong>{{ __('Tax') }}
                                                        ({{ $purchasereturn?->tax_percentage }}%)
                                                    </strong>
                                                </td>
                                                <td class="right">
                                                    {{ format_currency($purchasereturn?->tax_amount) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if (settings()->show_shipping == true)
                                            <tr>
                                                <td class="left"><strong>{{ __('Shipping') }}</strong></td>
                                                <td class="right">
                                                    {{ format_currency($purchasereturn?->shipping_amount) }}
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="left"><strong>{{ __('Grand Total') }}</strong>
                                            </td>
                                            <td class="right">
                                                <strong>{{ format_currency($purchasereturn?->total_amount) }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </x-slot>
    </x-modal>
    {{--  End ShowModal --}}

    {{-- PurchaseReturn Payment payment component   --}}
    {{-- @if (empty($purchasereturn)) --}}
        <livewire:purchase.payment.index :purchasereturn="$purchasereturn" />
    {{-- @endifp --}}
    {{-- End PurchaseReturn Payment payment component   --}}

    @if (!empty($paymentModal))
        <div>
            <x-modal wire:model.live="paymentModal">
                <x-slot name="title">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('PurchaseReturn Payment') }}
                    </h2>

                </x-slot>
                <x-slot name="content">
                    <form wire:submit="paymentSave">

                        <x-validation-errors class="mb-4" :errors="$errors" />

                        <div class="flex flex-wrap mb-3">

                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <x-label for="reference" :value="__('Reference')" required />
                                <x-input type="text" wire:model.live="reference" id="reference"
                                    class="block w-full mt-1" required />
                                <x-input-error :messages="$errors->first('reference')" />
                            </div>
                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <x-label for="date" :value="__('Date')" required />
                                <x-input type="date" wire:model.live="date" id="date" class="block w-full mt-1"
                                    required />
                                <x-input-error :messages="$errors->first('date')" />
                            </div>


                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <x-label for="amount" :value="__('Amount')" required />
                                <x-input type="text" wire:model="amount" id="amount"
                                    class="block w-full mt-1" required />
                                <x-input-error :messages="$errors->first('amount')" />
                            </div>

                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <x-label for="payment_method" :value="__('Payment Method')" required />
                                <select wire:model.live="payment_method"
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
                            <textarea wire:model.live="note"
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
