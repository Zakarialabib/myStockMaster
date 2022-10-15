<div>
    <div class="flex flex-row">
        <div class="w-full">
            <form wire:submit.prevent="generateReport">
                <div class="flex flex-wrap -mx-1">
                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <div class="mb-4">
                            <label>{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                            <input wire:model.defer="start_date" type="date"
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
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
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                name="end_date">
                            @error('end_date')
                                <span class="text-danger mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <div class="mb-4">
                            <label>{{ __('Supplier') }}</label>
                            <select wire:model.defer="supplier_id"
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                name="supplier_id">
                                <option value="">{{ __('Select Supplier') }}</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-1">
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <div class="mb-4">
                            <label>{{ __('Status') }}</label>
                            <select wire:model.defer="purchase_status"
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                name="purchase_status">
                                <option value="">{{ __('Select Status') }}</option>
                                <option value="Pending">{{ __('Pending') }}</option>
                                <option value="Ordered">{{ __('Ordered') }}</option>
                                <option value="Completed">{{ __('Completed') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <div class="mb-4">
                            <label>{{ __('Payment Status') }}</label>
                            <select wire:model.defer="payment_status"
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                name="payment_status">
                                <option value="">{{ __('Select Payment Status') }}</option>
                                <option value="Paid">{{ __('Paid') }}</option>
                                <option value="Unpaid">{{ __('Unpaid') }}</option>
                                <option value="Partial">{{ __('Partial') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-4 md:mb-0">
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

    <div class="flex flex-row pt-3">
        <div class="w-full">
            <x-table>
                <x-slot name="thead">
                    <x-table.th>{{ __('Date') }}</x-table.th>
                    <x-table.th>{{ __('Reference') }}</x-table.th>
                    <x-table.th>{{ __('Supplier') }}</x-table.th>
                    <x-table.th>{{ __('Status') }}</x-table.th>
                    <x-table.th>{{ __('Total') }}</x-table.th>
                    <x-table.th>{{ __('Paid') }}</x-table.th>
                    <x-table.th>{{ __('Due') }}</x-table.th>
                    <x-table.th>{{ __('Payment Status') }}</x-table.th>
                </x-slot>
                <x-table.tbody>
                    @forelse($purchases as $purchase)
                        <x-table.tr>
                            <x-table.td>{{ \Carbon\Carbon::parse($purchase->date)->format('d M, Y') }}
                            </x-table.td>
                            <x-table.td>{{ $purchase->reference }}</x-table.td>
                            <x-table.td>{{ $purchase->name }}</x-table.td>
                            <x-table.td>
                                @if ($purchase->status == 'Pending')
                                    <span class="badge badge-info">
                                        {{ $purchase->status }}
                                    </span>
                                @elseif ($purchase->status == 'Ordered')
                                    <span class="badge badge-primary">
                                        {{ $purchase->status }}
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        {{ $purchase->status }}
                                    </span>
                                @endif
                            </x-table.td>
                            <x-table.td>{{ format_currency($purchase->total_amount) }}</x-table.td>
                            <x-table.td>{{ format_currency($purchase->paid_amount) }}</x-table.td>
                            <x-table.td>{{ format_currency($purchase->due_amount) }}</x-table.td>
                            <x-table.td>
                                @if ($purchase->payment_status == 'Partial')
                                    <span class="badge badge-warning">
                                        {{ $purchase->payment_status }}
                                    </span>
                                @elseif ($purchase->payment_status == 'Paid')
                                    <span class="badge badge-success">
                                        {{ $purchase->payment_status }}
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        {{ $purchase->payment_status }}
                                    </span>
                                @endif

                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="8">
                                <span class="text-red-500">No Purchases Data Available!</span>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>

            <div @class(['mt-3' => $purchases->hasPages()])>
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
</div>
