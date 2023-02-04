@props(['for', 'value', 'required' => false, 'tooltip' => null])

@php
    $requiredClasses = 'text-red-500';
    $requiredLabel = '*';
@endphp

<label for="{{ $for }}" {{ $attributes->merge(['class' => 'block font-bold text-sm text-gray-700 mt-2']) }}>
    {{ $value ?? $slot }}

    @if ($required)
        <span class="{{ $requiredClasses }}">{{ $requiredLabel }}</span>
    @endif

    @if($tooltip)
    <i class="fas fa-info-circle text-red-400 ml-1" data-toggle="tooltip" data-placement="top" title="{{ $tooltip }}"></i>
    @endif
</label>
