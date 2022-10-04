<tr {{ $attributes->merge([
    'class' => 'whitespace-nowrap text-sm text-zinc-800',
]) }}>
    {{ $slot }}
</tr>