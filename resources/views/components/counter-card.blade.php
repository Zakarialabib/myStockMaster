@props(['href' => '#', 'color' => 'primary', 'counter', 'title'])

@php
    $colorMap = [
        'primary' => [
            'bg' => 'bg-primary-50 dark:bg-primary-900/30',
            'text' => 'text-primary-600 dark:text-primary-400',
            'border' => 'border-primary-200 dark:border-primary-800',
            'gradient_from' => 'from-primary-600',
            'gradient_to' => 'to-primary-500',
        ],
        'success' => [
            'bg' => 'bg-success-50 dark:bg-success-900/30',
            'text' => 'text-success-600 dark:text-success-400',
            'border' => 'border-success-200 dark:border-success-800',
            'gradient_from' => 'from-success-600',
            'gradient_to' => 'to-success-500',
        ],
        'warning' => [
            'bg' => 'bg-warning-50 dark:bg-warning-900/30',
            'text' => 'text-warning-600 dark:text-warning-400',
            'border' => 'border-warning-200 dark:border-warning-800',
            'gradient_from' => 'from-warning-600',
            'gradient_to' => 'to-warning-500',
        ],
        'error' => [
            'bg' => 'bg-error-50 dark:bg-error-900/30',
            'text' => 'text-error-600 dark:text-error-400',
            'border' => 'border-error-200 dark:border-error-800',
            'gradient_from' => 'from-error-600',
            'gradient_to' => 'to-error-500',
        ],
        'info' => [
            'bg' => 'bg-blue-50 dark:bg-blue-900/30',
            'text' => 'text-blue-600 dark:text-blue-400',
            'border' => 'border-blue-200 dark:border-blue-800',
            'gradient_from' => 'from-blue-600',
            'gradient_to' => 'to-blue-500',
        ],
    ];

    $colors = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div>
    <a class="block p-5 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-soft hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group w-full"
        href="{{ $href }}">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $colors['bg'] }} {{ $colors['text'] }} group-hover:bg-gradient-to-br group-hover:{{ $colors['gradient_from'] }} group-hover:{{ $colors['gradient_to'] }} group-hover:text-white transition-all duration-300">
                {{ $slot }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-blue-500 dark:text-blue-400 uppercase tracking-widest truncate mb-1">
                    {{ $title }}
                </p>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                    {{ $counter }}
                </h3>
            </div>
        </div>
    </a>
</div>
