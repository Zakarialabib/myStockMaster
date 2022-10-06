<div class="flex items-center justify-between flex-shrink-0 px-3">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
        <img class="w-14 h-auto" src="{{ asset('images/logo.png') }}" alt="Site Logo">
        <span class="sr-only">{{ config('settings.site_title') }}</span>
    </a>

    <!-- Toggle button -->
    <x-button type="button" iconOnly srText="Toggle sidebar" variant="secondary"
        x-show="isSidebarOpen || isSidebarHovered" @click="isSidebarOpen = !isSidebarOpen">
        <x-icons.menu-fold-right x-show="!isSidebarOpen" aria-hidden="true" class="hidden w-6 h-6 lg:block" />
        <x-icons.menu-fold-left x-show="isSidebarOpen" aria-hidden="true" class="hidden w-6 h-6 lg:block" />
        {{-- <x-heroicon-o-x aria-hidden="true" class="w-6 h-6 lg:hidden" /> --}}
    </x-button>
</div>
