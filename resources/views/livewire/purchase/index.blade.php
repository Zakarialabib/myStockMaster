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
                <x-input wire:model.lazy="search" placeholder="{{ __('Search') }}" autofocus />
            </div>
        </div>
        <div class="w-full mb-2 flex flex-wrap justify-center">
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <div class="mb-4">
                    <label>{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                    <x-input wire:model="startDate" type="date" name="startDate" />
                    @error('startDate')
                        <span class="text-danger mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <div class="mb-4">
                    <label>{{ __('End Date') }} <span class="text-red-500">*</span></label>
                    <x-input wire:model="endDate" type="date" name="endDate" />
                    @error('endDate')
                        <span class="text-danger mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="w-full space-x-2 md:w-1/3 flex mx-0 my-auto px-2 mb-2">
                <x-button type="button" primary wire:click="filterByType('day')">{{ __('Today') }}</x-button>
                <x-button type="button" info wire:click="filterByType('month')">{{ __('This Month') }}</x-button>
                <x-button type="button" warning wire:click="filterByType('year')">{{ __('This Year') }}</x-button>
            </div>
        </div>
    </div>
    <div>
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
                            <input type="checkbox" value="{{ $purchase->id }}" wire:model="selected" />
                        </x-table.td>
                        <x-table.td>
                            {{ $purchase->reference }}
                        </x-table.td>
                        <x-table.td>
                            {{ format_date($purchase->date) }}
                        </x-table.td>
                        <x-table.td>
                            <a class="text-blue-400 hover:text-blue-600 focus:text-blue-600"
                                href="{{ route('supplier.details', $purchase->supplier->uuid) }}">
                                {{ $purchase->supplier->name }}
                            </a>
                        </x-table.td>
                        <x-table.td>
                            @php
                                $badgeType = $purchase->status->getBadgeType();
                            @endphp

                            <x-badge :type="$badgeType">{{ $purchase->status->getName() }}</x-badge>

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
                                        @can('access_purchase_payments')
                                            <x-dropdown-link wire:click="$emit('showPayments', {{ $purchase->id }})"
                                                wire:loading.attr="disabled">
                                                <i class="fas fa-money-bill-wave"></i>
                                                {{ __('Payments') }}
                                            </x-dropdown-link>
                                        @endcan

                                        @can('access_purchase_payments')
                                            @if ($purchase->due_amount > 0)
                                                <x-dropdown-link wire:click="paymentModal({{ $purchase->id }})"
                                                    wire:loading.attr="disabled">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                    {{ __('Add Payment') }}
                                                </x-dropdown-link>
                                            @endif
                                        @endcan

                                        @can('purchase_access')
                                            <x-dropdown-link wire:click="showModal({{ $purchase->id }})"
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

                                        <x-dropdown-link target="_blank"
                                            href="{{ route('purchases.pdf', $purchase->id) }}"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-print"></i>
                                            {{ __('Print') }}
                                        </x-dropdown-link>

                                        @can('purchase_delete')
                                            <x-dropdown-link wire:click="$emit('deleteModal', {{ $purchase->id }})"
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
    </div>
    <div class="mt-4">
        {{ $purchases->links() }}
    </div>


    {{-- ShowModal Purchase --}}
    <x-modal wire:model="showModal">
        <x-slot name="title">
            <div class="w-full flex">
                {{ __('Show Purchase') }} - {{ __('Reference') }}: <strong>{{ $purchase?->reference }}</strong>

                <div class="float-right">
                    @if ($purchase != null)
                        <x-button secondary href="{{ route('purchases.pdf', $purchase->id) }}" target="_blank"
                            wire:loading.attr="disabled">
                            <i class="fas fa-file-pdf"></i>
                            {{ __('PDF') }}
                        </x-button>
                    @endif
                </div>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="px-2 mx-auto py-4">
                <div class="flex flex-row">
                    <div class="w-full">
                        <div class="flex flex-row mb-4">
                            <div class="md:w-1/2 mb-3 md:mb-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Supplier Info') }}:</h5>
                                <div><strong>{{ $purchase?->supplier->name }}</strong></div>
                                <div>{{ $purchase?->supplier->address }}</div>
                                <div>{{ __('Email') }}: {{ $purchase?->supplier->email }}</div>
                                <div>{{ __('Phone') }}: {{ $purchase?->supplier->phone }}</div>
                            </div>

                            <div class="md:w-1/2 mb-3 md:mb-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Invoice Info') }}:</h5>
                                <div>{{ __('Invoice') }}:
                                    {{ $purchase?->reference }}</strong></div>
                                <div>{{ __('Date') }}:
                                    {{ format_date($purchase?->date) }}</div>
                                <div>
                                    {{ __('Status') }}:
                                    @php
                                        $badgeType = $purchase?->status->getBadgeType();
                                    @endphp

                                    <x-badge :type="$badgeType">{{ $purchase?->status->getName() }}</x-badge>

                                </div>
                                <div>
                                    {{ __('Payment Status') }} :
                                    @php
                                        $type = $purchase?->payment_status->getBadgeType();
                                    @endphp
                                    <x-badge :type="$type">{{ $purchase?->payment_status->getName() }}</x-badge>
                                </div>
                            </div>
                        </div>

                        <div class="w-full">
                            <x-table>
                                <x-slot name="thead">
                                    <x-table.th>{{ __('Product') }}</x-table.th>
                                    <x-table.th>{{ __('Unit Cost') }}</x-table.th>
                                    <x-table.th>{{ __('Quantity') }}</x-table.th>
                                    <x-table.th>{{ __('Subtotal') }}</x-table.th>
                                </x-slot>
                                <x-table.tbody>
                                    @if ($purchase != null)
                                        @foreach ($purchase->purchaseDetails as $item)
                                            <x-table.tr>
                                                <x-table.td class="align-middle">
                                                    {{ $item->name }} <br>
                                                    <x-badge type="primary">
                                                         {{ $item->code }}
                                                    </x-badge>
                                                </x-table.td>

                                                <x-table.td class="align-middle">
                                                    {{ format_currency($item->unit_price) }}
                                                </x-table.td>

                                                <x-table.td class="align-middle">
                                                    {{ $item->quantity }}
                                                </x-table.td>

                                                <x-table.td class="align-middle">
                                                    {{ format_currency($item->sub_total) }}
                                                </x-table.td>
                                            </x-table.tr>
                                        @endforeach
                                    @endif
                                </x-table.tbody>
                            </x-table>
                        </div>
                        <div class="flex flex-row">
                            <div class="w-full px-4 mb-4">
                                <x-table-responsive>
                                    @if ($purchase?->discount_percentage)
                                        <x-table.tr>
                                            <x-table.heading class="left">
                                                <strong>{{ __('Discount') }}
                                                    ({{ $purchase?->discount_percentage }}%)</strong>
                                            </x-table.heading>
                                            <x-table.td class="right">
                                                {{ format_currency($purchase?->discount_amount) }}</x-table.td>
                                        </x-table.tr>
                                    @endif
                                    @if ($purchase?->tax_percentage)
                                        <x-table.tr>
                                            <x-table.heading class="left">
                                                <strong>{{ __('Tax') }}
                                                    ({{ $purchase?->tax_percentage }}%)</strong>
                                            </x-table.heading>
                                            <x-table.td class="right">
                                                {{ format_currency($purchase?->tax_amount) }}
                                            </x-table.td>
                                        </x-table.tr>
                                    @endif
                                    @if (settings()->show_shipping == true)
                                        <x-table.tr>
                                            <x-table.heading class="left">
                                                <strong>{{ __('Shipping') }}</strong>
                                            </x-table.heading>
                                            <x-table.td class="right">
                                                {{ format_currency($purchase?->shipping_amount) }}</x-table.td>
                                        </x-table.tr>
                                    @endif
                                    <x-table.tr>
                                        <x-table.heading class="left">
                                            <strong>{{ __('Grand Total') }}</strong>
                                        </x-table.heading>
                                        <x-table.td class="right">
                                            <strong>{{ format_currency($purchase?->total_amount) }}</strong>
                                        </x-table.td>
                                    </x-table.tr>
                                </x-table-responsive>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-modal>
    {{-- End ShowModal --}}


    {{-- Purchase Payment payment component   --}}
    @if (empty($showPayments))
        <livewire:purchase.payment.index :purchase="$purchase" />
    @endif
    {{-- End Purchase Payment payment component   --}}

    @if (!empty($paymentModal))
        <x-modal wire:model="paymentModal">
            <x-slot name="title">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Purchase Payment') }}
                </h2>

            </x-slot>
            <x-slot name="content">
                <form wire:submit.prevent="paymentSave">

                    <x-validation-errors class="mb-4" :errors="$errors" />

                    <div class="flex flex-wrap -mx-2 mb-3">

                        <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                            <x-label for="reference" :value="__('Reference')" required />
                            <x-input type="text" wire:model="reference" id="reference" class="block w-full mt-1"
                                required />
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
    @endif


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


</div>
