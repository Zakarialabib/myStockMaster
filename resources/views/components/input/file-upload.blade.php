<div class="flex items-center">
    {{ $slot }}

    <div x-data="{ focused: false }">
        <span class="ml-5 rounded-md shadow-sm">
            <input @focus="focused = true" @blur="focused = false" class="sr-only" type="file" {{ $attributes }}>
            <label for="{{ $attributes['id'] }}" :class="{ 'outline-none border-blue-300 shadow-outline-blue': focused }" class="cursor-pointer py-2 px-3 border border-zinc-300 rounded-md text-sm leading-4 font-medium text-zinc-700 hover:text-zinc-500 active:bg-zinc-50 active:text-zinc-800 transition duration-150 ease-in-out">
                Select File
            </label>
        </span>
    </div>
</div>
