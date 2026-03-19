<div class="flex items-center justify-between shrink-0 px-3 py-2">
    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
        <img class="w-10 h-auto md:w-12 lg:w-14" src="{{ asset('images/logo.png') }}" alt="{{ config('settings.site_title') }}">
        <span class="hidden md:inline text-lg font-semibold text-gray-800 dark:text-gray-100">
            {{ config('settings.site_title') }}
        </span>
    </a>

    <button
        type="button"
        x-on:click="isSidebarOpen = false"
        class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800"
        x-show="isSidebarOpen"
    >
        <i class="fas fa-times w-5 h-5"></i>
    </button>
</div>