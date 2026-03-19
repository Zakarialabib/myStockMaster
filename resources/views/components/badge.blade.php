@props(['type' => 'secondary'])

@php
    $badgeClasses = [
        'primary' => 'text-primary-700 bg-primary-100 dark:bg-primary-900/30 dark:text-primary-300 border border-primary-200 dark:border-primary-800',
        'secondary' => 'text-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700',
        'info' => 'text-blue-700 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
        'success' => 'text-success-700 bg-success-100 dark:bg-success-900/30 dark:text-success-300 border border-success-200 dark:border-success-800',
        'alert' => 'text-warning-700 bg-warning-100 dark:bg-warning-900/30 dark:text-warning-300 border border-warning-200 dark:border-warning-800',
        'danger' => 'text-error-700 bg-error-100 dark:bg-error-900/30 dark:text-error-300 border border-error-200 dark:border-error-800',
        'warning' => 'text-orange-700 bg-orange-100 dark:bg-orange-900/30 dark:text-orange-300 border border-orange-200 dark:border-orange-800'
    ];

    $classes = $badgeClasses[$type] ?? $badgeClasses['secondary'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold leading-none rounded-xl ' . $classes]) }}>
    {{ $slot }}
</span>
