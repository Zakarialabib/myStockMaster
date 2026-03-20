@php
    $href = $attributes->get('href');
    $target = $attributes->get('target');
    $hasClickHandler = $attributes->has('wire:click') || $attributes->has('@click');
    $isExternal = is_string($href) && (str_starts_with($href, 'http://') || str_starts_with($href, 'https://') || str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:') || str_starts_with($href, 'javascript:'));
    $shouldNavigate = is_string($href) && $href !== '' && $href !== '#' && $target !== '_blank' && ! $hasClickHandler && ! $isExternal;
@endphp

<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2.5 mx-2 my-1 text-start text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none focus:bg-primary-50 dark:focus:bg-primary-900/20 rounded-xl transition-all duration-200 w-[calc(100%-1rem)] cursor-pointer']) }} @if ($shouldNavigate) wire:navigate @endif>{{ $slot }}</a>
