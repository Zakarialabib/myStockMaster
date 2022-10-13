<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            <button
                class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                type="button" wire:click="$toggle('showDeleteModal')" wire:loading.attr="disabled"
                {{ $this->selectedCount ? '' : 'disabled' }}>
                {{ __('Delete Selected') }}
            </button>

            <button
                class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                type="button" wire:click="confirm('import')" wire:loading.attr="disabled">
                {{ __('Import') }}
            </button>
            
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <div class="my-2 my-md-0">
                <input type="text" wire:model.debounce.300ms="search"
                    class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>
    <div wire:loading.delay class="flex justify-center">
        <x-loading />
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th class="pr-0 w-8">
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
            <x-table.th sortable multi-column wire:click="sortBy('total')" :direction="$sorts['total'] ?? null">
                {{ __('Total') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
                {{ __('Status') }}
            </x-table.th>
            <x-table.th />
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
                        @if ($sale->payment_status == 'Partial')
                            <x-badge warning>
                                {{ $sale->payment_status }}
                            </x-badge>
                        @elseif ($sale->payment_status == 'Paid')
                            <x-badge success>
                                {{ $sale->payment_status }}
                            </x-badge>
                        @else
                            <x-badge danger>
                                {{ $sale->payment_status }}
                            </x-badge>
                        @endif
                    </x-table.td>

                    <x-table.td>
                        {{ $sale->total_amount }}
                    </x-table.td>

                    <x-table.td>
                        @if ($sale->status == 'Pending')
                            <x-badge warning>
                                {{ $sale->status }}
                            </x-badge>
                        @elseif ($sale->status == 'Shipped')
                            <x-badge success>
                                {{ $sale->status }}
                            </x-badge>
                        @else
                            <x-badge danger>
                                {{ $sale->status }}
                            </x-badge>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button info {{-- href="{{ route('sales.show', $sale) }}" --}} type="button"
                                wire:click="confirm('show', {{ $sale->id }})" wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>

                            @can('edit_sales')
                                <x-button href="{{ route('sales.edit', $sale) }}" primary type="button"
                                    wire:click="confirm('edit', {{ $sale->id }})" wire:loading.attr="disabled">
                                    <i class="fas fa-edit"></i>
                                </x-button>
                            @endcan

                            @can('delete_sales')
                                <x-button href="{{ route('sales.destroy', $sale) }}" danger type="button"
                                    wire:click="confirm('delete', {{ $sale->id }})" wire:loading.attr="disabled">
                                    <i class="fas fa-trash"></i>
                                </x-button>
                            @endcan

                            <x-button target="_blank" href="{{ route('sales.pos.pdf', $sale->id) }}" warning
                                wire:loading.attr="disabled">
                                <i class="fas fa-print"></i>
                            </x-button>

                            @can('access_sale_payments')
                                <x-button href="{{ route('sale-payments.index', $sale->id) }}" success
                                    wire:loading.attr="disabled">
                                    <i class="fas fa-money-bill-wave"></i>
                                </x-button>
                            @endcan
                            @can('access_sale_payments')
                                @if ($sale->due_amount > 0)
                                    <x-button href="{{ route('sale-payments.create', $sale->id) }}" success
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </x-button>
                                @endif
                            @endcan



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
        {{ $sales->links() }}
    </div>

    <x-modal wire:model="create">
        <x-slot name="title">
            {{ __('Create Sale') }}
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
                    id="customer_id" name="customer_id" wire:model="product.customer_id" :options="$this->listsForFields['custmers']" />

                <x-input-error :messages="$errors->get('customer_id')" for="customer_id" class="mt-2" />
            </div>
        </x-slot>
    </x-modal>

    <x-modal wire:model="update">
        <x-slot name="title">
            {{ __('Update Sale') }}
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
                    id="customer_id" name="customer_id" wire:model="product.customer_id" :options="$this->listsForFields['custmers']" />
                <x-input-error :messages="$errors->get('customer_id')" for="customer_id" class="mt-2" />
            </div>
        </x-slot>
    </x-modal>

    <x-modal wire:model="show">
        <x-slot name="title">
            {{ __('Show Sale') }}
        </x-slot>

        <x-slot name="content">
            <div class"w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
                <x-label for="date" :value="__('Date')" />
                <x-input id="date" class="block mt-1 w-full" type="date" wire:model.defer="date"
                    disabled />
                <x-input-error :messages="$errors->get('date')" for="date" class="mt-2" />
            </div>

            <div class"w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
                <x-label for="customer_id" :value="__('Customer')" />
                <x-select-list
                    class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                    id="customer_id" name="customer_id" wire:model="product.customer_id" :options="$this->listsForFields['custmers']" />
                <x-input-error :messages="$errors->get('customer_id')" for="customer_id" class="mt-2" />
            </div>
        </x-slot>
    </x-modal>

</div>

@push('page_scripts')
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
