<div>
    <x-page-container title="{{ __('Payments Report') }}" :breadcrumbs="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Reports'), 'url' => '#'],
        ['label' => __('Payments Report'), 'url' => '#']
    ]" :show-filters="true">

        <x-slot name="filters">
            <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md dark:bg-gray-800 dark:border-blue-500">
                <div class="flex items-start">
                    <div class="shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <strong>{{ __('How to get the most from this report:') }}</strong> 
                            {{ __('Use the date filters to narrow down your payments. Filter by specific payment types or methods to easily track transactions.') }}
                        </p>
                    </div>
                </div>
            </div>

            <form wire:submit="generateReport">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div>
                        <x-label for="start_date" :value="__('Start Date')" />
                        <x-input wire:model="start_date" type="date" id="start_date" />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="end_date" :value="__('End Date')" />
                        <x-input wire:model="end_date" type="date" id="end_date" />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="payments" :value="__('Payments')" />
                        <x-select wire:model.live="payments" id="payments" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                            <option value="">{{ __('Select Payments') }}</option>
                            <option value="sale">{{ __('Sales') }}</option>
                            <option value="sale_return">{{ __('Sale Returns') }}</option>
                            <option value="purchase">{{ __('Purchase') }}</option>
                            <option value="purchase_return">{{ __('Purchase Returns') }}</option>
                        </x-select>
                        <x-input-error :messages="$errors->get('payments')" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="payment_method" :value="__('Payment Method')" />
                        <x-select wire:model="payment_method" id="payment_method" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                            <option value="">{{ __('Select Payment Method') }}</option>
                            <option value="Cash">{{ __('Cash') }}</option>
                            <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                            <option value="Cheque">{{ __('Cheque') }}</option>
                            <option value="Other">{{ __('Other') }}</option>
                        </x-select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-button type="submit" primary>{{ __('Filter Report') }}</x-button>
                </div>
            </form>
        </x-slot>

        @if($this->cashFlowSummary->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                @foreach($this->cashFlowSummary as $summary)
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <p class="text-sm text-gray-500 font-medium">{{ $summary->payment_method }}</p>
                        <p class="text-xl font-bold text-gray-800">{{ format_currency($summary->total_amount) }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        @if($this->information->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative">
                <x-table>
                    <x-slot name="thead">
                        <x-table.th>{{ __('Date') }}</x-table.th>
                        <x-table.th>{{ __('Reference') }}</x-table.th>
                        <x-table.th>{{ ucwords(str_replace('_', ' ', $payments)) }}</x-table.th>
                        <x-table.th>{{ __('User') }}</x-table.th>
                        <x-table.th>{{ __('Register') }}</x-table.th>
                        <x-table.th>{{ __('Total') }}</x-table.th>
                        <x-table.th>{{ __('Payment Method') }}</x-table.th>
                    </x-slot>
                    <x-table.tbody>
                        @forelse($this->information as $data)
                            <x-table.tr>
                                <x-table.td>{{ format_date($data->date) }}</x-table.td>
                                <x-table.td>{{ $data->reference }}</x-table.td>
                                <x-table.td>
                                    @if($payments == 'sale')
                                        {{ $data->sale->reference }}
                                    @elseif($payments == 'purchase')
                                        {{ $data->purchase->reference }}
                                    @elseif($payments == 'sale_return')
                                        {{ $data->saleReturn->reference }}
                                    @elseif($payments == 'purchase_return')
                                        {{ $data->purchaseReturn->reference }}
                                    @endif
                                </x-table.td>
                                <x-table.td>{{ $data->user?->name ?? '--' }}</x-table.td>
                                <x-table.td>{{ $data->cashRegister?->warehouse?->name ?? '--' }}</x-table.td>
                                <x-table.td>{{ format_currency($data->amount) }}</x-table.td>
                                <x-table.td>{{ $data->payment_method }}</x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.tr>
                                <x-table.td colspan="7">
                                    <span class="text-red-500">{{ __('No Data Available') }}!</span>
                                </x-table.td>
                            </x-table.tr>
                        @endforelse
                    </x-table.tbody>
                </x-table>
                <div class="p-4">
                    {{ $this->information->links() }}
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative">
                <div class="p-4">
                    <div class="alert alert-warning mb-0">
                        {{ __('No Data Available!') }}
                    </div>
                </div>
            </div>
        @endif

    </x-page-container>
</div>
