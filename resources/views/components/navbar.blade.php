<nav aria-label="secondary" x-data="{ open: false }"
    class="sticky top-0 z-10 flex items-center justify-between px-4 py-4 transition-transform duration-500 bg-white dark:bg-dark-eval-1"
    :class="{
        '-translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    <div class="flex items-center gap-3">
        <x-button type="button" iconOnly secondary srText="Open main menu" @click="isSidebarOpen = !isSidebarOpen">
            <x-icons.menu x-show="!isSidebarOpen" aria-hidden="true" class="w-5 h-5" />
            <x-icons.x x-show="isSidebarOpen" aria-hidden="true" class="w-5 h-5" />
        </x-button>
    </div>

    <div class="flex items-center gap-3">
        <div class="md:flex hidden flex-wrap items-center">
            <x-button-fullscreen />
        </div>

        <x-language-dropdown />

        @can('show_notifications')
            <div class="md:flex hidden flex-wrap items-center">
                @livewire('notifications')
            </div>
        @endcan

        <x-button primary :href="route('app.pos.index')">
            {{ __('POS') }}
        </x-button>

        {{--
             <x-button type="button" class="hidden md:inline-flex" iconOnly secondary srText="Toggle dark mode"
            @click="toggleTheme">
            <x-icons.moon x-show="!isDarkMode" aria-hidden="true" class="w-5 h-5" />
            <x-icons.sun x-show="isDarkMode" aria-hidden="true" class="w-5 h-5" />
             </x-button>
              --}}


        {{--
                 <x-button type="button" class="hidden md:inline-flex" iconOnly primary srText="Toggle RTL mode"
                @click="toggleRtl">
                <a x-show="!isRtl" aria-hidden="true" class="font-bold text-md"> LTR </a>
                <a x-show="isRtl" aria-hidden="true" class="font-bold text-md"> RTL </a>
            </x-button> 
            --}}

        <ul class="flex-col md:flex-row list-none items-center md:flex">
            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <x-button type="button" primary>
                        {{ Auth::user()->name }}
                    </x-button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.index')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <div class="border-t border-gray-100"></div>

                    <x-dropdown-link :href="route('settings.index')">
                        {{ __('Settings') }}
                    </x-dropdown-link>

                    <div class="border-t border-gray-100"></div>

                    <x-dropdown-link>
                        @livewire('cache')
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