@props(['active' => false, 'title' => ''])

<div class="relative" x-data="{ open: @json($active) }">
    <x-sidebar.link collapsible title="{{ $title }}" @click="open = !open" isActive="{{ $active }}">
        @if ($icon ?? false)
        <x-slot name="icon">
            {{ $icon }}
        </x-slot>
        @endif
    </x-sidebar.link>

    <div class="px-4" x-show="open && (isSidebarOpen || isSidebarHovered)" x-collapse>
        <ul class="text-sm font-medium">
            {{ $slot }}
        </ul>
    </div>
</div>