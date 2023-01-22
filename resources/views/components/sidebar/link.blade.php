@props(['isActive' => false, 'title' => '', 'collapsible' => false])

@php
$isActiveClasses = $isActive ? 'bg-indigo-500 text-white active:bg-indigo-500' : 'text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 dark:hover:text-zinc-300 dark:hover:bg-dark-eval-2';
$classes = 'flex items-center hover:text-white hover:bg-indigo-500 py-3 pr-4 rounded ' . $isActiveClasses;
if ($collapsible) {
    $classes .= ' w-full';
}
@endphp

@if ($collapsible)
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon ?? false)
            {{ $icon }}
        @else
        <span class="inline-block mx-4">
            <x-icons.empty-circle class="text-gray-200 w-5 h-5" aria-hidden="true" />
        </span>
        @endif

        <span x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>

        <span x-show="isSidebarOpen || isSidebarHovered" aria-hidden="true" class="relative block w-6 h-6 ml-auto">
            <span :class="open ? '-rotate-45' : 'rotate-45'"
                class="absolute right-[9px] bg-zinc-400 mt-[-5px] h-2 w-[2px] top-1/2 transition-all duration-200"></span>
            <span :class="open ? 'rotate-45' : '-rotate-45'"
                class="absolute left-[9px] bg-zinc-400 mt-[-5px] h-2 w-[2px] top-1/2 transition-all duration-200"></span>
        </span>
    </button>
    
@else
    <a {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon ?? false)
            {{ $icon }}
        @else
            <span class="inline-block mx-4">
                <x-icons.empty-circle class="text-gray-200 w-5 h-5" aria-hidden="true" />
            </span>
        @endif

        <span x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>

    </a>

@endif
