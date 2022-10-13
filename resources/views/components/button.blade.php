@props(['type' => null, 'href' => '#', 'primary' => false, 'secondary' => false,'info'=> false, 'alert' => false, 'success' => false,'danger' => false, 'warning' => false])

@php
    $classes = ($primary ? 'bg-indigo-500 hover:bg-indigo-700 hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300' : '') .
        ($secondary ? 'bg-gray-500 hover:bg-gray-700 hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300' : '') .
        ($info ? 'bg-blue-500 hover:bg-blue-700 hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300' : '') .
        ($success ? 'bg-green-500 hover:bg-green-700 hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300' : '') .
        ($alert ? 'bg-yellow-500 hover:bg-yellow-700 hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300' : '') .
        ($danger ? 'bg-red-500 hover:bg-red-700 hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300' : '') .
        ($warning ? 'bg-orange-500 hover:bg-orange-700 hover:bg-orange-700 active:bg-orange-900 focus:outline-none focus:border-orange-900 focus:ring ring-orange-300' : '');
@endphp

@if ($type == 'submit' || $type == 'button')
    <button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest disabled:opacity-25 transition ease-in-out duration-150 ' . $classes]) }}>
        {{ $slot }}
    </button>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest disabled:opacity-25 transition ease-in-out duration-150 ' . $classes]) }}>
        {{ $slot }}
    </a>
@endif
