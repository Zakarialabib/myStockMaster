<!-- resources/views/components/card.blade.php -->
@props(['image','title'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-lg']) }}>
    
    @if($image)
    <img src="{{ $image }}" alt="{{ $title }}" class="h-48 w-full object-cover rounded-t-lg">
    @endif

    <div class="px-6 py-4">
        <h2 class="text-xl font-bold mb-2">{{ $title }}</h2>
        <p class="text-gray-700 text-base">{{ $slot }}</p>
    </div>

</div>

{{-- 
<x-card image="https://example.com/image.jpg" title="Example Card">
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet.
</x-card>
--}}