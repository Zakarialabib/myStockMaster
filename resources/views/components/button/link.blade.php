@props(['color' => 'brand', 'style_override' => null])

<?php
    $style = $color;

    if(isset($style_override)) {
        $style = $style_override;
    }

    $ring = $color !== 'transparent' ? "has__ring" : "no__ring";
?>

<a {{ $attributes->merge(['type' => 'button', 'class' => "btn btn__text {$style} {$ring}"]) }}>
    {{ $slot }}
</a>
