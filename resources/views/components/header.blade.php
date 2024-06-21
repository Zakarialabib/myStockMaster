<div x-data="{ mobileMenuOpen: false, open: false }" x-on:click.outside="open = false" class="relative bg-white drop-shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center border-gray-100 md:space-x-10">
            <div class="flex justify-start">
                <a href="{{ route('front.home') }}" title="{{ $website_title }}">
                    @if (file_exists(asset('images/logo.svg')))
                        <x-picture name="images/logo.svg" :default-sizes="['width' => 376, 'height' => 176]" alt="{{ $website_title }}"
                            class="h-8 w-auto sm:h-10 inline" width="376px" height="176px"
                            style="min-height: 70px;padding: 10px 0;" />
                    @else
                        <img class="h-8 w-auto sm:h-10 inline" style="min-height: 70px;padding: 10px 0;"
                            src="{{ asset('images/logo.svg') }}" alt="{{ $website_title }}">
                    @endif
                    @if (file_exists(asset('images/logo.svg')))
                        <x-picture name="images/logo.svg" :default-sizes="['width' => 376, 'height' => 176]" alt="{{ $website_title }}"
                            class="h-8 w-auto sm:h-10 hidden" width="376px" height="176px"
                            style="min-height: 70px;padding: 10px 0;" />
                    @else
                        <img class="h-8 w-auto sm:h-10 hidden" style="min-height: 70px;padding: 10px 0;"
                            src="{{ asset('images/logo.svg') }}" alt="{{ $website_title }}">
                    @endif
                </a>
            </div>
            <div class="flex items-center justify-center space-x-4 md:order-last">
                <x-dropdown>
                    <x-slot name="trigger">
                        <button type="button"
                            class="text-base font-semibold text-gray-500 hover:text-sky-800 flex flex-wrap">
                            @if (count($langs) > 1)
                            <i class="bi bi-globe2 h-5 w-5 cursor-pointer pr-2" aria-hidden="true"></i>
                            @endif
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        @if (count($langs) > 1)
                            <ul>
                                @foreach ($langs as $lang)
                                    @if (\Illuminate\Support\Facades\App::getLocale() !== $lang->code)
                                        <li class="flex">
                                            <a class="py-2 px-4 text-sm w-full whitespace-nowrap"
                                                href="{{ route('changelanguage', $lang->code) }}"
                                                title="{{ $lang->name }}">
                                                {{ $lang->name }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </x-slot>

                </x-dropdown>


                <span class="text-sm text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                <label for="toggle"
                    class="flex items-center h-5 p-1 duration-300 ease-in-out bg-gray-300 rounded-full cursor-pointer w-9">
                    <div
                        class="w-4 h-4 duration-300 ease-in-out transform bg-white rounded-full shadow-md toggle-dot">
                    </div>
                </label>
                <span class="text-sm text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                </span>
                <input id="toggle" type="checkbox" class="hidden" :value="darkMode"
                    @change="darkMode = !darkMode" />
            </div>

            <div class="-mr-2 -my-2 md:hidden">
                <button @click="mobileMenuOpen = true" type="button"
                    class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-500 hover:text-gray-500 hover:bg-gray-100"
                    aria-expanded="false">
                    <span class="sr-only">{{ __('Open menu') }}</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <x-navigation.desktop />
        </div>
    </div>

    <div style="display: none" x-show="mobileMenuOpen" x-transition:enter="duration-200 ease-out"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="duration-100 ease-in" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">
        <div
            class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50">
            <div class="pt-5 pb-6 px-5">
                <div class="flex items-center justify-between">
                    <div>
                        @if (file_exists(asset('images/logo.svg')))
                            <x-picture name="images/logo.svg" :default-sizes="['width' => 64, 'height' => 32]" alt="{{ $website_title }}"
                                class="h-8 w-auto inline" width="64px" height="32px" loading="lazy" />
                        @else
                            <img class="h-8 w-auto inline" src="{{ asset('images/logo.svg') }}"
                                alt="{{ $website_title }}">
                        @endif
                        @if (file_exists(asset('images/logo.svg')))
                            <x-picture name="images/logo.svg" :default-sizes="['width' => 64, 'height' => 32]" alt="{{ $website_title }}"
                                class="h-8 w-auto hidden" width="64px" height="32px"
                                loading="lazy" />
                        @else
                            <img class="h-8 w-auto hidden" src="{{ asset('images/logo.svg') }}"
                                alt="{{ $website_title }}">
                        @endif
                    </div>
                    <div class="-mr-2">
                        <button @click="mobileMenuOpen = false" type="button"
                            class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-500 hover:text-gray-500 hover:bg-gray-100">
                            <span class="sr-only">{{ __('Close menu') }}</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="mt-6">
                    <x-navigation.mobile />
                </div>
            </div>
        </div>
    </div>
</div>
