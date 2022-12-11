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
            <x-button type="button" secondary id="fullScreen" >
                <i class="fa fa-expand w-4 h-4"></i>
            </x-button >
        </div>

        <x-language-dropdown />

        @can('show_notifications')
                <div>
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger" class="inline-flex">
                            <x-button type="button" iconOnly secondary srText="Open notifications">
                                @php
                                    $low_quantity_products = \App\Models\Product::select('id', 'quantity', 'stock_alert', 'code')
                                        ->whereColumn('quantity', '<=', 'stock_alert')
                                        ->get();
                                @endphp
                                <div class="text-xs font-semibold text-white justify-center">
                                    <i class="fas fa-bell" aria-hidden="true"></i>
                                    <span class="pl-2">{{ $low_quantity_products->count() }}</span>
                                </div>
                            </x-button>
                        </x-slot>

                        <x-slot name="content">
                            @forelse($low_quantity_products as $product)
                                <x-dropdown-link href="{{ route('products.show', $product->id) }}">
                                    <i class="fas fa-bell w-5 h-5" aria-hidden="true"></i>
                                    <span class="ml-2">{{ __('Product') }}: "{{ $product->code }}"
                                        {{ __('is low in quantity !') }}</span>
                                </x-dropdown-link>
                            @empty
                                <x-dropdown-link href="#">
                                    <i class="fas fa-bell w-5 h-5" aria-hidden="true"></i>
                                    <span class="ml-2">{{ __('No notifications') }}</span>
                                </x-dropdown-link>
                            @endforelse
                        </x-slot>
                    </x-dropdown>
                </div>
            @endcan

            <x-button primary :href="route('app.pos.index')" >
                {{ __('POS') }}
            </x-button>

        {{-- <x-button type="button" class="hidden md:inline-flex" iconOnly secondary srText="Toggle dark mode"
            @click="toggleTheme">
            <x-icons.moon x-show="!isDarkMode" aria-hidden="true" class="w-5 h-5" />
            <x-icons.sun x-show="isDarkMode" aria-hidden="true" class="w-5 h-5" />
        </x-button> --}}


        <x-button type="button" class="hidden md:inline-flex" iconOnly primary srText="Toggle RTL mode"
            @click="toggleRtl">
            <a x-show="!isRtl" aria-hidden="true" class="font-bold text-md"> LTR </a>
            <a x-show="isRtl" aria-hidden="true" class="font-bold text-md"> RTL </a>
        </x-button>

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

                    <x-dropdown-link >
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


@push('scripts')
<script>

    function toggleFullscreen(elem) {
        elem = elem || document.documentElement;
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    }

    if(('#fullScreen').length > 0) {
       document.getElementById('fullScreen').addEventListener('click', function() {
           toggleFullscreen();
        });
    }
   

// });

</script>
    
@endpush