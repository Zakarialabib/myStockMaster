<div class="flex items-center justify-between shrink-0 px-3 py-4 border-b border-gray-200 dark:border-gray-800">
    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
        <img class="w-10 h-auto" src="{{ asset('images/logo.png') }}" alt="{{ settings()->company_name ?? config('app.name') }}">
        <span class="text-xl font-black tracking-tighter text-primary-600 dark:text-primary-400 whitespace-nowrap overflow-hidden text-ellipsis" x-show="isSidebarOpen || isSidebarHovered">
            {{ settings()->company_name ?? config('app.name') }}
        </span>
    </a>

    <button
        type="button"
        x-on:click="isSidebarOpen = false"
        class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800"
        x-show="isSidebarOpen"
    >
        <i class="fas fa-times w-5 h-5"></i>
    </button>
</div>