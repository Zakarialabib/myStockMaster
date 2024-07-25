@props(['colors'])

@php
    $colors = ['bg-gray-100', 'bg-gray-200', 'bg-gray-300', 'bg-gray-400', 'bg-gray-500', 'bg-gray-600', 'bg-gray-700', 'bg-gray-800', 'bg-gray-900'];
@endphp

<div>
    @foreach ($colors as $color)
        <button class="w-6 h-6 rounded-full bg-{{ $color }} cursor-pointer"
            wire:click="$emit('colorSelected', '{{ $color }}')"></button>
    @endforeach
</div>
