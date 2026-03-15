<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('desktop.logging.error_history') }}</h1>
            <p class="text-gray-600 mt-1">Monitor and manage desktop application errors</p>
        </div>

        <div class="flex items-center space-x-3">
            <button wire:click="toggleStatistics"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                {{ $showStatistics ? 'Hide' : 'Show' }} Statistics
            </button>

            <button wire:click="exportErrorLog"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Export Log
            </button>

            <button wire:click="clearErrorHistory"
                wire:confirm="Are you sure you want to clear all error history? This action cannot be undone."
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                {{ __('desktop.logging.clear_error_history') }}
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Panel -->
    @if ($showStatistics)
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('desktop.logging.error_statistics') }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $statistics['total_errors'] }}</div>
                    <div class="text-sm text-blue-800">{{ __('desktop.logging.total_errors') }}</div>
                </div>

                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600">{{ $statistics['errors_today'] }}</div>
                    <div class="text-sm text-yellow-800">{{ __('desktop.logging.errors_today') }}</div>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $statistics['errors_this_week'] }}</div>
                    <div class="text-sm text-purple-800">{{ __('desktop.logging.errors_this_week') }}</div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $statistics['most_recent'] ? $this->getRelativeTime($statistics['most_recent']['timestamp']) : 'None' }}
                    </div>
                    <div class="text-sm text-green-800">{{ __('desktop.logging.most_recent_error') }}</div>
                </div>
            </div>

            <!-- Severity Breakdown -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-900 mb-3">{{ __('desktop.logging.error_severity') }}</h3>
                    <div class="space-y-2">
                        @foreach ($statistics['by_severity'] as $severity => $count)
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-1 rounded text-sm {{ $this->getSeverityColor($severity) }}">
                                    {{ ucfirst($severity) }}
                                </span>
                                <span class="font-medium">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="font-medium text-gray-900 mb-3">{{ __('desktop.logging.error_category') }}</h3>
                    <div class="space-y-2">
                        @foreach ($statistics['by_category'] as $category => $count)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="w-4 h-4 text-gray-500" {{ $this->getCategoryIcon($category) }}></i>
                                    <span>{{ ucfirst($category) }}</span>
                                </div>
                                <span class="font-medium">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if ($statistics['most_frequent'])
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">{{ __('desktop.logging.most_frequent_error') }}</h3>
                    <p class="text-sm text-gray-700">{{ $statistics['most_frequent']['message'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Occurred {{ $statistics['most_frequent']['count'] }} times
                    </p>
                </div>
            @endif
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" wire:model.live.debounce.300ms="searchTerm" placeholder="Search errors..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                <select wire:model.live="filterSeverity"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Severities</option>
                    @foreach ($severityOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select wire:model.live="filterCategory"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach ($categoryOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" wire:model.live="filterDateFrom"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-end">
                <button wire:click="resetFilters"
                    class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Error List -->
    <div class="bg-white rounded-lg shadow-sm border">
        @if (empty($errorHistory))
            <div class="p-8 text-center">
                <i class="w-16 h-16 text-green-500 mx-auto mb-4" {{ $this->getCategoryIcon('application') }}></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('desktop.logging.no_errors') }}</h3>
                <p class="text-gray-600">No errors match your current filters.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('desktop.logging.error_timestamp') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('desktop.logging.error_severity') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('desktop.logging.error_category') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('desktop.logging.error_message') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($errorHistory as $error)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ $this->formatTimestamp($error['timestamp']) }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $this->getRelativeTime($error['timestamp']) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 rounded text-xs {{ $this->getSeverityColor($error['severity'] ?? 'medium') }}">
                                        {{ ucfirst($error['severity'] ?? 'medium') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center space-x-2">
                                        <i class="w-4 h-4 text-gray-500"
                                            {{ $this->getCategoryIcon($error['category'] ?? 'application') }}></i>
                                        <span>{{ ucfirst($error['category'] ?? 'application') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs truncate" title="{{ $error['message'] }}">
                                        {{ $error['message'] }}
                                    </div>
                                    @if (isset($error['file']))
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ basename($error['file']) }}:{{ $error['line'] ?? '?' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="viewErrorDetails('{{ $error['id'] }}')"
                                        class="text-blue-600 hover:text-blue-900">
                                        {{ __('desktop.logging.view_error_details') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Error Details Modal -->
    @if ($selectedError)
        <div x-data="{ show: false }" x-show="show" x-on:show-error-modal.window="show = true"
            x-on:hide-error-modal.window="show = false" class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                    x-on:click="$wire.closeErrorDetails()"></div>

                <div
                    class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('desktop.logging.view_error_details') }}
                        </h3>
                        <button wire:click="closeErrorDetails" class="text-gray-400 hover:text-gray-600">
                            <i class="w-6 h-6" {{ $this->getCategoryIcon('application') }}></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700">{{ __('desktop.logging.error_timestamp') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $this->formatTimestamp($selectedError['timestamp']) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700">{{ __('desktop.logging.error_severity') }}</label>
                                <span
                                    class="mt-1 inline-block px-2 py-1 rounded text-xs {{ $this->getSeverityColor($selectedError['severity'] ?? 'medium') }}">
                                    {{ ucfirst($selectedError['severity'] ?? 'medium') }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700">{{ __('desktop.logging.error_message') }}</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded">
                                {{ $selectedError['message'] }}</p>
                        </div>

                        @if (isset($selectedError['file']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">File & Line</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $selectedError['file'] }}:{{ $selectedError['line'] ?? '?' }}</p>
                            </div>
                        @endif

                        @if (isset($selectedError['trace']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stack Trace</label>
                                <pre class="mt-1 text-xs text-gray-900 bg-gray-50 p-3 rounded overflow-x-auto">{{ $selectedError['trace'] }}</pre>
                            </div>
                        @endif

                        @if (isset($selectedError['context']) && !empty($selectedError['context']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Context</label>
                                <pre class="mt-1 text-xs text-gray-900 bg-gray-50 p-3 rounded overflow-x-auto">{{ json_encode($selectedError['context'], JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button wire:click="closeErrorDetails"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
