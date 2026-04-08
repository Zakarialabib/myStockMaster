<div>
    @section('title', __('Sales'))

    <x-page-container :title="__('Sales List')"
        :breadcrumbs="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Sales List'), 'url' => route('sales.index')]
        ]"
        :show-filters="true">

        <x-slot name="actions">
            <div class="flex justify-end space-x-2">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger" class="inline-flex">
                        <x-button primary type="button" class="text-white flex items-center">
                            <i class="fas fa-ellipsis-v"></i>
                        </x-button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link wire:click="dispatch('exportAll')" wire:loading.attr="disabled">
                            <i class="fas fa-file-pdf"></i>
                            {{ __('PDF') }}
                        </x-dropdown-link>
                        <x-dropdown-link wire:click="dispatch('downloadAll')" wire:loading.attr="disabled">
                            <i class="fas fa-file-excel"></i>
                            {{ __('Excel') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
                @can('sale_create')
                    <x-button primary href="{{ route('sale.create') }}">{{ __('Create Invoice') }}</x-button>
                @endcan
            </div>
        </x-slot>

        <x-slot name="filters">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-input.select wire:model.live="perPage" :label="__('Show')">
                    @foreach ($paginationOptions as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </x-input.select>

                <x-input.text wire:model.live.debounce.500ms="search" :placeholder="__('Search')" icon="fas fa-search" />

                <x-input.date wire:model.live="startDate" :label="__('Start Date')" />

                <x-input.date wire:model.live="endDate" :label="__('End Date')" />
            </div>

            <div class="flex flex-wrap gap-2 mt-4">
                <x-button type="button" primary wire:click="filterByType('day')">{{ __('Today') }}</x-button>
                <x-button type="button" info wire:click="filterByType('month')">{{ __('This Month') }}</x-button>
                <x-button type="button" warning wire:click="filterByType('year')">{{ __('This Year') }}</x-button>
            </div>

            @if ($selected)
                <div class="flex items-center space-x-4 mt-4">
                    @can('sale_delete')
                        <x-button danger type="button" wire:click="deleteSelected">
                            <i class="fas fa-trash mr-2"></i>
                            {{ __('Delete Selected') }}
                        </x-button>
                    @endcan
                    <x-button success type="button" wire:click="downloadSelected">
                        <i class="fas fa-file-excel mr-2"></i>
                        {{ __('EXCEL') }}
                    </x-button>
                    <x-button warning type="button" wire:click="exportSelected">
                        <i class="fas fa-file-pdf mr-2"></i>
                        {{ __('PDF') }}
                    </x-button>
                    @if ($this->selectedCount)
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span class="text-sm font-medium">{{ $this->selectedCount }} {{ __('Entries selected') }}</span>
                            </div>
                            <x-button secondary type="button" wire:click="resetSelected">
                                {{ __('Clear Selected') }}
                            </x-button>
                        </div>
                    @endif
                </div>
            @endif
        </x-slot>

        <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model.live="selectPage" />
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('reference')" :direction="$sortBy === 'reference' ? $sortDirection : null">
                {{ __('Reference') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('date')" :direction="$sortBy === 'date' ? $sortDirection : null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('customer_id')" :direction="$sortBy === 'customer_id' ? $sortDirection : null">
                {{ __('Customer') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('payment_id')" :direction="$sortBy === 'payment_id' ? $sortDirection : null">
                {{ __('Payment status') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('due_amount')" :direction="$sortBy === 'due_amount' ? $sortDirection : null">
                {{ __('Due Amount') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('total')" :direction="$sortBy === 'total' ? $sortDirection : null">
                {{ __('Total') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('status')" :direction="$sortBy === 'status' ? $sortDirection : null">
                {{ __('Status') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($sales as $sale)
                <x-table.tr wire:key="row-{{ $sale->id }}">
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
                                href="{{ route('customer.details', $sale->customer->id) }}">
                                {{ $sale?->customer?->name }}
                            </a>
                        @else
                            {{ $sale?->customer?->name }}
                        @endif

                    </x-table.td>
                    <x-table.td>
                        <x-table.status-dropdown 
                            :id="$sale->id" 
                            :value="$sale->payment_id" 
                            action="updatePaymentStatus"
                            :options="[
                                ['value' => App\Enums\PaymentStatus::PENDING->value, 'label' => App\Enums\PaymentStatus::PENDING->getName()],
                                ['value' => App\Enums\PaymentStatus::PARTIAL->value, 'label' => App\Enums\PaymentStatus::PARTIAL->getName()],
                                ['value' => App\Enums\PaymentStatus::PAID->value, 'label' => App\Enums\PaymentStatus::PAID->getName()],
                                ['value' => App\Enums\PaymentStatus::DUE->value, 'label' => App\Enums\PaymentStatus::DUE->getName()]
                            ]" 
                        />
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($sale->due_amount) }}
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($sale->total_amount) }}
                    </x-table.td>

                    <x-table.td>
                        <x-table.status-dropdown 
                            :id="$sale->id" 
                            :value="$sale->status->getName()" 
                            action="updateStatus"
                            :options="[
                                ['value' => App\Enums\SaleStatus::PENDING->value, 'label' => App\Enums\SaleStatus::PENDING->getName()],
                                ['value' => App\Enums\SaleStatus::ORDERED->value, 'label' => App\Enums\SaleStatus::ORDERED->getName()],
                                ['value' => App\Enums\SaleStatus::COMPLETED->value, 'label' => App\Enums\SaleStatus::COMPLETED->getName()],
                                ['value' => App\Enums\SaleStatus::CANCELED->value, 'label' => App\Enums\SaleStatus::CANCELED->getName()],
                                ['value' => App\Enums\SaleStatus::SHIPPED->value, 'label' => App\Enums\SaleStatus::SHIPPED->getName()]
                            ]" 
                        />
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
                                    <x-dropdown-link wire:click="$dispatchTo('sales.show', 'showModal', { id: {{ $sale->id }} })"
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
                                        <x-dropdown-link href="{{ route('sale.edit', $sale->id) }}"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-edit"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('sale_delete')
                                        <x-dropdown-link wire:click="deleteModal({{ $sale->id }})"
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
                                        <x-dropdown-link wire:click="$dispatchTo('sales.payment.index', 'showPayments', { id: {{ $sale->id }} })"
                                            primary wire:loading.attr="disabled">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ __('Payments') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('access_sale_payments')
                                        @if ($sale->due_amount > 0)
                                            <x-dropdown-link wire:click="$dispatchTo('sales.payment-form', 'paymentModal', { id: {{ $sale->id }} })"
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

    </x-page-container>

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
