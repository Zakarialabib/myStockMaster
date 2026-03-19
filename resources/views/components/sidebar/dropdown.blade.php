@props(['active' => false, 'title' => ''])

<div class="relative" x-data="{ open: @json($active) }">
    <x-sidebar.link collapsible title="{{ $title }}" @click="open = !open" isActive="{{ $active }}">
        @if ($icon ?? false)
            <x-slot name="icon">
                {{ $icon }}
            </x-slot>
        @endif
        @if ($add ?? false)
            <x-slot name="add">
                {{ $add }}
            </x-slot>
        @endif
    </x-sidebar.link>

    <div class="mt-1 ps-6 border-s border-gray-200 dark:border-gray-700"
        x-show="open && (isSidebarOpen || isSidebarHovered)"
        x-collapse>
        <ul class="pb-1 pt-1 text-sm font-medium space-y-0.5">
            {{ $slot }}
        </ul>
    </div>
</div>
