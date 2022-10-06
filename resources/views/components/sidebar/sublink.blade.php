@props(['title' => '', 'active' => false])

@php
    
$classes = 'transition-colors hover:text-zinc-900 dark:hover:text-zinc-100';
$active 
    ? $classes .= ' text-zinc-900 dark:text-zinc-200' 
    : $classes .= ' text-zinc-500 dark:text-zinc-400';
@endphp

<li>
    <a class="flex items-center pl-3 py-3 pr-4 text-gray-500 hover:bg-indigo-50 rounded" {{ $attributes->merge(['class' => $classes]) }}>{{ $title }}</a>
</li>