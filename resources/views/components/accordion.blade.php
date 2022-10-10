@props(['title', 'id'])

<div x-data="{ open: false }">
    <div class="flex flex-row justify-between items-center">
        <div class="flex flex-row items-center bg-indigo-200 text-indigo-700">
            <button @click="open = !open" class="flex flex-row items-center">
                <i class="bi bi-chevron-down" x-show="!open"></i>
                <i class="bi bi-chevron-up" x-show="open"></i>
            </button>
            <h3 class="text-lg text-center px-4 py-3 font-semibold ml-2">{{ $title }}</h3>
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