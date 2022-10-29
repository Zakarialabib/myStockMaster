<div>
    <div class="flex flex-row">
        <div class="w-full">
            <div class="card border-0 shadow-sm">
                <div class="p-4">
                    <form wire:submit.prevent="generateReport">
                        <div class="flex flex-wrap -mx-1">
                            <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                <div class="mb-4">
                                    <label>{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                                    <input wire:model.defer="start_date" type="date"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                        name="start_date">
                                    @error('start_date')
                                        <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                <div class="mb-4">
                                    <label>{{ __('End Date') }} <span class="text-red-500">*</span></label>
                                    <input wire:model.defer="end_date" type="date"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                        name="end_date">
                                    @error('end_date')
                                        <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                <div class="mb-4">
                                    <label>{{ __('Customer') }}</label>
                                    <select wire:model.defer="customer_id"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                        name="customer_id">
                                        <option value="">{{ __('Select Customer') }}</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-1">
                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                <div class="mb-4">
                                    <label>{{ __('Status') }}</label>
                                    <select wire:model.defer="sale_return_status"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                        name="sale_return_status">
                                        <option value="">{{ __('Select Status') }}</option>
                                        <option value="Pending">{{ __('Pending') }}</option>
                                        <option value="Shipped">{{ __('Shipped') }}</option>
                                        <option value="Completed">{{ __('Completed') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                <div class="mb-4">
                                    <label>{{ __('Payment Status') }}</label>
                                    <select wire:model.defer="payment_status"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                        name="payment_status">
                                        <option value="">{{ __('Select Payment Status') }}</option>
                                        <option value="Paid">{{ __('Paid') }}</option>
                                        <option value="Unpaid">{{ __('Unpaid') }}</option>
                                        <option value="Partial">{{ __('Partial') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <button type="submit"
                                class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">
                                <span wire:target="generateReport" wire:loading class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true"></span>
                                <i wire:target="generateReport" wire:loading.remove class="bi bi-shuffle"></i>
                                {{ __('Filter Report') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="p-4">
                    <x-table>
                        <x-slot name="thead">
                            <x-table.th>{{ __('Date') }}</x-table.th>
                            <x-table.th>{{ __('Reference') }}</x-table.th>
                            <x-table.th>{{ __('Customer') }}</x-table.th>
                            <x-table.th>{{ __('Status') }}</x-table.th>
                            <x-table.th>{{ __('Total') }}</x-table.th>
                            <x-table.th>{{ __('Paid') }}</x-table.th>
                            <x-table.th>{{ __('Due') }}</x-table.th>
                            <x-table.th>{{ __('Payment Status') }}</x-table.th>
                        </x-slot>
                        <x-table.tbody>
                            @forelse($sale_returns as $sale_return)
                                <x-table.tr>
                                    <x-table.td>{{ \Carbon\Carbon::parse($sale_return->date)->format('d M, Y') }}
                                    </x-table.td>
                                    <x-table.td>{{ $sale_return->reference }}</x-table.td>
                                    <x-table.td>{{ $sale_return->customer->name }}</x-table.td>
                                    <x-table.td>
                                        @if ($sale_return->status == 'Pending')
                                            <x-badge info>
                                                {{ $sale_return->status }}
                                            </x-badge>
                                        @elseif ($sale_return->status == 'Shipped')
                                            <x-badge primary>
                                                {{ $sale_return->status }}
                                            </x-badge>
                                        @else
                                            <x-badge success>
                                                {{ $sale_return->status }}
                                            </x-badge>
                                        @endif
                                    </x-table.td>
                                    <x-table.td>{{ format_currency($sale_return->total_amount) }}</x-table.td>
                                    <x-table.td>{{ format_currency($sale_return->paid_amount) }}</x-table.td>
                                    <x-table.td>{{ format_currency($sale_return->due_amount) }}</x-table.td>
                                    <x-table.td>
                                        @if ($sale_return->payment_status == 'Partial')
                                            <x-badge info>
                                                {{ $sale_return->payment_status }}
                                            </x-badge>
                                        @elseif ($sale_return->payment_status == 'Paid')
                                            <x-badge primary>
                                                {{ $sale_return->payment_status }}
                                            </x-badge>
                                        @else
                                            <x-badge success>
                                                {{ $sale_return->payment_status }}
                                            </x-badge>
                                        @endif
                                    </x-table.td>
                                </x-table.tr>
                            @empty
                                <x-table.tr>
                                    <x-table.td colspan="8">
                                        <span class="text-red-500">{{ __('No Sale Return Data Available!') }}</span>
                                    </x-table.td>
                                </x-table.tr>
                            @endforelse
                        </x-table.tbody>
                    </x-table>
                    <div @class(['mt-3' => $sale_returns->hasPages()])>
                        {{ $sale_returns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
