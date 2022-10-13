<nav aria-label="secondary" x-data="{ open: false }"
    class="sticky top-0 z-10 flex items-center justify-between pr-4 pl-8 py-4 transition-transform duration-500 bg-white dark:bg-dark-eval-1"
    :class="{
        '-translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    <div class="flex items-center gap-3">
        <x-button type="button" iconOnly secondary srText="Open main menu" @click="isSidebarOpen = !isSidebarOpen">
            <x-icons.menu x-show="!isSidebarOpen" aria-hidden="true" class="w-6 h-6" />
            <x-icons.x x-show="isSidebarOpen" aria-hidden="true" class="w-6 h-6" />
        </x-button>
        <x-button type="button" class="md:hidden" iconOnly secondary srText="Toggle dark mode" @click="toggleTheme">
            <x-icons.moon x-show="!isDarkMode" aria-hidden="true" class="w-6 h-6" />
            <x-icons.sun x-show="isDarkMode" aria-hidden="true" class="w-6 h-6" />
        </x-button>
    </div>

    <div class="flex items-center gap-3">

        <div class="md:flex hidden flex-row flex-wrap items-center lg:ml-auto mr-3">
            @can('show_notifications')
                <div>
                    <a class="inline-flex items-center p-2 disabled:cursor-not-allowed focus:outline-none focus:ring focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-dark-eval-2 bg-white text-gray-500 hover:bg-gray-100 focus:ring-blue-500 dark:text-gray-400 dark:bg-dark-eval-1 dark:hover:bg-dark-eval-2 dark:hover:text-gray-200 rounded-md"
                        onclick="openDropdown(event,'nav-notifications-dropdown')">
                        <i class="fas fa-bell" class="w-6 h-6" aria-hidden="true"></i>
                        @php
                            $low_quantity_products = \App\Models\Product::select('id', 'quantity', 'stock_alert', 'code')
                                ->whereColumn('quantity', '<=', 'stock_alert')
                                ->get();
                            echo $low_quantity_products->count();
                        @endphp
                        <span
                            class="absolute -top-1 right-1 text-xs font-semibold inline-flex rounded-full h-5 min-w-5 text-white bg-indigo-600 leading-5 justify-center">
                            <span class="px-1">{{ $low_quantity_products->count() }}</span>
                        </span>
                    </a>
                    <div id="nav-notifications-dropdown" data-popper-placement="bottom-start"
                        class="bg-white text-gray-500 focus:ring-blue-500 dark:text-gray-400 dark:bg-dark-eval-1 transition-colors z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48 hidden"
                        style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(617px, 58px);">
                        @forelse($low_quantity_products as $product)
                            <a href="{{ route('products.show', $product->id) }}"
                                class="flex flex-wrap py-2 px-4 text-sm dark:hover:bg-gray-600 dark:hover:text-gray-200 w-full whitespace-nowrap">
                                {{ __('Product') }}: "{{ $product->code }}" {{ __('is low in quantity') }}!
                            </a>
                        @empty
                            <a href="#"
                                class="flex flex-wrap py-2 px-4 text-sm dark:hover:bg-gray-600 dark:hover:text-gray-200 w-full whitespace-nowrap">
                                {{ __('No notifications') }}
                            </a>
                        @endforelse
                    </div>
                </div>
            @endcan

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
                    <x-dropdown-link :href="route('')" :href="route('logout')">
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
