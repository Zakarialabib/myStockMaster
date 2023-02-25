<section class="py-5 px-6 bg-gray-50 shadow">
    <nav class="flex items-center justify-between flex-shrink-0 px-3">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="text-xl font-semibold">
            <img class="w-14 h-auto" src="{{ asset('images/logo.png') }}" alt="Site Logo">
            <span class="sr-only">{{ config('settings.site_title') }}</span>
        </a>

        <div class="md:flex gap-4">
            <div class="md:flex hidden">
                <x-button-fullscreen />
            </div>

            <x-language-dropdown />

            @can('show_notifications')
                <div class="md:flex hidden flex-wrap items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger" class="inline-flex">
                            <x-button type="button" iconOnly secondary srText="Open notifications">
                                @php
                                    $low_quantity_products = \App\Models\Product::select('id', 'quantity', 'stock_alert', 'code')
                                        ->whereColumn('quantity', '<=', 'stock_alert')
                                        ->take(5)
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
                                <x-dropdown-link href="#">
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
