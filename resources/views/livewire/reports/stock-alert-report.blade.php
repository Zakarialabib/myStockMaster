<div>
    <x-page-container :title="__('Stock Alert Report')" :showFilters="true">
        <x-slot name="filters">
            <div class="flex flex-wrap mb-3">
                <div class="w-full md:w-1/3 px-2 mb-2">
                    <div class="mb-4">
                        <label>{{ __('Warehouse') }}</label>
                        <x-select wire:model.live="warehouse_id" id="warehouse_id" name="warehouse_id"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                            <option value="">{{ __('All Warehouses') }}</option>
                            @foreach ($this->warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                </div>
            </div>
        </x-slot>

        <div class="flex flex-row">
            <div class="w-full">
                <div class="card border-0 shadow-sm mt-4">
                    <div class="p-4">
                        <x-table>
                            <x-slot name="thead">
                                <x-table.th>{{ __('Product Code') }}</x-table.th>
                                <x-table.th>{{ __('Product Name') }}</x-table.th>
                                <x-table.th>{{ __('Warehouse') }}</x-table.th>
                                <x-table.th>{{ __('Quantity') }}</x-table.th>
                                <x-table.th>{{ __('Stock Alert Level') }}</x-table.th>
                                <x-table.th>{{ __('Set New Threshold') }}</x-table.th>
                            </x-slot>
                            <x-table.tbody>
                                @forelse ($this->stockAlert as $item)
                                    <x-table.tr>
                                        <x-table.td>{{ $item->product?->code }}</x-table.td>
                                        <x-table.td>{{ $item->product?->name }}</x-table.td>
                                        <x-table.td>{{ $item->warehouse?->name }}</x-table.td>
                                        <x-table.td>
                                            <span class="text-red-500 font-bold">{{ $item->qty }}</span>
                                        </x-table.td>
                                        <x-table.td>{{ $item->stock_alert }}</x-table.td>
                                        <x-table.td>
                                            <input type="number"
                                                wire:change="setThreshold({{ $item->id }}, $event.target.value)"
                                                value="{{ $item->stock_alert }}" placeholder="New Threshold"
                                                class="form-input rounded-md shadow-sm mt-1 block w-full sm:w-32">
                                        </x-table.td>
                                    </x-table.tr>
                                @empty
                                    <x-table.tr>
                                        <x-table.td colspan="6">
                                            <span class="text-red-500">{{ __('No products below stock alert level.') }}</span>
                                        </x-table.td>
                                    </x-table.tr>
                                @endforelse
                            </x-table.tbody>
                        </x-table>
                        <div @class(['mt-3' => $this->stockAlert->hasPages()])>
                            {{ $this->stockAlert->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-page-container>
</div>
