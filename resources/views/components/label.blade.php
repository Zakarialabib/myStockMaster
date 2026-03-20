@props(['for', 'value', 'required' => false, 'tooltip' => null])

@php
    $requiredClasses = 'text-error-500 ml-1';
    $requiredLabel = '*';
@endphp

<label for="{{ $for }}" {{ $attributes->merge(['class' => 'block font-bold text-sm text-gray-800 dark:text-gray-200 mt-2 mb-1.5 tracking-tight']) }}>
    {{ $value ?? $slot }}

    @if ($required)
        <span class="{{ $requiredClasses }}">{{ $requiredLabel }}</span>
    @endif

    @if($tooltip)
    <i class="fas fa-info-circle text-primary-400 ml-1.5" data-toggle="tooltip" data-placement="top" title="{{ $tooltip }}"></i>
    @endif
</label>
