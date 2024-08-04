<div>
    <div class="flex flex-row">
        <div class="w-full">
            <div class="card border-0 shadow-sm">
                <div class="p-4">
                    <h1 class="text-xl font-semibold mb-4">{{ __('Stock Alert Report') }}</h1>
                    <div class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <input type="text" wire:model="filterName" placeholder="Product Name"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            <div>
                                <input type="text" wire:model="filterCode" placeholder="Product Code"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            <div>
                                <input type="number" wire:model="filterQuantityMin" placeholder="Min Quantity"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            <div>
                                <input type="number" wire:model="filterQuantityMax" placeholder="Max Quantity"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button wire:click="$refresh"
                                class="bg-indigo-800 hover:bg-indigo-700 text-white text-xs py-2 px-4 rounded">
                                {{ __('Apply Filters') }}
                            </button>
                        </div>
                    </div>

                    <x-table>
                        <x-slot name="thead">
                            <x-table.th>{{ __('Product Code') }}</x-table.th>
                            <x-table.th>{{ __('Product Name') }}</x-table.th>
                            <x-table.th>{{ __('Quantity') }}</x-table.th>
                            <x-table.th>{{ __('Stock Alert Level') }}</x-table.th>
                            <x-table.th>{{ __('Set New Threshold') }}</x-table.th>
                        </x-slot>
                        <x-table.tbody>
                            @foreach ($products as $product)
                                <x-table.tr>
                                    <x-table.td>{{ $product->code }}</x-table.td>
                                    <x-table.td>{{ $product->name }}</x-table.td>
                                    <x-table.td>{{ $product->quantity }}</x-table.td>
                                    <x-table.td>{{ $product->stock_alert }}</x-table.td>
                                    <x-table.td>
                                        <div class="flex items-center">
                                            <input type="number" wire:model="thresholds.{{ $product->id }}"
                                                placeholder="New Threshold"
                                                class="form-input rounded-md shadow-sm mt-1 block w-full">
                                            <button
                                                wire:click="setThreshold({{ $product->id }}, thresholds.{{ $product->id }})"
                                                class="ml-2 bg-indigo-800 hover:bg-indigo-700 text-white text-xs py-2 px-4 rounded">
                                                {{ __('Set') }}
                                            </button>
                                        </div>
                                    </x-table.td>
                                </x-table.tr>
                            @endforeach
                        </x-table.tbody>
                    </x-table>
                    <div @class(['mt-3' => $products->hasPages()])>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
