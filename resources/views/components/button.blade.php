@if ($type == 'submit' || $type == 'button')
    <button {{ $attributes->merge(['type' => $type, 'class' => $getClasses]) }}>
        @if ($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'ml-2' }}"></i>
        @endif
    </button>
@else
    @php
        $target = $attributes->get('target');
        $hasClickHandler = $attributes->has('wire:click') || $attributes->has('@click');
        $isExternal =
            str_starts_with($href, 'http://') ||
            str_starts_with($href, 'https://') ||
            str_starts_with($href, 'mailto:') ||
            str_starts_with($href, 'tel:') ||
            str_starts_with($href, 'javascript:');
        $shouldNavigate = $href !== '' && $href !== '#' && $target !== '_blank' && !$hasClickHandler && !$isExternal;
    @endphp
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $getClasses]) }}
        @if ($shouldNavigate) wire:navigate @endif>
        @if ($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'ml-2' }}"></i>
        @endif
    </a>
@endif
