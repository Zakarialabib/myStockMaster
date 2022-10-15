@props(['color' => 'transparent'])

<x-button.link color="{{ $color }}" href="{{ url()->previous() }}" title="Go back" {{ $attributes }}>
    <x-heroicon-s-arrow-left class="w-4" />
    <span>Back</span>
</x-button.link>
