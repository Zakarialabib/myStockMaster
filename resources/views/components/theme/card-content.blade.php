<!-- resources/views/components/card-content.blade.php -->
@props(['image', 'title', 'buttonText', 'buttonLink', 'bgColor', 'textColor', 'size', 'rounded'])

<div {{ $attributes->merge(['class' => 'bg-' . $bgColor . ' text-' . $textColor . ' rounded-' . $rounded . ' shadow-lg']) }}>
    
    @if($image)
    <img src="{{ $image }}" alt="{{ $title }}" class="h-48 w-full object-cover rounded-t-{{ $rounded }}">
    @endif

    <div class="px-6 py-4">
        <h2 class="text-xl font-bold mb-2">{{ $title }}</h2>
        <p class="text-base">{{ $slot }}</p>
        <div class="mt-4">
            <a href="{{ $buttonLink }}" class="inline-block px-4 py-2 bg-white text-{{ $textColor }} font-semibold rounded-{{ $rounded }} hover:bg-gray-100 transition duration-300 ease-in-out {{ $size }}">{{ $buttonText }}</a>
        </div>
    </div>

</div>

{{-- Example usage --}}
{{-- 
<x-card-content 
    image="https://example.com/image.jpg" 
    title="Example Card" 
    buttonText="Read more" 
    buttonLink="https://example.com" 
    bgColor="gray-100" 
    textColor="gray-800" 
    size="text-sm" 
    rounded="lg"
>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet.
</x-card-content>
--}}
