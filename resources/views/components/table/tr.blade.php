<tr {{ $attributes->merge([
    'class' => 'border-b border-blue-50 whitespace-nowrap text-sm text-zinc-800',
]) }}>
    {{ $slot }}
</tr>