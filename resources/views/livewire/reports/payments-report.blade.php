<div>
    <div class="flex flex-row">
        <div class="w-full">
            <div class="card border-0 shadow-sm">
                <div class="p-4">
                    <form wire:submit.prevent="generateReport">
                        <div class="flex flex-wrap -mx-2 mb-3">
                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <div class="mb-4">
                                    <label>{{__('Start Date')}} <span class="text-red-500">*</span></label>
                                    <x-input wire:model.defer="start_date" type="date" name="start_date" />
                                    @error('start_date')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <div class="mb-4">
                                    <label>{{__('End Date')}} <span class="text-red-500">*</span></label>
                                    <x-input wire:model.defer="end_date" type="date" name="end_date" />
                                    @error('end_date')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-2 mb-3">
                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <div class="mb-4">
                                    <label>{{__('Payments')}}</label>
                                    <select wire:model="payments" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" name="payments">
                                        <option value="">{{__('Select Payments')}}</option>
                                        <option value="sale">{{__('Sales')}}</option>
                                        <option value="sale_return">{{__('Sale Returns')}}</option>
                                        <option value="purchase">{{__('Purchase')}}</option>
                                        <option value="purchase_return">{{__('Purchase Returns')}}</option>
                                    </select>
                                    @error('payments')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                <div class="mb-4">
                                    <label>{{__('Payment Method')}}</label>
                                    <select wire:model.defer="payment_method" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" name="payment_method">
                                        <option value="">{{__('Select Payment Method')}}</option>
                                        <option value="Cash">{{__('Cash')}}</option>
                                        <option value="Bank Transfer">{{__('Bank Transfer')}}</option>
                                        <option value="Cheque">{{__('Cheque')}}</option>
                                        <option value="Other">{{__('Other')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">
                                <span wire:target="generateReport" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <i wire:target="generateReport" wire:loading.remove class="bi bi-shuffle"></i>
                                {{__('Filter Report')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($information->isNotEmpty())
        <div class="flex flex-row">
            <div class="w-full">
                <div class="card border-0 shadow-sm">
                    <div class="p-4">
                        <x-table>
                            <x-slot name="thead">
                                <x-table.th>{{__('Date')}}</x-table.th>
                                <x-table.th>{{__('Reference')}}</x-table.th>
                                <x-table.th>{{ ucwords(str_replace('_', ' ', $payments)) }}</x-table.th>
                                <x-table.th>{{__('Total')}}</x-table.th>
                                <x-table.th>{{__('Payment Method')}}</x-table.th>
                            </x-slot>
                            <x-table.tbody>
                            @forelse($information as $data)
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
                                    <x-table.td>{{ format_currency($data->amount) }}</x-table.td>
                                    <x-table.td>{{ $data->payment_method }}</x-table.td>
                                </x-table.tr>
                            @empty
                                <x-table.tr>
                                    <x-table.td colspan="8">
                                        <span class="text-red-500">{{__('No Data Available')}}!</span>
                                    </x-table.td>
                                </x-table.tr>
                            @endforelse
                            </x-table.tbody>
                        </x-table.table>
                        <div @class(['mt-3' => $information->hasPages()])>
                            {{ $information->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="flex flex-row">
            <div class="w-full">
                <div class="card border-0 shadow-sm">
                    <div class="p-4">
                        <div class="alert alert-warning mb-0">
                            {{__('No Data Available!')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
