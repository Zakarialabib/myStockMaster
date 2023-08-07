@props(['wide', 'bgColor', 'padded','slot'])


<!-- resources/views/components/layout.blade.php -->
<div {{ $attributes->merge(['class' => ' w-full container mx-auto ' ]) }}>
    @if ($wide)
        <div {{ $attributes->merge(['class' => $wide ? 'w-full' : 'max-w-screen-xl mx-auto']) }}>
            {{ $slot }}
        </div>
    @endif
</div>

{{-- {{ $wide ? 'max-w-full' : 'max-w-screen-lg' }}  --}}

{{-- <x-layout :wide="true"> </x-layout> --}}

{{-- <x-layouts :wide="true" bgColor="bg-gray-100" padded> </x-layouts> --}}

{{-- {{ $bgColor }} {{ $padded ? 'px-4 py-8' : '' } --}}