@props([
    'type' => null, 'href' => '#', 'icon' => null, 'iconPosition' => 'left',
    'size' => 'md', 'variant' => 'primary', 'outline' => false,
    'primary' => false, 'secondary' => false, 'info'=> false, 'alert' => false, 
    'success' => false,'danger' => false, 'warning' => false, 
    'primaryOutline' => false,'secondaryOutline' => false,
    'infoOutline' => false,'successOutline' => false,
    'alertOutline' => false,'dangerOutline' => false,
    'warningOutline' => false,
])

@php
    // Size classes
    $sizeClasses = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base',
        'xl' => 'px-6 py-3 text-base',
    ];
    
    // Determine variant from legacy props
    if ($primary || $primaryOutline) $variant = 'primary';
    elseif ($secondary || $secondaryOutline) $variant = 'secondary';
    elseif ($info || $infoOutline) $variant = 'info';
    elseif ($success || $successOutline) $variant = 'success';
    elseif ($danger || $dangerOutline) $variant = 'danger';
    elseif ($warning || $warningOutline) $variant = 'warning';
    elseif ($alert || $alertOutline) $variant = 'alert';
    
    // Determine outline from legacy props
    if ($primaryOutline || $secondaryOutline || $infoOutline || $successOutline || $dangerOutline || $warningOutline || $alertOutline) {
        $outline = true;
    }
    
    // Variant classes
    $variantClasses = [
        'primary' => $outline ? 'border-blue-600 text-blue-600 hover:bg-blue-50 dark:border-blue-400 dark:text-blue-400 dark:hover:bg-blue-900/20' : 'bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600',
        'secondary' => $outline ? 'border-gray-600 text-gray-600 hover:bg-gray-50 dark:border-gray-400 dark:text-gray-400 dark:hover:bg-gray-800' : 'bg-gray-600 text-white hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600',
        'info' => $outline ? 'border-cyan-600 text-cyan-600 hover:bg-cyan-50 dark:border-cyan-400 dark:text-cyan-400 dark:hover:bg-cyan-900/20' : 'bg-cyan-600 text-white hover:bg-cyan-700 dark:bg-cyan-500 dark:hover:bg-cyan-600',
        'success' => $outline ? 'border-green-600 text-green-600 hover:bg-green-50 dark:border-green-400 dark:text-green-400 dark:hover:bg-green-900/20' : 'bg-green-600 text-white hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600',
        'danger' => $outline ? 'border-red-600 text-red-600 hover:bg-red-50 dark:border-red-400 dark:text-red-400 dark:hover:bg-red-900/20' : 'bg-red-600 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600',
        'warning' => $outline ? 'border-yellow-600 text-yellow-600 hover:bg-yellow-50 dark:border-yellow-400 dark:text-yellow-400 dark:hover:bg-yellow-900/20' : 'bg-yellow-600 text-white hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600',
        'alert' => $outline ? 'border-orange-600 text-orange-600 hover:bg-orange-50 dark:border-orange-400 dark:text-orange-400 dark:hover:bg-orange-900/20' : 'bg-orange-600 text-white hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600',
    ];
    
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    $borderClass = $outline ? 'border-2 bg-transparent' : 'border border-transparent';
    
    $classes = $baseClasses . ' ' . $borderClass . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']);
@endphp

@if ($type == 'submit' || $type == 'button')
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'ml-2' }}"></i>
        @endif
    </button>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'ml-2' }}"></i>
        @endif
    </a>
@endif