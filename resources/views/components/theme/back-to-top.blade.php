@props([
    
])

{{-- 'back-to-top', 'button', 'icon', 'invert', 'plain', 'fixed', 'bottom', 'z-1', 'is-outline', 'round'  --}}

@php
    // 
@endphp

<a href="#top"  id="top-link" aria-label="Go to top" {{ $attributes->merge(['class' => " fixed bottom-4 right-4 md:right-8 z-50 {{$classes}} text-sm"]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M17.293 9.293a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414 0l-6-6A1 1 0 1 1 5.707 7.88L10 12.172V3a1 1 0 1 1 2 0v9.172l4.293-4.293a1 1 0 0 1 1.414 0z" clip-rule="evenodd" />
    </svg>
</a>
