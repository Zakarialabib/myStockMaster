@props([
    'label' => '',
    'color' => 'primary',
    'class' => '',
])

@php
    $colorMap = [
        'primary' => [
            'bg' => 'bg-primary-100 dark:bg-primary-900/40',
            'text' => 'text-primary-700 dark:text-primary-300',
            'border' => 'border-primary-200 dark:border-primary-800',
        ],
        'secondary' => [
            'bg' => 'bg-gray-100 dark:bg-gray-800',
            'text' => 'text-gray-700 dark:text-gray-300',
            'border' => 'border-gray-200 dark:border-gray-700',
        ],
        'success' => [
            'bg' => 'bg-success-100 dark:bg-success-900/40',
            'text' => 'text-success-700 dark:text-success-300',
            'border' => 'border-success-200 dark:border-success-800',
        ],
        'warning' => [
            'bg' => 'bg-warning-100 dark:bg-warning-900/40',
            'text' => 'text-warning-700 dark:text-warning-300',
            'border' => 'border-warning-200 dark:border-warning-800',
        ],
        'error' => [
            'bg' => 'bg-error-100 dark:bg-error-900/40',
            'text' => 'text-error-700 dark:text-error-300',
            'border' => 'border-error-200 dark:border-error-800',
        ],
        'info' => [
            'bg' => 'bg-blue-100 dark:bg-blue-900/40',
            'text' => 'text-blue-700 dark:text-blue-300',
            'border' => 'border-blue-200 dark:border-blue-800',
        ],
    ];

    $colors = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-lg border {{ $colors['bg'] }} {{ $colors['text'] }} {{ $colors['border'] }} {{ $class }}">
    {{ $label }}
</span>
