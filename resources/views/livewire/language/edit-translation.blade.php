<div>
    {{-- show a table of words --}}
    <x-table>
        <x-slot name="thead">
            <x-table.th>#</x-table.th>
            <x-table.th>key</x-table.th>
            <x-table.th>value</x-table.th>
        </x-slot>
        <x-table.tbody>
            @foreach ($json as $key => $value)
                <x-table.td>
                    <x-button type="button" wire:click="editWord('{{ $key }}')">
                        {{ __('edit') }}
                    </x-button>
                </x-table.td>
                <x-table.td>
                    {{ $key }}
                </x-table.td>
                <x-table.td>
                    {{ $value }}
                </x-table.td>
            @endforeach
            </x-table-tbody>
    </x-table>

    {{--  edit translation --}}
    <x-modal wire:model="editWord">
        <x-slot name="title">
            {{ 'Edit translation' }}
        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="updateTranslation">
                <div class="form-group">
                    <label for="key">{{ __('Key') }}</label>
                    <input type="text" class="form-control" wire:model="key" id="key" placeholder="Enter key">
                    @error('key')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="value">{{ __('Value') }}</label>
                    <input type="text" class="form-control" wire:model="value" id="value"
                        placeholder="Enter value">
                    @error('value')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </form>
        </x-slot>
    </x-modal>
</div>
