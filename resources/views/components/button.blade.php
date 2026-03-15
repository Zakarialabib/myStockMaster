@if ($type == 'submit' || $type == 'button')
    <button {{ $attributes->merge(['type' => $type, 'class' => $getClasses]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'ml-2' }}"></i>
        @endif
    </button>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $getClasses]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $slot->isEmpty() ? '' : 'ml-2' }}"></i>
        @endif
    </a>
@endif