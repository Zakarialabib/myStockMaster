@props([
    'tableRowclass' => null,
    'hasDivider' => false,
    'divider' => 'divide-x divide-slate-100'
])

@php
    $divider = $hasDivider ? $divider : '';
@endphp

<tr {{ $attributes->merge(['class' => "hover:bg-indigo-50 {$divider}"]) }}>
    {{ $slot }}
</tr>
