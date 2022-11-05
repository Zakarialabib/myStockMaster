<div>
    <x-dropdown>
        <x-slot name="trigger">
            <button type="button"
                class="text-base font-semibold text-gray-500 hover:text-sky-800 dark:text-slate-400 dark:hover:text-sky-400 flex flex-wrap">
                <span class="mr-1">{{ strtoupper(app()->getLocale()) }}</span>
            </button>
        </x-slot>
        <x-slot name="content">
            {{-- @foreach ($languages as $locale)
                <x-dropdown-link href="{{ route('admin.changelanguage', $locale->code) }}">
                    {{ $locale->name }}
                </x-dropdown-link>
            @endforeach --}}
        </x-slot>
    </x-dropdown>
</div>
