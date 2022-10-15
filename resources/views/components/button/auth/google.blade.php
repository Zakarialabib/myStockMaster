<a {{ $attributes->merge(['href' => '#', 'title' => 'Continue with Google']) }}>
    <x-button class="w-full group" color="light">
        <img src="{{ asset('images/google.svg') }}" alt="Google logo">
        <span>Continue with Google</span>
    </x-button>
</a>
