@props(['title','id'])

<div x-data="{ open: false }" class="w-full p-3">
    <div class="flex justify-between items-center cursor-pointer border border-indigo-500 text-indigo-500 font-extralight text-center py-2 px-4 " @click="open = !open">
        <div class="text-lg font-bold">
            {{ $title }}
        </div>
        <div>
            <svg class="w-6 h-6" :class="{'transform rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>
    <div x-show="open" class="mt-2 divide-y divide-gray-400">
        {{ $slot }}
    </div>
</div>