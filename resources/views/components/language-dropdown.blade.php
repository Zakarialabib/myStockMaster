<div>
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button type="button" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors font-bold flex items-center focus:outline-none">
                <i class="fas fa-globe mr-2 text-[18px]"></i>
                <span class="text-sm uppercase">{{ strtoupper(app()->getLocale()) }}</span>
            </button>
        </x-slot>
        <x-slot name="content">
            @foreach (\App\Models\Language::all() as $language)
                <x-dropdown-link href="{{ route('changelanguage', $language->code) }}" class="font-medium text-sm text-gray-700 dark:text-gray-300">
                    {{ $language->name }}
                </x-dropdown-link>
            @endforeach
        </x-slot>
    </x-dropdown>
</div>
