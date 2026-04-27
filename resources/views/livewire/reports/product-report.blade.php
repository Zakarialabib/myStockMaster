<div>
    <x-page-container title="{{ __('Product Report') }}" :breadcrumbs="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Reports'), 'url' => '#'],
        ['label' => __('Product Report'), 'url' => '#']
    ]" :show-filters="false">
        
        <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md dark:bg-gray-800 dark:border-blue-500">
            <div class="flex items-start">
                <div class="shrink-0"><i class="fas fa-info-circle text-blue-400"></i></div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        <strong>{{ __('How to get the most from this report:') }}</strong> 
                        {{ __('Analyze inventory valuation (total capital tied up in stock) and turnover ratios. A low turnover ratio may indicate dead stock, while a high ratio indicates fast-moving goods.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative">
            <x-table>
                <x-slot name="thead">
                    <x-table.th>{{ __('Product Name') }}</x-table.th>
                    <x-table.th>{{ __('Current Stock') }}</x-table.th>
                    <x-table.th>{{ __('Total Sold') }}</x-table.th>
                    <x-table.th>{{ __('Inventory Valuation') }}</x-table.th>
                    <x-table.th>{{ __('Turnover Ratio') }}</x-table.th>
                </x-slot>
                <x-table.tbody>
                    @foreach($this->products as $product)
                        @php
                            $averageStock = ($product->current_stock + $product->total_sold) / 2;
                            $turnoverRatio = $averageStock > 0 ? ($product->total_sold / $averageStock) : 0;
                        @endphp
                        <x-table.tr>
                            <x-table.td>{{ $product->name }} ({{ $product->code }})</x-table.td>
                            <x-table.td>{{ $product->current_stock }}</x-table.td>
                            <x-table.td>{{ $product->total_sold }}</x-table.td>
                            <x-table.td>{{ format_currency($product->inventory_valuation) }}</x-table.td>
                            <x-table.td>{{ number_format($turnoverRatio, 2) }}</x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table.tbody>
            </x-table>
            <div class="p-4">{{ $this->products->links() }}</div>
        </div>
    </x-page-container>
</div>
