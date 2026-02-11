@props([
    'title' => null,
    'breadcrumbs' => [],
    'actions' => null,
    'filters' => null,
    'showFilters' => false,
])

<!-- Page Header -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
        <!-- Title and Breadcrumbs -->
        <div class="flex flex-col items-center justify-start">
            @if ($title)
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $title }}</h1>
            @endif

            @if (!empty($breadcrumbs))
                <nav class="w-full" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        @foreach ($breadcrumbs as $index => $breadcrumb)
                            <li class="flex items-center">
                                @if ($index > 0)
                                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                                @endif

                                @if (isset($breadcrumb['url']) && !$loop->last)
                                    <a href="{{ $breadcrumb['url'] }}"
                                        class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                @else
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $breadcrumb['label'] ?? '' }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @endif
        </div>

        <!-- Actions -->
        @if ($actions)
            <div class="flex items-center space-x-3">
                {{ $actions }}
            </div>
        @endif
    </div>

    <!-- Filters Section -->
    @if ($showFilters && $filters)
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-4">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                    {{ $filters }}
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div>
        {{ $slot }}
    </div>
</div>
