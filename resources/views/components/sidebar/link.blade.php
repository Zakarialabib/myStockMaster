@props(['isActive' => false, 'title' => '', 'collapsible' => false])

@php
    $baseClasses = 'flex items-center gap-3 py-2.5 px-3 rounded-xl transition-all duration-200 font-bold';
    $activeClasses = $isActive
        ? 'bg-gradient-to-br from-primary-600 to-primary-500 text-white shadow-lg shadow-primary-500/25'
        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-primary-600 dark:hover:text-primary-400';
    $classes = $baseClasses . ' ' . $activeClasses;
    if ($collapsible) {
        $classes .= ' w-full';
    }
@endphp

@if ($collapsible)
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon ?? false)
            <span class="shrink-0 w-6 flex items-center justify-center">{!! $icon !!}</span>
        @else
            <span class="shrink-0 w-6 flex items-center justify-center">
                <x-icons.empty-circle class="w-5 h-5 text-current opacity-60" aria-hidden="true" />
            </span>
        @endif

        <span class="flex-1 text-start whitespace-nowrap overflow-hidden text-ellipsis" x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>
    </button>
@else
    <a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
        @if ($icon ?? false)
            <span class="shrink-0 w-6 flex items-center justify-center">{!! $icon !!}</span>
        @else
            <span class="shrink-0 w-6 flex items-center justify-center">
                <x-icons.empty-circle class="w-5 h-5 text-current opacity-60" aria-hidden="true" />
            </span>
        @endif

        <span class="flex-1 whitespace-nowrap overflow-hidden text-ellipsis" x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>
    </a>
@endif