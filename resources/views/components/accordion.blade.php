@props(['title', 'id'])

<div x-data="{ open: false }" class="bg-indigo-500 text-white px-4 py-2">
    <div class="flex flex-row justify-between items-center text-center">
        <div class="flex flex-row items-center">
            <button @click="open = !open" class="flex flex-row items-center">
                <i class="bi bi-chevron-down" x-show="!open"></i>
                <i class="bi bi-chevron-up" x-show="open"></i>
            </button>
            <h3 class="text-lg text-center font-semibold ml-2">{{ $title }}</h3>
        </div>
        <div class="flex flex-row items-center">
            <button @click="open = !open" class="flex flex-row items-center">
                <i class="bi bi-chevron-down" x-show="!open"></i>
                <i class="bi bi-chevron-up" x-show="open"></i>
            </button>
        </div>
    </div>
    <div x-show="open" x-cloak>
        {{ $slot }}
    </div>
</div>