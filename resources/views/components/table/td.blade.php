@props([
    'checkbox' => false,
    'actions' => false
])

<td {{ $attributes->merge([
    'class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100' . 
               ($checkbox ? ' w-12' : '') . 
               ($actions ? ' text-right' : ''),
]) }}>
    @if($checkbox)
        <input type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-gray-700" {{ $attributes->only(['wire:model', 'value']) }}>
    @else
        {{ $slot }}
    @endif
</td>
