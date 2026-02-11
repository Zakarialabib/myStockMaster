<div>
    <div class="print:hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="text-sm text-gray-500 mb-1">
                        {{ __('Finance') }}
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ __('Break-Even Analysis') }}
                    </h2>
                </div>
                <div class="flex gap-2 print:hidden">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors" wire:click="exportAnalysis">
                        <i class="ti ti-download mr-2"></i>
                        {{ __('Export Analysis') }}
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 hover:bg-blue-50 text-sm font-medium rounded-lg transition-colors" wire:click="resetScenario">
                        <i class="ti ti-refresh mr-2"></i>
                        {{ __('Reset Scenario') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date From') }}</label>
                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="dateFrom">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date To') }}</label>
                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="dateTo">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Analysis Type') }}</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="analysisType">
                                    <option value="overall">{{ __('Overall Business') }}</option>
                                    <option value="product">{{ __('By Product') }}</option>
                                    <option value="category">{{ __('By Category') }}</option>
                                    <option value="scenario">{{ __('Scenario Planning') }}</option>
                                </select>
                            </div>
                            @if($analysisType === 'product')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Select Product') }}</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="selectedProduct">
                                        <option value="">{{ __('Select Product') }}</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($loading)
                <div class="flex justify-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600" role="status">
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </div>
                </div>
            @else
                <!-- Break-Even Overview -->
                <div class="mb-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="ti ti-target mr-2"></i>
                                {{ __('Break-Even Overview') }}
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="shrink-0">
                                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                                <i class="ti ti-calculator text-white"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ __('Break-Even Point') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ number_format($breakEvenData['break_even_units'] ?? 0) }} {{ __('units') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="shrink-0">
                                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                                <i class="ti ti-currency-dollar text-white"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ __('Break-Even Revenue') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ format_currency($breakEvenData['break_even_revenue'] ?? 0) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="shrink-0">
                                            <div class="w-10 h-10 bg-cyan-600 rounded-lg flex items-center justify-center">
                                                <i class="ti ti-percentage text-white"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ __('Margin of Safety') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ number_format($breakEvenData['margin_of_safety'] ?? 0, 2) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="shrink-0">
                                            <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center">
                                                <i class="ti ti-clock text-white"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ __('Days to Break-Even') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ number_format($breakEvenData['days_to_break_even'] ?? 0) }} {{ __('days') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Break-Even Chart -->
                @if(!empty($chartData))
                    <div class="mb-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="ti ti-chart-line mr-2"></i>
                                    {{ __('Break-Even Analysis Chart') }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <div id="break-even-chart" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Scenario Planning -->
                @if($analysisType === 'scenario')
                    <div class="mb-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="ti ti-bulb mr-2"></i>
                                    {{ __('Scenario Planning') }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Fixed Costs') }}</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="scenarioFixedCosts" step="0.01" placeholder="{{ __('Enter fixed costs') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Variable Cost per Unit') }}</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="scenarioVariableCost" step="0.01" placeholder="{{ __('Enter variable cost') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Selling Price per Unit') }}</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="scenarioSellingPrice" step="0.01" placeholder="{{ __('Enter selling price') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Target Units') }}</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="scenarioTargetUnits" placeholder="{{ __('Enter target units') }}">
                                    </div>
                                </div>
                                
                                <div class="flex gap-2 mb-4">
                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors" wire:click="runScenarioAnalysis">
                                        <i class="ti ti-play mr-1"></i>
                                        {{ __('Run Analysis') }}
                                    </button>
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors" wire:click="resetScenario">
                                        <i class="ti ti-refresh mr-1"></i>
                                        {{ __('Reset') }}
                                    </button>
                                </div>

                                @if(!empty($scenarioResults))
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Scenario Results') }}</h4>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="py-2 text-sm text-gray-900">{{ __('Break-Even Units') }}</td>
                                                            <td class="py-2 text-sm text-gray-900 text-right">{{ number_format($scenarioResults['break_even_units'] ?? 0) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="py-2 text-sm text-gray-900">{{ __('Break-Even Revenue') }}</td>
                                                            <td class="py-2 text-sm text-gray-900 text-right">{{ format_currency($scenarioResults['break_even_revenue'] ?? 0) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="py-2 text-sm text-gray-900">{{ __('Contribution Margin') }}</td>
                                                            <td class="py-2 text-sm text-gray-900 text-right">{{ number_format($scenarioResults['contribution_margin'] ?? 0, 2) }}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="py-2 text-sm text-gray-900">{{ __('Profit at Target Units') }}</td>
                                                            <td class="py-2 text-sm text-right">
                                                                @php
                                                                    $profit = $scenarioResults['profit_at_target'] ?? 0;
                                                                    $profitClass = $profit >= 0 ? 'text-green-600' : 'text-red-600';
                                                                @endphp
                                                                <span class="{{ $profitClass }}">
                                                                    {{ format_currency($profit) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Sensitivity Analysis') }}</h4>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead>
                                                        <tr class="border-b border-gray-200">
                                                            <th class="py-2 text-left text-sm font-medium text-gray-900">{{ __('Price Change') }}</th>
                                                            <th class="py-2 text-left text-sm font-medium text-gray-900">{{ __('Break-Even Units') }}</th>
                                                            <th class="py-2 text-left text-sm font-medium text-gray-900">{{ __('Impact') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        @foreach($scenarioResults['sensitivity'] ?? [] as $sensitivity)
                                                            <tr>
                                                                <td class="py-2 text-sm">
                                                                    @php
                                                                        $changeClass = $sensitivity['change'] >= 0 ? 'text-green-600' : 'text-red-600';
                                                                    @endphp
                                                                    <span class="{{ $changeClass }}">
                                                                        {{ $sensitivity['change'] >= 0 ? '+' : '' }}{{ $sensitivity['change'] }}%
                                                                    </span>
                                                                </td>
                                                                <td class="py-2 text-sm text-gray-900">{{ number_format($sensitivity['break_even_units']) }}</td>
                                                                <td class="py-2 text-sm">
                                                                    @php
                                                                        $impact = $sensitivity['impact'];
                                                                        $impactClass = $impact === 'positive' ? 'bg-green-100 text-green-800' : ($impact === 'negative' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                                                                    @endphp
                                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $impactClass }}">
                                                                        {{ __(ucfirst($impact)) }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Cost Structure Analysis -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="ti ti-chart-pie mr-2"></i>
                                {{ __('Cost Structure') }}
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="py-3 text-left text-sm font-medium text-gray-900">{{ __('Cost Type') }}</th>
                                            <th class="py-3 text-left text-sm font-medium text-gray-900">{{ __('Amount') }}</th>
                                            <th class="py-3 text-left text-sm font-medium text-gray-900">{{ __('Percentage') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="py-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ __('Fixed Costs') }}</span>
                                            </td>
                                            <td class="py-3 text-sm text-gray-900">{{ format_currency($breakEvenData['fixed_costs'] ?? 0) }}</td>
                                            <td class="py-3 text-sm text-gray-900">
                                                @php
                                                    $totalCosts = ($breakEvenData['fixed_costs'] ?? 0) + ($breakEvenData['variable_costs'] ?? 0);
                                                    $fixedPercentage = $totalCosts > 0 ? (($breakEvenData['fixed_costs'] ?? 0) / $totalCosts) * 100 : 0;
                                                @endphp
                                                {{ number_format($fixedPercentage, 1) }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">{{ __('Variable Costs') }}</span>
                                            </td>
                                            <td class="py-3 text-sm text-gray-900">{{ format_currency($breakEvenData['variable_costs'] ?? 0) }}</td>
                                            <td class="py-3 text-sm text-gray-900">
                                                @php
                                                    $variablePercentage = $totalCosts > 0 ? (($breakEvenData['variable_costs'] ?? 0) / $totalCosts) * 100 : 0;
                                                @endphp
                                                {{ number_format($variablePercentage, 1) }}%
                                            </td>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="py-3 font-semibold text-gray-900">{{ __('Total Costs') }}</td>
                                            <td class="py-3 font-semibold text-gray-900">{{ format_currency($totalCosts) }}</td>
                                            <td class="py-3 font-semibold text-gray-900">100%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="ti ti-trending-up mr-2"></i>
                                {{ __('Profitability Analysis') }}
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="py-3 text-left text-sm font-medium text-gray-900">{{ __('Metric') }}</th>
                                            <th class="py-3 text-left text-sm font-medium text-gray-900">{{ __('Value') }}</th>
                                            <th class="py-3 text-left text-sm font-medium text-gray-900">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="py-3 text-sm text-gray-900">{{ __('Current Sales') }}</td>
                                            <td class="py-3 text-sm text-gray-900">{{ number_format($breakEvenData['current_units'] ?? 0) }} {{ __('units') }}</td>
                                            <td class="py-3">
                                                @php
                                                    $currentUnits = $breakEvenData['current_units'] ?? 0;
                                                    $breakEvenUnits = $breakEvenData['break_even_units'] ?? 0;
                                                    $status = $currentUnits >= $breakEvenUnits ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                                    $statusText = $currentUnits >= $breakEvenUnits ? __('Profitable') : __('Loss');
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $status }}">{{ $statusText }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-3 text-sm text-gray-900">{{ __('Units Above/Below BE') }}</td>
                                            <td class="py-3 text-sm">
                                                @php
                                                    $difference = $currentUnits - $breakEvenUnits;
                                                    $differenceClass = $difference >= 0 ? 'text-green-600' : 'text-red-600';
                                                @endphp
                                                <span class="{{ $differenceClass }}">
                                                    {{ $difference >= 0 ? '+' : '' }}{{ number_format($difference) }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                @if($difference >= 0)
                                                    <i class="ti ti-trending-up text-green-600"></i>
                                                @else
                                                    <i class="ti ti-trending-down text-red-600"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-3 text-sm text-gray-900">{{ __('Contribution Margin Ratio') }}</td>
                                            <td class="py-3 text-sm text-gray-900">{{ number_format($breakEvenData['contribution_margin_ratio'] ?? 0, 2) }}%</td>
                                            <td class="py-3">
                                                @php
                                                    $cmRatio = $breakEvenData['contribution_margin_ratio'] ?? 0;
                                                    $cmStatus = $cmRatio >= 30 ? 'bg-green-100 text-green-800' : ($cmRatio >= 20 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                                    $cmText = $cmRatio >= 30 ? __('Good') : ($cmRatio >= 20 ? __('Fair') : __('Poor'));
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $cmStatus }}">{{ $cmText }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product-Specific Analysis -->
                @if($analysisType === 'product' && $selectedProduct && !empty($productAnalysis))
                    <div class="mb-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="ti ti-package mr-2"></i>
                                    {{ __('Product Break-Even Analysis') }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <div class="lg:col-span-2">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full">
                                                <thead>
                                                    <tr class="border-b border-gray-200">
                                                        <th class="py-3 text-left text-sm font-medium text-gray-900">{{ __('Metric') }}</th>
                                                        <th class="py-3 text-left text-sm font-medium text-gray-900">{{ __('Value') }}</th>
                                                        <th class="py-3 text-left text-sm font-medium text-gray-900">{{ __('Performance') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    <tr>
                                                        <td class="py-3 text-sm text-gray-900">{{ __('Product Name') }}</td>
                                                        <td class="py-3 text-sm text-gray-900" colspan="2">{{ $productAnalysis['product_name'] ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-3 text-sm text-gray-900">{{ __('Break-Even Units') }}</td>
                                                        <td class="py-3 text-sm text-gray-900">{{ number_format($productAnalysis['break_even_units'] ?? 0) }}</td>
                                                        <td class="py-3">
                                                            @php
                                                                $currentSales = $productAnalysis['current_sales'] ?? 0;
                                                                $beUnits = $productAnalysis['break_even_units'] ?? 0;
                                                                $performance = $beUnits > 0 ? ($currentSales / $beUnits) * 100 : 0;
                                                                $performanceClass = $performance >= 100 ? 'bg-green-100 text-green-800' : ($performance >= 80 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                                            @endphp
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $performanceClass }}">
                                                                {{ number_format($performance, 1) }}%
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-3 text-sm text-gray-900">{{ __('Current Sales') }}</td>
                                                        <td class="py-3 text-sm text-gray-900">{{ number_format($productAnalysis['current_sales'] ?? 0) }}</td>
                                                        <td class="py-3">
                                                            @if($currentSales >= $beUnits)
                                                                <i class="ti ti-check text-green-600"></i>
                                                            @else
                                                                <i class="ti ti-x text-red-600"></i>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-3 text-sm text-gray-900">{{ __('Unit Contribution') }}</td>
                                                        <td class="py-3 text-sm text-gray-900">{{ format_currency($productAnalysis['unit_contribution'] ?? 0) }}</td>
                                                        <td class="py-3">
                                                            @php
                                                                $contribution = $productAnalysis['unit_contribution'] ?? 0;
                                                                $contributionClass = $contribution > 0 ? 'text-green-600' : 'text-red-600';
                                                            @endphp
                                                            <span class="{{ $contributionClass }}">
                                                                {{ $contribution > 0 ? __('Positive') : __('Negative') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-3 text-sm text-gray-900">{{ __('Margin of Safety') }}</td>
                                                        <td class="py-3 text-sm text-gray-900">{{ number_format($productAnalysis['margin_of_safety'] ?? 0, 2) }}%</td>
                                                        <td class="py-3">
                                                            @php
                                                                $margin = $productAnalysis['margin_of_safety'] ?? 0;
                                                                $marginClass = $margin >= 20 ? 'bg-green-100 text-green-800' : ($margin >= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                                                $marginText = $margin >= 20 ? __('Safe') : ($margin >= 10 ? __('Moderate') : __('Risky'));
                                                            @endphp
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $marginClass }}">{{ $marginText }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Recommendation') }}</h4>
                                            @php
                                                $recommendation = $productAnalysis['recommendation'] ?? '';
                                                $recommendationClass = '';
                                                $recommendationIcon = '';
                                                
                                                switch($recommendation) {
                                                    case 'profitable':
                                                        $recommendationClass = 'text-green-600';
                                                        $recommendationIcon = 'ti-check-circle';
                                                        $recommendationText = __('Product is profitable and performing well');
                                                        break;
                                                    case 'monitor':
                                                        $recommendationClass = 'text-yellow-600';
                                                        $recommendationIcon = 'ti-alert-triangle';
                                                        $recommendationText = __('Monitor closely - near break-even');
                                                        break;
                                                    case 'improve':
                                                        $recommendationClass = 'text-red-600';
                                                        $recommendationIcon = 'ti-trending-down';
                                                        $recommendationText = __('Needs improvement - below break-even');
                                                        break;
                                                    default:
                                                        $recommendationClass = 'text-gray-500';
                                                        $recommendationIcon = 'ti-info-circle';
                                                        $recommendationText = __('Analysis in progress');
                                                }
                                            @endphp
                                            <div class="mb-4">
                                                <i class="ti {{ $recommendationIcon }} {{ $recommendationClass }} text-5xl"></i>
                                            </div>
                                            <p class="{{ $recommendationClass }} text-sm">
                                                {{ $recommendationText }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @push('scripts')
        @if(!empty($chartData))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const chartData = @json($chartData);
                    const units = chartData.units || [];
                    const revenue = chartData.revenue || [];
                    const totalCosts = chartData.total_costs || [];
                    const fixedCosts = chartData.fixed_costs || [];
                    const variableCosts = chartData.variable_costs || [];

                    const options = {
                        series: [
                            {
                                name: '{{ __('Revenue') }}',
                                type: 'line',
                                data: revenue
                            },
                            {
                                name: '{{ __('Total Costs') }}',
                                type: 'line',
                                data: totalCosts
                            },
                            {
                                name: '{{ __('Fixed Costs') }}',
                                type: 'line',
                                data: fixedCosts
                            },
                            {
                                name: '{{ __('Variable Costs') }}',
                                type: 'line',
                                data: variableCosts
                            }
                        ],
                        chart: {
                            height: 400,
                            type: 'line',
                            toolbar: {
                                show: true
                            }
                        },
                        stroke: {
                            width: [3, 3, 2, 2],
                            curve: 'smooth',
                            dashArray: [0, 0, 5, 5]
                        },
                        xaxis: {
                            categories: units,
                            title: {
                                text: '{{ __('Units Sold') }}'
                            }
                        },
                        yaxis: {
                            title: {
                                text: '{{ __('Amount') }}'
                            },
                            labels: {
                                formatter: function (val) {
                                    return new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: 'USD'
                                    }).format(val);
                                }
                            }
                        },
                        colors: ['#206bc4', '#e74c3c', '#f39c12', '#9b59b6'],
                        legend: {
                            position: 'top'
                        },
                        annotations: {
                            points: [{
                                x: {{ $breakEvenData['break_even_units'] ?? 0 }},
                                y: {{ $breakEvenData['break_even_revenue'] ?? 0 }},
                                marker: {
                                    size: 8,
                                    fillColor: '#fff',
                                    strokeColor: '#e74c3c',
                                    radius: 2
                                },
                                label: {
                                    borderColor: '#e74c3c',
                                    offsetY: 0,
                                    style: {
                                        color: '#fff',
                                        background: '#e74c3c'
                                    },
                                    text: '{{ __('Break-Even Point') }}'
                                }
                            }]
                        }
                    };

                    const chart = new ApexCharts(document.querySelector('#break-even-chart'), options);
                    chart.render();
                });
            </script>
        @endif
    @endpush
</div>