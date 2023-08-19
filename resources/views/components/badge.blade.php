@props(['type' => 'secondary'])

@php
    $badgeClasses = [
        'primary' => 'text-white bg-indigo-500 hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300',
        'secondary' => 'text-gray-100 bg-gray-500 hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300',
        'info' => 'text-blue-100 bg-blue-500 hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300',
        'success' => 'text-green-100 bg-green-500 hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300',
        'alert' => 'text-yellow-100 bg-yellow-500 hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300',
        'danger' => 'text-red-100 bg-red-500 hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300',
        'warning' => 'text-orange-100 bg-orange-500 hover:bg-orange-700 active:bg-orange-900 focus:outline-none focus:border-orange-900 focus:ring ring-orange-300'
    ];

    $classes = $badgeClasses[$type] ?? $badgeClasses['secondary'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-2 py-1 mr-2 text-xs font-bold leading-none rounded-full ' . $classes]) }}>
    {{ $slot }}
</span>
