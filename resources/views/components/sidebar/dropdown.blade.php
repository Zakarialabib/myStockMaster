@props(['active' => false, 'title' => ''])

<div class="relative" x-data="{ open: @json($active) }">
    <x-sidebar.link collapsible title="{{ $title }}" @click="open = !open" isActive="{{ $active }}">
        @if ($icon ?? false)
        <x-slot name="icon">
            {{ $icon }}
        </x-slot>
        @endif
    </x-sidebar.link>

    <div x-show="open && (isSidebarOpen || isSidebarHovered)" x-collapse>
        <ul
            class="relative px-0 pt-2 pb-0 ml-5 before:w-0 before:block before:absolute before:inset-y-0 before:left-0 before:border-l-2 before:border-l-zinc-200 dark:before:border-l-zinc-600">

            {{ $slot }}
        </ul>
    </div>
</div>