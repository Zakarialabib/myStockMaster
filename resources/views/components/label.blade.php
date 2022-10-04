@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-zinc-700']) }}>
    {{ $value ?? $slot }}
</label>
