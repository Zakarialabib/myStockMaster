@props([
    'status' => 'default',
    'size' => 'md',
    'variant' => 'filled', // filled, outlined, soft
    'icon' => null,
    'pulse' => false
])

@php
    // Size classes
    $sizeClasses = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2.5 py-1 text-xs',
        'md' => 'px-3 py-1.5 text-sm',
        'lg' => 'px-4 py-2 text-base'
    ];
    
    // Status color mappings
    $statusColors = [
        'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'filled' => 'bg-green-600 text-white'],
        'inactive' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200', 'filled' => 'bg-gray-600 text-white'],
        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200', 'filled' => 'bg-yellow-600 text-white'],
        'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'filled' => 'bg-green-600 text-white'],
        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200', 'filled' => 'bg-red-600 text-white'],
        'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200', 'filled' => 'bg-gray-600 text-white'],
        'published' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200', 'filled' => 'bg-blue-600 text-white'],
        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'filled' => 'bg-green-600 text-white'],
        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200', 'filled' => 'bg-red-600 text-white'],
        'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200', 'filled' => 'bg-blue-600 text-white'],
        'shipped' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-200', 'filled' => 'bg-purple-600 text-white'],
        'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'filled' => 'bg-green-600 text-white'],
        'returned' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'border' => 'border-orange-200', 'filled' => 'bg-orange-600 text-white'],
        'low_stock' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200', 'filled' => 'bg-red-600 text-white'],
        'in_stock' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'filled' => 'bg-green-600 text-white'],
        'out_of_stock' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200', 'filled' => 'bg-gray-600 text-white'],
        'success' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'filled' => 'bg-green-600 text-white'],
        'error' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200', 'filled' => 'bg-red-600 text-white'],
        'warning' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200', 'filled' => 'bg-yellow-600 text-white'],
        'info' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200', 'filled' => 'bg-blue-600 text-white'],
        'default' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200', 'filled' => 'bg-gray-600 text-white']
    ];
    
    $colors = $statusColors[$status] ?? $statusColors['default'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    
    // Build classes based on variant
    $classes = 'inline-flex items-center gap-1.5 font-medium rounded-full ' . $sizeClass;
    
    if ($variant === 'filled') {
        $classes .= ' ' . $colors['filled'];
    } elseif ($variant === 'outlined') {
        $classes .= ' bg-transparent border ' . $colors['border'] . ' ' . $colors['text'];
    } else { // soft variant
        $classes .= ' ' . $colors['bg'] . ' ' . $colors['text'];
    }
    
    // Dark mode support
    $classes .= ' dark:bg-opacity-20 dark:border-opacity-30';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($pulse)
        <span class="flex h-2 w-2 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75
                {{ $variant === 'filled' ? 'bg-white' : ($colors['text'] === 'text-green-800' ? 'bg-green-400' : ($colors['text'] === 'text-red-800' ? 'bg-red-400' : ($colors['text'] === 'text-yellow-800' ? 'bg-yellow-400' : 'bg-blue-400'))) }}"></span>
            <span class="relative inline-flex rounded-full h-2 w-2
                {{ $variant === 'filled' ? 'bg-white' : ($colors['text'] === 'text-green-800' ? 'bg-green-500' : ($colors['text'] === 'text-red-800' ? 'bg-red-500' : ($colors['text'] === 'text-yellow-800' ? 'bg-yellow-500' : 'bg-blue-500'))) }}"></span>
        </span>
    @elseif($icon)
        <i class="{{ $icon }}"></i>
    @endif
    
    {{ $slot }}
</span>