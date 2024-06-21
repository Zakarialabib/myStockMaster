<div>
    <div class="space-y-4 flex flex-col items-center justify-center my-4">
        @foreach ($options as $index => $option)
            <div class="flex flex-row w-full space-x-2">
                <select wire:model="options.{{ $index }}.type"
                    class="block w-full bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-500">
                    <option value="">{{ __('Choose an option') }}</option>
                    <option value="color" {{ $option['type'] == 'color' ? 'selected' : '' }}>
                        {{ __('Color') }}
                    </option>
                    <option value="movementType" {{ $option['type'] == 'movementType' ? 'selected' : '' }}>
                        {{ __('Movement type') }}</option>
                    <option value="waterproof" {{ $option['type'] == 'waterproof' ? 'selected' : '' }}>
                        {{ __('Water Resiste') }}</option>
                    <option value="displayType" {{ $option['type'] == 'displayType' ? 'selected' : '' }}>
                        {{ __('Display type') }}</option>
                    <option value="bandColor" {{ $option['type'] == 'bandColor' ? 'selected' : '' }}>
                        {{ __('Band color') }}</option>
                    <option value="materials" {{ $option['type'] == 'materials' ? 'selected' : '' }}>
                        {{ __('Materials') }}</option>
                    <option value="size" {{ $option['type'] == 'size' ? 'selected' : '' }}>{{ __('Size') }}
                    </option>
                </select>
                @if ($option['type'] === 'color')
                    <input type="color" wire:model="options.{{ $index }}.value">
                @else
                    <x-input type="text" wire:model="options.{{ $index }}.value" />
                @endif
                <x-button danger type="button" wire:click="removeOption({{ $index }})"
                    wire:loading.attr="disabled"><i class="fas fa-trash"></i>
                </x-button>
            </div>
        @endforeach
        <x-button primary type="button" wire:click="addOption" wire:loading.attr="disabled">{{ __('Add Option') }}
            (+)</x-button>
    </div>
</div>
