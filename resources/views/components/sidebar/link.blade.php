@props(['isActive' => false, 'title' => '', 'collapsible' => false])

@php
    $isActiveClasses =  $isActive ? 'bg-green-500 text-white' : 'text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 dark:hover:text-zinc-300 dark:hover:bg-dark-eval-2';  
    $classes = 'px-2 py-4 transition-colors rounded-md ' . $isActiveClasses;
    if($collapsible) $classes .= ' w-full';
@endphp

@if ($collapsible)
    <button type="button" {{ $attributes->merge(['class' => $classes]) }} >
        @if ($icon ?? false)
            {{ $icon }}
        @else
            <x-icons.empty-circle class="w-8 h-8 block my-0 mx-auto" aria-hidden="true" />
        @endif

        <span class="text-base font-medium block my-0 mx-auto whitespace-nowrap" x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>

        <span  x-show="isSidebarOpen || isSidebarHovered" aria-hidden="true" class="relative block mx-auto w-6 h-6">
            <span :class="open ? '-rotate-45' : 'rotate-45'" class="absolute right-[9px] bg-zinc-400 mt-[-5px] h-2 w-[2px] top-1/2 transition-all duration-200"></span>
            <span :class="open ? 'rotate-45' : '-rotate-45'" class="absolute left-[9px] bg-zinc-400 mt-[-5px] h-2 w-[2px] top-1/2 transition-all duration-200"></span>
        </span>
    </button>
@else
    <a {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon ?? false)
            {{ $icon }}
        @else
            <x-icons.empty-circle class="flex-shrink-0 w-6 h-6 block my-0 mx-auto" aria-hidden="true" />
        @endif

        <span class="text-base font-medium text-center block my-0 mx-auto" x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>
    </a>
@endif