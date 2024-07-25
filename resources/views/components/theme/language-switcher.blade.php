<div class="self-center relative" x-data="{ langSwitcher: false }">
    <a class="inline-flex items-center transition-colors font-medium select-none disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring focus:ring-offset-2 focus:ring-offset-white bg-white text-gray-500 hover:bg-gray-100 focus:ring-blue-500 rounded-md"
        @click="langSwitcher = !langSwitcher">
        <img src="{{ Helpers::flagImageUrl(\Illuminate\Support\Facades\App::getLocale()) }}" class="px-2">
        @if (count($languages) > 1)
            <i class="fas fa-caret-down text-black"></i>
        @endif
    </a>
    <div x-show="langSwitcher" @click.away="langSwitcher = false"
        class="flex flex-col bg-white text-gray-500 focus:ring focus:ring-offset-2 focus:ring-blue-500 transition-colors float-left py-2 min-w-48 absolute right-0 z-50 mt-2  rounded-md shadow-lg overflow-y-auto"
        x-transition:enter="transition ease-out duration-100 transform" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75 transform"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>
        @foreach ($languages as $code => $name)
            @if (\Illuminate\Support\Facades\App::getLocale() !== $code)
                <a class="py-2 px-4 text-sm w-full whitespace-nowrap"
                href="{{ route('changelanguage', $code) }}" title="{{ $name }}">
                    {{ $name }}
                </a>
            @endif
        @endforeach
    </div>
</div>