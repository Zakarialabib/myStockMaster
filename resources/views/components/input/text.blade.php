{{--
-- Important note:
--
-- This template is based on an example from Tailwind UI, and is used here with permission from Tailwind Labs
-- for educational purposes only. Please do not use this template in your own projects without purchasing a
-- Tailwind UI license, or they’ll have to tighten up the licensing and you’ll ruin the fun for everyone.
--
-- Purchase here: https://tailwindui.com/
--}}

@props([
    'leadingAddOn' => null,
    'icon' => null,
    'size' => 'md',
    'type' => 'text'
])

@php
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-3 text-base',
    ];
    
    $baseClasses = 'block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 transition-all duration-200';
    $iconPadding = $icon ? 'pl-10' : '';
    $leadingPadding = $leadingAddOn ? 'rounded-l-none' : '';
    
    $classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . $iconPadding . ' ' . $leadingPadding;
@endphp

<div class="relative flex">
    @if ($leadingAddOn)
        {{ $leadingAddOn }}
    @endif
    
    @if($icon)
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="{{ $icon }} text-gray-400 dark:text-gray-500"></i>
        </div>
    @endif

    <input type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} />
</div>
