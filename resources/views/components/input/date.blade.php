<div
    x-data="{ value: @entangle($attributes->wire('model')), picker: undefined }"
    x-init="new Pikaday({ field: $refs.input, format: 'MM/DD/YYYY', onOpen() { this.setDate($refs.input.value) } })"
    x-on:change="value = $event.target.value"
    class="flex rounded-md shadow-sm"
>
    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-zinc-300 bg-zinc-50 text-zinc-500 sm:text-sm">
        <svg class="h-5 w-5 text-zinc-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M6 2C5.44772 2 5 2.44772 5 3V4H4C2.89543 4 2 4.89543 2 6V16C2 17.1046 2.89543 18 4 18H16C17.1046 18 18 17.1046 18 16V6C18 4.89543 17.1046 4 16 4H15V3C15 2.44772 14.5523 2 14 2C13.4477 2 13 2.44772 13 3V4H7V3C7 2.44772 6.55228 2 6 2ZM6 7C5.44772 7 5 7.44772 5 8C5 8.55228 5.44772 9 6 9H14C14.5523 9 15 8.55228 15 8C15 7.44772 14.5523 7 14 7H6Z"/>
        </svg>
    </span>

    <input
        {{ $attributes->whereDoesntStartWith('wire:model') }}
        x-ref="input"
        x-bind:value="value"
        class="rounded-none rounded-r-md flex-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
    />
</div>
