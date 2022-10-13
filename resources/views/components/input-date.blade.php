@props(['name', 'value' => null, 'placeholder' => null, 'required' => false])

@php
    $id = $attributes->get('id', Str::random(10));
@endphp

<input
    id="{{ $id }}"
    name="{{ $name }}"
    type="date"
    required
    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
    placeholder="{{ $placeholder }}"
    value="{{ $value }}"
    {{ $attributes->merge(['class' => '']) }}
/>
