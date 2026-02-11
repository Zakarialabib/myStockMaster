@props([
    'placeholder' => null,
    'trailingAddOn' => null,
    'icon' => null,
    'size' => 'md'
])

@php
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-3 text-base',
    ];
    
    $baseClasses = 'block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 transition-all duration-200';
    $iconPadding = $icon ? 'pl-10' : '';
    $trailingPadding = $trailingAddOn ? 'rounded-r-none' : '';
    
    $classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . $iconPadding . ' ' . $trailingPadding;
@endphp

<div class="relative flex">
    @if($icon)
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="{{ $icon }} text-gray-400 dark:text-gray-500"></i>
        </div>
    @endif
    
    <select {{ $attributes->merge(['class' => $classes]) }}>
        @if ($placeholder)
            <option disabled value="" selected>{{ $placeholder }}</option>
        @endif

        {{ $slot }}
    </select>

    @if ($trailingAddOn)
        {{ $trailingAddOn }}
    @endif
</div>
