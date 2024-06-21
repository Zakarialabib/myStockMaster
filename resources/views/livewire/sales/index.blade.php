<div>
    @section('title', __('Sales'))

    <x-theme.breadcrumb :title="__('Sales List')" :parent="route('sales.index')" :parentName="__('Sales List')">

        <x-dropdown align="right" width="48" class="w-auto mr-2">
            <x-slot name="trigger" class="inline-flex">
                <x-button secondary type="button" class="text-white flex items-center">
                    <i class="fas fa-angle-double-down w-4 h-4"></i>
                </x-button>
            </x-slot>
            <x-slot name="content">
                <x-dropdown-link wire:click="dispatch('exportAll')" wire:loading.attr="disabled">
                    {{ __('PDF') }}
                </x-dropdown-link>
                <x-dropdown-link wire:click="dispatch('downloadAll')" wire:loading.attr="disabled">
                    {{ __('Excel') }}
                </x-dropdown-link>
            </x-slot>
        </x-dropdown>
        @can('sale_create')
            <x-button primary href="{{ route('sale.create') }}">{{ __('Create Invoice') }}</x-button>
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
                @can('sale_delete')
                    <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                        <i class="fas fa-trash"></i>
                    </x-button>
                @endcan
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
        <div class="grid gap-4 grid-cols-2 items-center justify-center">
            <div class="w-full mb-2 flex flex-wrap ">
                <div class="w-full md:w-1/2 px-2">
                    <label>{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                    <x-input wire:model.live="startDate" type="date" name="startDate" value="$startDate" />
                    @error('startDate')
                        <span class="text-danger mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-2">
                    <label>{{ __('End Date') }} <span class="text-red-500">*</span></label>
                    <x-input wire:model.live="endDate" type="date" name="endDate" value="$endDate" />
                    @error('endDate')
                        <span class="text-danger mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="gap-2 inline-flex items-center mx-0 px-2 mb-2">
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
            <x-table.th sortable wire:click="sortBy('customer_id')" :direction="$sorts['customer_id'] ?? null">
                {{ __('Customer') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('payment_id')" :direction="$sorts['payment_id'] ?? null">
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
            @forelse ($sales as $sale)
                <x-table.tr wire:loading.class.delay="opacity-50">
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $sale->id }}" wire:model.live="selected" />
                    </x-table.td>
                    <x-table.td>
                        {{ $sale->reference }}
                    </x-table.td>
                    <x-table.td>
                        {{ format_date($sale->date) }}
                    </x-table.td>
                    <x-table.td>
                        @if ($sale?->customer)
                            <a class="text-blue-400 hover:text-blue-600 focus:text-blue-600"
                                href="{{ route('customer.details', $sale->customer->uuid) }}">
                                {{ $sale?->customer?->name }}
                            </a>
                        @else
                            {{ $sale?->customer?->name }}
                        @endif

                    </x-table.td>
                    <x-table.td>
                        {{ $sale->payment_id }}
                        @php
                            $type = $sale->payment_id;
                        @endphp
                        <x-badge :type="$type">{{ $sale->payment_id }}</x-badge>
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($sale->due_amount) }}
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($sale->total_amount) }}
                    </x-table.td>

                    <x-table.td>
                        @php
                            $badgeType = $sale->status->getBadgeType();
                        @endphp

                        <x-badge :type="$badgeType">{{ $sale->status->getName() }}</x-badge>
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
                                    <x-dropdown-link wire:click="$dispatch('showModal', {{ $sale->id }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-eye"></i>
                                        {{ __('View') }}
                                    </x-dropdown-link>
                                    @if ($sale->due_amount > 0)
                                        <x-dropdown-link wire:click="sendWhatsapp({{ $sale->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-paper-plane"></i>
                                            {{ __('Send to Whatsapp') }}
                                        </x-dropdown-link>
                                    @endif
                                    @can('sale_update')
                                        <x-dropdown-link href="{{ route('sales.edit', $sale->id) }}"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-edit"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('sale_delete')
                                        <x-dropdown-link wire:click="$dispatch('deleteModal', {{ $sale->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-trash"></i>
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <x-dropdown-link target="_blank" href="{{ route('sales.pos.pdf', $sale->id) }}"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-print"></i>
                                        {{ __('Print Pos') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link target="_blank" href="{{ route('sales.pdf', $sale->id) }}"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-print"></i>
                                        {{ __('Print') }}
                                    </x-dropdown-link>

                                    @can('access_sale_payments')
                                        <x-dropdown-link wire:click="$dispatch('showPayments', {{ $sale->id }})"
                                            primary wire:loading.attr="disabled">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ __('Payments') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('access_sale_payments')
                                        @if ($sale->due_amount > 0)
                                            <x-dropdown-link wire:click="$dispatch('paymentModal', {{ $sale->id }})"
                                                primary wire:loading.attr="disabled">
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
                    <x-table.td colspan="9">
                        <div class="flex justify-center items-center">
                            <i class="fas fa-box-open text-4xl text-gray-400"></i>
                            {{ __('No results found') }}
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="px-6 py-3">
        {{ $sales->links() }}
    </div>

    @livewire('sales.show', ['sale' => $sale], key('show' . $sale?->id))

    @livewire('sales.payment-form', ['sale' => $sale], key('payment-form' . $sale?->id))

    <x-modal wire:model.live="importModal">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                {{ __('Import Excel') }}
                <x-button primary wire:click="downloadSample" type="button">
                    {{ __('Download Sample') }}
                </x-button>
            </div>
        </x-slot>

        <x-slot name="content">
            <form wire:submit="import">
                <div class="mb-4">

                    <div class="w-full px-3">
                        <x-label for="import" :value="__('Import')" />
                        <x-input id="import" class="block mt-1 w-full" type="file" name="import"
                            wire:model="import_file" />
                        <x-input-error :messages="$errors->get('import')" for="import" class="mt-2" />
                    </div>

                    <div class="w-full px-3">
                        <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>

    @livewire('sales.payment.index', ['sale' => $sale])


    @pushOnce('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
            integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endPushOnce

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', function() {
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
                            window.Livewire.dispatch('delete', saleId)
                        }
                    })
                })

            })
        </script>
        <script>
            function printContent() {
                const content = document.getElementById("printable-content");
                html2canvas(content).then(canvas => {
                    const printWindow = window.open('', '',
                        'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
                    const printDocument = printWindow.document;
                    printDocument.body.appendChild(canvas);
                    canvas.onload = function() {
                        printWindow.print();
                        printWindow.close();
                    };
                });
            }
        </script>
    @endpush

</div>
