<div>
    <x-validation-errors class="mb-4" :errors="$errors" />

    <div class="table-responsive">
        <x-table>
            <x-slot name="thead">
                <x-table.th>#</x-table.th>
                <x-table.th>{{ __('Product Name') }}</x-table.th>
                <x-table.th>{{ __('Code') }}</x-table.th>
                <x-table.th>{{ __('Stock') }}</x-table.th>
                <x-table.th>{{ __('Quantity') }}</x-table.th>
                <x-table.th>{{ __('Type') }}</x-table.th>
                <x-table.th>{{ __('Action') }}</x-table.th>
            </x-slot>
            <x-table.tbody>
                @foreach ($products as $key => $product)
                    <x-table.tr wire:loading.class.delay="opacity-50">
                        <x-table.td>{{ $key + 1 }}</x-table.td>
                        <x-table.td>{{ $product['name'] ?? $product['product']['name'] }}</x-table.td>
                        <x-table.td>{{ $product['code'] ?? $product['product']['code'] }}</x-table.td>
                        <x-table.td>
                            <span class="badge badge-info">
                                {{ $product['quantity'] ?? $product['product']['quantity'] }}
                                {{ $product['unit'] ?? $product['product']['unit'] }}
                            </span>
                        </x-table.td>
                        <input type="hidden" name="product_ids[]"
                            value="{{ $product['product']['id'] ?? $product['id'] }}">
                        <x-table.td>
                            <input type="text" name="quantities[]" min="1"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                value="{{ $product['quantity'] ?? 1 }}">
                        </x-table.td>
                        <x-table.td>
                            @if (isset($product['type']))
                                @if ($product['type'] == 'add')
                                    <select name="types[]"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                                        <option value="add" selected>(+) {{__('Addition')}}</option>
                                        <option value="sub">(-) {{__('Subtraction')}}</option>
                                    </select>
                                @elseif($product['type'] == 'sub')
                                    <select name="types[]"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                                        <option value="sub" selected>(-) {{__('Subtraction')}}</option>
                                        <option value="add">(+)  {{__('Addition')}}</option>
                                    </select>
                                @endif
                            @else
                                <select name="types[]"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                                    <option value="add">(+) {{__('Addition')}}</option>
                                    <option value="sub">(-) {{__('Subtraction')}}</option>
                                </select>
                            @endif
                        </x-table.td>
                        <x-table.td>
                            <button type="button" class="btn btn-danger"
                                wire:click="removeProduct({{ $key }})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            
            </x-table.tbody>
        </x-table>
    </div>
</div>
