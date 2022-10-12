@props(['title' => '', 'active' => false])

@php
    
$classes = 'transition-colors hover:text-gray-900 dark:hover:text-gray-100';
$active 
    ? $classes .= ' text-gray-900 dark:text-gray-200' 
    : $classes .= ' text-gray-500 dark:text-gray-400';
@endphp

<li>
    <a class="flex items-center pl-3 py-3 pr-4 text-indigo-500 hover:text-white hover:bg-indigo-500 rounded" {{ $attributes->merge(['class' => $classes]) }}>{{ $title }}</a>
</li>