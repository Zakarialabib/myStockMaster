<nav aria-label="secondary" x-data="{ open: false }"
    class="sticky top-0 z-10 flex items-center justify-between pr-4 pl-8 py-4 transition-transform duration-500 bg-white dark:bg-dark-eval-1"
    :class="{
        '-translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    <div class="flex items-center gap-3">
        <x-button type="button" iconOnly secondary srText="Open main menu"
            @click="isSidebarOpen = !isSidebarOpen">
            <x-icons.menu x-show="!isSidebarOpen" aria-hidden="true" class="w-6 h-6" />
            <x-icons.x x-show="isSidebarOpen" aria-hidden="true" class="w-6 h-6" />
        </x-button>
        <x-button type="button" class="md:hidden" iconOnly secondary srText="Toggle dark mode"
            @click="toggleTheme">
            <x-icons.moon x-show="!isDarkMode" aria-hidden="true" class="w-6 h-6" />
            <x-icons.sun x-show="isDarkMode" aria-hidden="true" class="w-6 h-6" />
        </x-button>
    </div>

    <div class="flex items-center gap-3">

        <div class="md:flex hidden flex-row flex-wrap items-center lg:ml-auto mr-3">
            <x-button primary :href="route('app.pos.index')">
                <i class="bi bi-cart-plus"></i> {{ __('POS') }}
            </x-button>
            {{-- @livewire('admin.cache') --}}
        </div>

        <x-button type="button" class="hidden md:inline-flex" iconOnly secondary srText="Toggle dark mode"
            @click="toggleTheme">
            <x-icons.moon x-show="!isDarkMode" aria-hidden="true" class="w-6 h-6" />
            <x-icons.sun x-show="isDarkMode" aria-hidden="true" class="w-6 h-6" />
        </x-button>

        {{-- @livewire('admin.cache') --}}

        <ul class="flex-col md:flex-row list-none items-center md:flex">
            <x-dropdown align="right" width="60">
                <x-slot name="trigger">
                    {{ Auth::user()->name }}

                    <button
                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                        <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->profile_photo_url }}"
                            alt="{{ auth()->user()->name }}" />
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('')"
                    :href="route('logout')">
                        {{ __('Settings') }}
                    </x-dropdown-link>

                    {{-- <x-dropdown-link href="{{ route('profile.show') }}">
                        {{ __('Profile') }}
                    </x-dropdown-link> --}}

                    <div class="border-t border-gray-100"></div>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </ul>
    </div>
</nav>

{{-- <!-- Mobile bottom bar -->
<div class="fixed inset-x-0 bottom-0 z-10 flex items-center justify-between px-4 py-4 sm:px-6 transition-transform duration-500 bg-white lg:hidden dark:bg-dark-eval-1"
    :class="{
        'translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    <a href="{{ route('admin.dashboard') }}">
        <x-application-logo aria-hidden="true" class="w-10 h-10" />
        <span class="sr-only">{{ config('settings.site_title') }}</span>
    </a>
</div> --}}
