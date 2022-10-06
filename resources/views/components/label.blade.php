@props(['for', 'value', 'required' => false])

@php
    $requiredClasses = 'text-red-500';
    $requiredLabel = '*';
@endphp

<label for="{{ $for }}" {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
    @if ($required)
        <span class="{{ $requiredClasses }}">{{ $requiredLabel }}</span>
    @endif
</label>