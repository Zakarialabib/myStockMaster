<nav aria-label="secondary" x-data="{ open: false }"
    class="sticky top-0 z-10 flex items-center justify-between px-4 py-5 sm:px-6 transition-transform duration-500 bg-white dark:bg-dark-eval-1"
    :class="{
        '-translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    <div class="flex items-center gap-3">
        <x-button type="button" iconOnly variant="secondary" srText="Open main menu"
            @click="isSidebarOpen = !isSidebarOpen">
            <x-heroicon-o-menu x-show="!isSidebarOpen" aria-hidden="true" class="w-6 h-6" />
            <x-heroicon-o-x x-show="isSidebarOpen" aria-hidden="true" class="w-6 h-6" />
        </x-button>
        <x-button type="button" class="md:hidden" iconOnly variant="secondary" srText="Toggle dark mode"
            @click="toggleTheme">
            <x-heroicon-o-moon x-show="!isDarkMode" aria-hidden="true" class="w-6 h-6" />
            <x-heroicon-o-sun x-show="isDarkMode" aria-hidden="true" class="w-6 h-6" />
        </x-button>
    </div>

    <div class="flex items-center gap-3">

        @if (auth()->user()->isAdmin())
            <div class="md:flex hidden flex-row flex-wrap items-center lg:ml-auto mr-3">

                {{-- @livewire('admin.cache') --}}
            </div>
        @endif
        <x-button type="button" class="hidden md:inline-flex" iconOnly variant="secondary" srText="Toggle dark mode"
            @click="toggleTheme">
            <x-heroicon-o-moon x-show="!isDarkMode" aria-hidden="true" class="w-6 h-6" />
            <x-heroicon-o-sun x-show="isDarkMode" aria-hidden="true" class="w-6 h-6" />
        </x-button>
        
        @livewire('admin.cache')

        <ul class="flex-col md:flex-row list-none items-center md:flex">
            <a class="inline-flex items-center p-2 transition-colors font-medium select-none disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-dark-eval-2 text-zinc-500 hover:bg-zinc-100 focus:ring-blue-500 dark:text-zinc-400 dark:hover:bg-dark-eval-2 dark:hover:text-zinc-200 rounded-md"
                onclick="openDropdown(event,'user-dropdown')" aria-haspopup="true"
                :aria-expanded="open ? 'true' : 'false'">
                {{ Auth::user()->name }}
                <x-heroicon-o-chevron-down class="flex-shrink-0 w-4 h-4" aria-hidden="true" />
            </a>
            <div data-popper-placement="bottom-start" id="user-dropdown"
                class="bg-white text-zinc-500 focus:ring focus:ring-offset-2 focus:ring-blue-500 dark:text-zinc-400 dark:bg-dark-eval-1 dark:hover:bg-dark-eval-2 transition-colors z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48 hidden"
                style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(617px, 58px);">
                @if (auth()->user()->isAdmin())
                    <a href="{{ url('admin/settings') }}"
                        class="block py-2 px-4 text-sm dark:hover:bg-zinc-600 dark:hover:text-zinc-200 w-full whitespace-nowrap">
                        {{ __('Settings') }}
                    </a>
                @endif
                <a class="block py-2 px-4 text-sm dark:hover:bg-zinc-600 dark:hover:text-zinc-200 w-full whitespace-nowrap"
                    href="{{ url('/logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                </form>
            </div>
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
