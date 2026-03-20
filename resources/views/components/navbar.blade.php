<nav aria-label="secondary" x-data="{ open: false }"
    class="sticky top-0 z-20 flex items-center justify-between px-6 py-3 transition-transform duration-500 bg-white/90 dark:bg-gray-900/90 border-b border-gray-200 dark:border-gray-800 h-16"
    :class="{
        '-translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    <div class="flex items-center gap-3">
        <button type="button" class="p-2 rounded-xl text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-all" @click="isSidebarOpen = !isSidebarOpen">
            <span class="sr-only">Open main menu</span>
            <x-icons.menu x-show="!isSidebarOpen" aria-hidden="true" class="w-6 h-6" />
            <x-icons.x x-show="isSidebarOpen" aria-hidden="true" class="w-6 h-6" />
        </button>
    </div>

    <div class="flex items-center gap-4">
        <div class="hidden md:flex items-center gap-2">
            <x-button-fullscreen />
        </div>

        <div class="hidden md:flex items-center gap-2">
            <button type="button" class="p-2 rounded-xl text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-all" @click="toggleTheme()">
                <i x-show="!isDarkMode" class="fas fa-moon text-lg"></i>
                <i x-show="isDarkMode" class="fas fa-sun text-lg"></i>
            </button>
        </div>

        <div class="hidden md:flex items-center">
            <x-language-dropdown />
        </div>

        @can('show_notifications')
            <div class="hidden md:flex items-center">
                <livewire:notifications-bell />
            </div>
        @endcan

        <a href="{{ route('pos.index') }}" wire:navigate class="flex items-center gap-2 bg-gradient-to-br from-primary-600 to-primary-500 text-white font-bold text-sm px-4 py-2 rounded-xl shadow-soft hover:shadow-lg hover:brightness-110 active:scale-[0.98] transition-all">
            <i class="fas fa-cash-register text-sm"></i>
            <span>{{ __('POS') }}</span>
        </a>

        <ul class="flex items-center list-none">
            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <button type="button" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center border-2 border-primary-200 dark:border-primary-800 overflow-hidden shadow-sm">
                            <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <span class="hidden md:block text-sm font-bold text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.index')" wire:navigate class="font-medium text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-user mr-2 text-primary-500"></i> {{ __('Profile') }}
                    </x-dropdown-link>

                    <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>

                    <x-dropdown-link :href="route('settings.index')" wire:navigate class="font-medium text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-cog mr-2 text-primary-500"></i> {{ __('Settings') }}
                    </x-dropdown-link>

                    <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>

                    <x-dropdown-link class="font-medium text-sm text-gray-700 dark:text-gray-300">
                        <livewire:cache-clear />
                    </x-dropdown-link>

                    <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>

                    <livewire:layout.navigation />
                </x-slot>
            </x-dropdown>
        </ul>
    </div>
</nav>
