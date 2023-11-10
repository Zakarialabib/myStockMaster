@props(['icon', 'title', 'color' => 'green', 'href' => '#', 'description', 'content' => ''])

<div x-data="{ open: false }" @click.away="open = false" class="relative">
    <a class="p-2 mx-4 flex gap-4 items-center group hover:bg-{{ $color }}-500 cursor-pointer hover:shadow-xl border bg-white hover:border-{{ $color }}-500 duration-200 ease-in-out transition-all flex items-center rounded-md overflow-hidden sm:rounded-md transform hover:-translate-y-1 hover:scale-110 shadow"
        href="{{ $href }}" @click.prevent="open = !open">
        <div class="image bg-{{ $color }}-500 group-hover:bg-transparent sm:p-4 p-2 ltr:mr-3 rtl:ml-3 rounded-lg">
            <i class="{{ $icon }} group-hover:text-gray-50 font-xl"></i>
        </div>
        <h3 class="text-lg antialiased group-hover:text-gray-50 font-bold">
            {{ $slot }}
        </h3>
        <div x-show="!open" class="ml-auto text-white cursor-pointer">
            <i class="fa fa-info-circle"></i>
        </div>
    </a>

    <div x-show="open" class="w-full absolute z-10 p-4 bg-white shadow-md rounded-md mt-2">
        {{ $content }}
    </div>
</div>
