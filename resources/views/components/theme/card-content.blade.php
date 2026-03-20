<!-- resources/views/components/card-content.blade.php -->
@props(['image', 'title', 'buttonText', 'buttonLink', 'bgColor' => 'white dark:bg-gray-900', 'textColor' => 'gray-900 dark:text-gray-100', 'size' => 'text-sm', 'rounded' => '2xl'])

<div {{ $attributes->merge(['class' => 'bg-' . $bgColor . ' text-' . $textColor . ' rounded-' . $rounded . ' shadow-soft border border-gray-200 dark:border-gray-800']) }}>
    
    @if($image)
    <img src="{{ $image }}" alt="{{ $title }}" class="h-48 w-full object-cover rounded-t-{{ $rounded }}">
    @endif

    <div class="px-8 py-6">
        <h2 class="text-xl font-bold tracking-tight mb-2">{{ $title }}</h2>
        <p class="text-base text-gray-600 dark:text-gray-400">{{ $slot }}</p>
        <div class="mt-6">
            <a href="{{ $buttonLink }}" class="inline-block px-4 py-2 bg-gradient-to-br from-primary-600 to-primary-500 text-white font-bold rounded-xl shadow-lg hover:brightness-110 active:scale-[0.98] transition-all {{ $size }}">{{ $buttonText }}</a>
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
