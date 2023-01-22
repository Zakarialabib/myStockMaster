{{-- Optimize this code --}}

@props(['align' => 'right', 'width' => null, 'contentClasses' => 'py-1 bg-white'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = ' origin-top-left left-0';
        break;
    case 'top':
        $alignmentClasses = ' origin-top';
        break;
    case 'right':
    default:
        $alignmentClasses = ' origin-top-right right-0';
        break;
}
switch ($width) {
    case '48':
        $widthClasses = ' w-48';
        break;
    case '56':
        $widthClasses = ' w-56';
        break;
    case '64':
        $widthClasses = ' w-64';
        break;
    case '72':
        $widthClasses = ' w-72';
        break;
    case '80':
        $widthClasses = ' w-80';
        break;
    case '96':
        $widthClasses = ' w-96';
        break;
    case 'auto':
    default:
        $widthClasses = '';
        break;
}
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute right-0 z-50 mt-2 {{ $widthClasses }} rounded-md shadow-lg {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
