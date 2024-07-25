<div x-data="{ openAccordion: false }" class="w-full py-2">
    <div x-on:click="openAccordion = !openAccordion"
        class="flex justify-between items-center cursor-pointer border-b border-gray-400 py-2">
        <h3 class="font-bold">{{ $title }}</h3>
        <svg class="w-6 h-6" :class="{ 'transform rotate-180': openAccordion }" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>
    <div x-show="openAccordion" class="border-l border-r border-b border-gray-400 py-2" x-cloak>
        <p>{{ $slot }}</p>
    </div>
</div>

{{-- usage  --}}

{{--
<x-theme.accordion title="Accordion Title" >
</x-theme.accordion> 
--}}
