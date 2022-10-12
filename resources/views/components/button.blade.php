@props(['type' => 'button', 'href' => '#', 'primary' => false, 'secondary' => false,'info'=> false, 'alert' => false, 'danger' => false, 'warning' => false])

@php
    $classes = ($primary ? 'bg-indigo-500 hover:bg-indigo-700' : '') .
        ($secondary ? 'bg-gray-500 hover:bg-gray-700' : '') .
        ($info ? 'bg-blue-500 hover:bg-blue-700' : '') .
        ($alert ? 'bg-yellow-500 hover:bg-yellow-700' : '') .
        ($danger ? 'bg-red-500 hover:bg-red-700' : '') .
        ($warning ? 'bg-orange-500 hover:bg-orange-700' : '');
@endphp


@if ($href)
    <a {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ' . $classes]) }} href="{{ $href }}">
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ' . $classes]) }} type="{{ $type }}">
        {{ $slot }}
    </button>
@endif