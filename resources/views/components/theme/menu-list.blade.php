@props(['placement'])

@foreach (Helpers::getMenusByPlacement($placement) as $menu)
    <li>
        <a href="{{ url($menu->url) }}"
            class="text-zinc-800 cursor-pointer text-[0.94rem] leading-5 font-semibold px-6 uppercase z-[1] {{ request()->routeIs($menu->url) ? 'text-green-400 underline' : '' }} hover:text-green-400 gap-x-2 leading-10 after:absolute after:bottom-[10px] after:left-0 after:bg-white after:transition-transform after:w-full after:origin-left after:scale-x-100 hover:underline focus:underline uppercase">
            {{ $menu->name }}
        </a>

        @if ($menu->children->isNotEmpty())
            <button x-on:click="isSubmenuOpen{{ $menu->id }} = !isSubmenuOpen{{ $menu->id }}"
                @mouseenter="isSubmenuOpen{{ $menu->id }} = true"
                @click.away="isSubmenuOpen{{ $menu->id }} = false">
                <small class="inline-block align-middle text-gray-100">&#9660;</small>
            </button>

            <div x-show="isSubmenuOpen{{ $menu->id }}"
                x-transition:enter="transition ease-out duration-300 transform origin-top"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200 opacity-0 transform origin-top"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="-translate-y-4 scale-95"
                class="absolute z-10 top-0 left-full bg-white w-[25rem] flex flex-col gap-4 py-4 text-center rounded-md shadow-lg"
                @click.away="isSubmenuOpen{{ $menu->id }} = false">
                @foreach ($menu->children as $submenu)
                    <a href="{{ route($submenu->url) }}" class="block px-4 py-2 hover:text-green-400">
                        {{ $submenu->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </li>
@endforeach
