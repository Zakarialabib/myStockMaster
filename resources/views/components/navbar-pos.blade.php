<section class="py-5 px-6 bg-gray-50 shadow">
    <nav class="flex items-center justify-between shrink-0 px-3">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="text-xl font-semibold">
            <img class="w-14 h-auto" src="{{ asset('images/logo.png') }}" alt="Site Logo">
            <span class="sr-only">{{ config('settings.site_title') }}</span>
        </a>

        <div class="flex gap-4">
            <div class="md:flex hidden">
                <x-button-fullscreen />
            </div>

            <x-language-dropdown />

            @can('show_notifications')
                <div class="md:flex hidden flex-wrap items-center">
                    @livewire('utils.notifications')
                </div>
            @endcan

            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <x-button type="button" secondary>
                        <x-icons.menu class="w-5 h-5" />
                    </x-button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('dashboard')">
                        {{ __('Dashboard') }}
                    </x-dropdown-link>

                    <x-dropdown-link wire:click="dispatchTo('products.create', 'createModal')">
                        {{ __('Create Product') }}
                    </x-dropdown-link>

                    <x-dropdown-link wire:click="dispatchTo('customers.create', 'createModal')">
                        {{ __('Create Customer') }}
                    </x-dropdown-link>

                    <x-dropdown-link wire:click="dispatchTo('sales.recent', 'recentSales')">
                        {{ __('Recent Sales') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        </div>
    </nav>
</section>
