@props(['isActive' => false, 'title' => '', 'collapsible' => false])

@php
    $baseClasses = 'flex items-center gap-3 py-2.5 px-3 rounded-xl transition-all duration-200';
    $activeClasses = $isActive
        ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-md shadow-indigo-500/25'
        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white';
    $classes = $baseClasses . ' ' . $activeClasses;
    if ($collapsible) {
        $classes .= ' w-full';
    }
@endphp

@if ($collapsible)
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon ?? false)
            <span class="shrink-0">{!! $icon !!}</span>
        @else
            <span class="shrink-0">
                <x-icons.empty-circle class="w-5 h-5 text-current opacity-60" aria-hidden="true" />
            </span>
        @endif

        <span class="flex-1 text-start" x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>

        <span x-show="isSidebarOpen || isSidebarHovered" aria-hidden="true" class="relative w-5 h-5">
            <span :class="open ? 'rotate-45' : '-rotate-45'"
                class="absolute right-[10px] top-1/2 -mt-1 h-4 w-[2px] bg-current opacity-60 transition-all duration-200"></span>
            <span :class="open ? '-rotate-45' : 'rotate-45'"
                class="absolute left-[10px] top-1/2 -mt-1 h-4 w-[2px] bg-current opacity-60 transition-all duration-200"></span>
        </span>
    </button>
@else
    <a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
        @if ($icon ?? false)
            <span class="shrink-0">{!! $icon !!}</span>
        @else
            <span class="shrink-0">
                <x-icons.empty-circle class="w-5 h-5 text-current opacity-60" aria-hidden="true" />
            </span>
        @endif

        <span class="flex-1" x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>
    </a>
@endif