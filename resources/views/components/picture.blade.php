<picture>
@if (isset($sizes))
@foreach($sizes as $minWidth => $size)
    <source srcset="{{ image($name, $size['width'], $size['height']) }}" media="(min-width: {{ $minWidth }}px)">
@endforeach
@endif
    <source srcset="{{ image($name, $defaultSizes['width'], $defaultSizes['height']) }}">
    <img
        srcset="{{ image($name, $defaultSizes['width'], $defaultSizes['height']) }}"
        src="{{ $name }}"
        alt="{{ $alt }}"
        {!! $attributes !!}
    >
</picture>
