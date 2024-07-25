<div>

    <div x-data="{ showNotifications: false, openAlert: false }">

        <x-button type="button" iconOnly secondary srText="Open notifications" x-on:click="showNotifications = true">
            <div class="text-xs font-semibold text-white justify-center">
                <i class="fas fa-bell" aria-hidden="true"></i>
                <span class="pl-2">
                    {{ $this->lowQuantity->count() }}
                </span>
            </div>
        </x-button>

        <div>
            <div x-show="showNotifications" x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4" class="fixed inset-0 overflow-hidden z-50"
                x-on:click.away="showNotifications = false" x-close-on-escape="true">

                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute inset-0" aria-hidden="true" x-on:click="showNotifications = false"></div>
                    <div class="absolute inset-y-0 right-0 pl-10 max-w-full flex">
                        <div class="w-screen max-w-sm">
                            <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                                <div class="flex justify-between items-center py-4 px-2">
                                    <div class="float-left leading-5 cursor-pointer" aria-hidden="true"
                                        x-on:click="showNotifications = false">
                                        X
                                    </div>
                                    <h3 class="text-2xl text-center">{{ __('Notifications') }}</h3>
                                    <div class="w-0"></div>
                                </div>
                                <hr>
                                <div>
                                    {{-- @foreach ($user->unreadNotifications as $key => $notification)
                                        <div class="flex items-center px-4 py-2 text-sm font-medium text-gray-700">
                                            <i class="fas fa-bell w-5 h-5 text-gray-500" aria-hidden="true"></i>
                                            <span class="ml-2">
                                                {{ $notification->data['message'] }}
                                            </span>
                                            <button type="button" wire:click="markAsRead('{{ $key }}')"
                                                class="cursor-pointer">
                                                <i class="fa fa-eye w-5 h-5 text-gray-500"></i>
                                            </button>
                                        </div>
                                    @endforeach --}}
                                </div>
                                <hr>

                                <div>
                                    <div class="flex justify-between items-center py-4 px-2">
                                        <div class="float-left leading-5 cursor-pointer">
                                            <button @click="openAlert = !openAlert">
                                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <h3 class="text-2xl text-center">{{ __('Stock Alert') }}</h3>
                                        <div class="w-0"></div>
                                    </div>

                                    <ol x-show="openAlert">
                                        {{-- @forelse($this->lowQuantity as $product)
                                            <li class="flex items-center px-4 py-2 text-sm font-medium text-gray-700">
                                                <i class="fas fa-bell w-5 h-5 text-gray-500" aria-hidden="true"></i>
                                                <span class="ml-2">{{ __('Product') }}: "{{ $product->name }}"
                                                    {{ $product->quantity }}/{{ $product->stock_alert }}
                                                    {{ __('product exceed alert quantity !') }}</span>
                                            </li>
                                        @empty
                                            <li class="flex items-center px-4 py-2 text-sm font-medium text-gray-700">
                                                <i class="fas fa-bell w-5 h-5 text-gray-500" aria-hidden="true"></i>
                                                <span class="ml-2">{{ __('No notifications') }}</span>
                                            </li>
                                        @endforelse --}}
                                        <hr>
                                        <div class="flex justify-center my-4">
                                            <x-button type="button" primary wire:click="loadMore">
                                                +
                                            </x-button>
                                        </div>
                                    </ol>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
