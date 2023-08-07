<div>
    <div class="my-2 px-4 flex justify-between items-center">
        <h2 class="text-left">{{ $selectedLanguage->name }}</h2>
        <x-button primary type="button" wire:click="updateTranslation">{{ __('update translations') }}</x-button>
    </div>
    <x-table>
        <x-slot name="thead">
            <x-table.th>{{ __('System') }}</x-table.th>
            <x-table.th>{{ __('Translation') }}</x-table.th>
            <x-table.th>{{ __('Action') }}</x-table.th>
        </x-slot>
        <x-table.tbody>

            <x-table.tr>
                <x-table.td>
                    <x-input type="text" wire:model="newKey" placeholder="{{ __('Key') }}" />
                </x-table.td>
                <x-table.td>
                    <x-input type="text" wire:model="newValue" placeholder="{{ __('Translation') }}" />
                </x-table.td>
                <x-table.td>
                    <x-button type="button" primary wire:click="addTranslation">{{ __('Add') }}</x-button>
                </x-table.td>
            </x-table.tr>

            @foreach ($translations as $key => $translation)
                <x-table.tr>
                    <x-table.td class="max-w-xs h-auto overflow-hidden">
                        <p class="truncate">{{ $key }}</p>
                    </x-table.td>
                    <x-table.td>
                        <x-input type="text" wire:model.lazy="translations.{{ $key }}.value" />
                    </x-table.td>
                    <x-table.td>
                        <x-button type="button" danger wire:click="deleteTranslation('{{ $key }}')">
                            {{ __('Delete') }}
                        </x-button>
                    </x-table.td>
                </x-table.tr>
            @endforeach

        </x-table.tbody>
    </x-table>
</div>
