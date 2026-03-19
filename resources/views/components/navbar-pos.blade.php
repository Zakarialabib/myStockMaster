<section class="bg-white/90 dark:bg-gray-900/90 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-50">
    <nav class="flex items-center justify-between px-8 h-16">
        <div class="flex items-center gap-12">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <img class="w-10 h-auto" src="{{ asset('images/logo.png') }}" alt="Site Logo">
                <div class="hidden sm:block">
                    <span class="text-xl font-black tracking-tighter text-primary-600 dark:text-primary-400">{{ config('settings.site_title') }}</span>
                    <span class="ml-2 text-sm text-gray-400 dark:text-gray-500 font-medium">|</span>
                    <span class="ml-2 text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Point of Sale') }}</span>
                </div>
            </a>
        </div>

        <div class="flex items-center gap-5">
            <div class="md:flex hidden">
                <x-button-fullscreen />
            </div>

            <x-language-dropdown />

            @can('show_notifications')
                <div class="md:flex hidden flex-wrap items-center">
                    @livewire('notifications-bell')
                </div>
            @endcan

            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <button type="button" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                        <span class="sr-only">Open POS menu</span>
                        <x-icons.menu class="w-6 h-6" />
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('dashboard')" class="font-medium text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-home mr-2 text-gray-400"></i> {{ __('Dashboard') }}
                    </x-dropdown-link>
                    
                    <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>

                    <x-dropdown-link wire:click="dispatchTo('products.create', 'createModal')" class="font-medium text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-box-open mr-2 text-gray-400"></i> {{ __('Create Product') }}
                    </x-dropdown-link>

                    <x-dropdown-link wire:click="dispatchTo('customers.create', 'createModal')" class="font-medium text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-user-plus mr-2 text-gray-400"></i> {{ __('Create Customer') }}
                    </x-dropdown-link>
                    
                    <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>

                    <x-dropdown-link wire:click="dispatchTo('sales.recent', 'recentSales')" class="font-medium text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-history mr-2 text-gray-400"></i> {{ __('Recent Sales') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        </div>
    </nav>
</section>
