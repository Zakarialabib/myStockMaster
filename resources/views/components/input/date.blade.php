@props([
    'value' => null,
    'name' => null,
    'id' => null,
    'size' => 'md',
    'placeholder' => 'Select date'
])

@php
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-3 text-base',
    ];
    
    $baseClasses = 'block w-full pl-10 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 transition-all duration-200 cursor-pointer';
    
    $classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

<div class="relative">
    <!-- Calendar Icon -->
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <i class="fas fa-calendar-alt text-gray-400 dark:text-gray-500"></i>
    </div>
    
    <input
        type="text"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => $classes]) }}
        readonly
    />
    
    <!-- Clear Button -->
    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
        <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" onclick="clearDate('{{ $id }}')">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const picker = new Pikaday({
            field: document.getElementById('{{ $id }}'),
            format: 'YYYY-MM-DD',
            yearRange: [2000, 2030],
            theme: 'dark-theme'
        });
    });
    
    function clearDate(fieldId) {
        document.getElementById(fieldId).value = '';
    }
</script>
