@props(['isActive' => false, 'title' => '', 'collapsible' => false])

@php
    $isActiveClasses = $isActive ? 'bg-indigo-500 text-white active:bg-indigo-500' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-100';
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
                <x-icons.empty-circle class="text-gray-600 w-5 h-5" aria-hidden="true" />
            </span>
        @endif

        <span x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>

        <span x-show="isSidebarOpen || isSidebarHovered" aria-hidden="true" class="relative block w-6 h-6 ml-auto">
            <span :class="open ? '-rotate-45' : 'rotate-45'"
                class="absolute right-[9px] bg-gray-400 mt-[-5px] h-2 w-[2px] top-1/2 transition-all duration-200"></span>
            <span :class="open ? 'rotate-45' : '-rotate-45'"
                class="absolute left-[9px] bg-gray-400 mt-[-5px] h-2 w-[2px] top-1/2 transition-all duration-200"></span>
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
