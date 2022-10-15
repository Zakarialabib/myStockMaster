@props(['type' => 'white', 'is_image' => false])

<span {{ $attributes->merge([]) }}>
    @if ($type === 'white')
        @if ($is_image)
            <x-spinner.image.white />
        @else
            <x-spinner.code.white />
        @endif
    @else
        @if ($is_image)
            <x-spinner.image.black />
        @else
            <x-spinner.code.black />
        @endif
    @endif
</span>
