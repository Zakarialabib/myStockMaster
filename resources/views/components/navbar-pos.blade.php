<section class="py-5 px-6 bg-white shadow">
    <nav class="flex items-center justify-between flex-shrink-0 px-3">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="text-xl font-semibold">
            <img class="w-14 h-auto" src="{{ asset('images/logo.png') }}" alt="Site Logo">
            <span class="sr-only">{{ config('settings.site_title') }}</span>
        </a>
       
        <div class="md:flex">
            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <x-button type="button" secondary>
                        <x-icons.menu class="w-5 h-5" />
                    </x-button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('home')">
                        {{ __('Dashboard') }}
                    </x-dropdown-link>

                    <x-dropdown-link onclick="Livewire.emit('createProduct')">
                        {{ __('Create Product') }}
                    </x-dropdown-link>

                    <x-dropdown-link onclick="Livewire.emit('createCustomer')">
                        {{ __('Create Customer') }}
                    </x-dropdown-link>

                    <x-dropdown-link onclick="Livewire.emit('recentSales')">
                        {{ __('Recent Sales') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        </div>
    </nav>
</section>
