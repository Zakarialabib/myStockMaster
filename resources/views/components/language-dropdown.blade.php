<div>
    <x-dropdown>
        <x-slot name="trigger">
            <x-button type="button" secondary>
                <span class="mr-1">{{ strtoupper(app()->getLocale()) }}</span>
            </x-button>
        </x-slot>
        <x-slot name="content">
            @foreach (\App\Models\Language::all() as $language)
                <x-dropdown-link href="{{ route('changelanguage', $language->code) }}">
                    {{ $language->name }}
                </x-dropdown-link>
            @endforeach
        </x-slot>
    </x-dropdown>
</div>
