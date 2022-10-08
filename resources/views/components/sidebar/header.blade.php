<div class="flex items-center justify-between flex-shrink-0 px-3">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="text-xl font-semibold">
        <img class="w-14 h-auto" src="{{ asset('images/logo.png') }}" alt="Site Logo">
        <span class="sr-only">{{ config('settings.site_title') }}</span>
    </a>

    <!-- Toggle button -->
    <x-button type="button" iconOnly srText="Toggle sidebar" variant="secondary"
        x-show="isSidebarOpen || isSidebarHovered" @click="isSidebarOpen = !isSidebarOpen">
        <i class="fas fa-chevron-right hidden w-6 h-6 lg:block" x-show="!isSidebarOpen" aria-hidden="true"></i>
        <i class="fas fa-chevron-left hidden w-6 h-6 lg:block" x-show="isSidebarOpen" aria-hidden="true"></i>
    </x-button>
</div>
