<div>
    @section('title', __('Create adjustment'))
    <x-theme.breadcrumb :title="__('Create adjustment')" :parent="route('adjustments.index')" :parentName="__('Adjustment')" :children="route('adjustments.create')" :childrenName="__('Create adjustment')">

    </x-theme.breadcrumb>
    <div class="flex flex-wrap">
        <div class="lg:w-1/2 sm:w-full h-full">
            <livewire:utils.search-product />
        </div>
        <div class="lg:w-1/2 sm:w-full h-full">
            <x-validation-errors :errors="$errors" />

            <form wire:submit="store">
                <div class="flex flex-wrap">
                    <div class="xl:w-1/2 lg:w-1/2 sm:w-full px-3">
                        <x-label for="reference" :value="__('Reference')" required />
                        <x-input type="text" wire:model="reference" name="reference" required />
                    </div>
                    <div class="xl:w-1/2 lg:w-1/2 sm:w-full px-3">
                        <x-label for="date" :value="__('Date')" required />
                        <x-input type="date" wire:model="date" name="date" required />
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                        <x-label for="warehouse" :value="__('Warehouse')" />
                        <x-select-list
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required id="warehouse_id" name="warehouse_id" wire:model.live="warehouse_id"
                            :options="$this->warehouses" />
                        <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                    </div>
                </div>

                <div class="table-responsive">
                    <x-table>
                        <x-slot name="thead">
                            <x-table.th>#</x-table.th>
                            <x-table.th>{{ __('Product Name') }}</x-table.th>
                            <x-table.th>{{ __('Stock') }}</x-table.th>
                            <x-table.th>{{ __('Quantity') }}</x-table.th>
                            <x-table.th>{{ __('Type') }}</x-table.th>
                            <x-table.th>{{ __('Action') }}</x-table.th>
                        </x-slot>
                        <x-table.tbody>
                            @foreach ($products as $key => $product)
                                {{-- @dd($product) --}}
                                <x-table.tr wire:loading.class.delay="opacity-50">
                                    <x-table.td>{{ $key + 1 }}</x-table.td>
                                    <x-table.td>
                                        {{ $product['name'] ?? $product['options']['name'] }}
                                        <small>{{ $product['code'] ?? $product['options']['code'] }}</small>
                                    </x-table.td>
                                    <x-table.td>
                                        <span class="badge badge-info">
                                            {{ $product['qty'] ?? $product['options']['qty'] }}
                                            {{ $product['unit'] ?? $product['options']['unit'] }}
                                        </span>
                                    </x-table.td>
                                    <x-table.td>
                                        <input type="text" name="quantities[]" min="1"
                                            wire:model="products.{{ $key }}.quantities"
                                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                            value="{{ $product['qty'] ?? $product['options']['qty'] }}">
                                    </x-table.td>
                                    <x-table.td>
                                        <select name="types[]" wire:model="products.{{ $key }}.types"
                                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                                            <option value="add"
                                                {{ isset($product['type']) && $product['options']['type'] == 'add' ? 'selected' : '' }}>
                                                (+)
                                                {{ __('Addition') }}</option>
                                            <option value="sub"
                                                {{ isset($product['type']) && $product['options']['type'] == 'sub' ? 'selected' : '' }}>
                                                (-) {{ __('Subtraction') }}</option>
                                        </select>
                                    </x-table.td>
                                    <x-table.td>
                                        <x-button danger type="button"
                                            wire:click="removeProduct({{ $key }})">
                                            <i class="fa fa-trash"></i>
                                        </x-button>
                                    </x-table.td>
                                </x-table.tr>
                            @endforeach

                        </x-table.tbody>
                    </x-table>
                </div>

                <div class="mb-4">
                    <x-label for="note" :value="__('Note (If Needed)')" />
                    <textarea name="note" id="note" rows="5" wire:model="note"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                </div>
                <div class="mt-3">
                    <x-button type="submit" primary>
                        {{ __('Create Adjustment') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
