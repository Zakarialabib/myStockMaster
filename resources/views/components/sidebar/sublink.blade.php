@props(['title' => '', 'active' => false, 'collapsible' => false, 'icon' => null])

@php
    $baseClasses = 'flex items-center gap-3 py-2 ps-2 pe-3 rounded-xl transition-all duration-200 font-bold text-sm';
    $activeClasses = $active
        ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-300'
        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-primary-600 dark:hover:text-primary-400';
    $classes = $baseClasses . ' ' . $activeClasses;
    if ($collapsible) {
        $classes .= ' w-full';
    }
@endphp

@if ($collapsible)
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
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
        @if ($icon)
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
