@props([
    'padding' => null,
    'wrap' => 'whitespace-nowrap',
])

@php
    $padding = $padding ?? 'px-4 py-4';
@endphp
<td {{ $attributes->merge(['class' => "{$padding} {$wrap} text-sm"]) }}>
    {{ $slot }}
</td>
