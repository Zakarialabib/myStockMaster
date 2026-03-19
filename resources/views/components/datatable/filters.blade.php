@props([
    'perPageOptions' => [25, 50, 100],
    'searchPlaceholder' => 'Search...',
    'showPerPage' => true,
    'showSearch' => true,
    'showSelected' => true,
    'selectedCount' => 0,
    'canDelete' => false,
    'extraFilters' => null
])

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
    <div class="flex flex-wrap items-center gap-4">
        @if($showPerPage)
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                    {{ __('Show') }}
                </label>
                <x-input.select wire:model.live="perPage" size="sm" class="w-20">
                    @foreach ($perPageOptions as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </x-input.select>
                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                    {{ __('entries') }}
                </span>
            </div>
        @endif

        @if($showSelected && $selectedCount > 0)
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 text-sm"></i>
                    <span class="text-sm text-blue-700 dark:text-blue-300">
                        <span class="font-semibold">{{ $selectedCount }}</span> {{ __('selected') }}
                    </span>
                </div>
                <button wire:click="resetSelected" wire:loading.attr="disabled"
                    class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 cursor-pointer transition-colors">
                    {{ __('Clear') }}
                </button>
            </div>
        @endif

        @if($canDelete && $selectedCount > 0)
            <x-button variant="danger" icon="fas fa-trash" size="sm" wire:click="deleteSelected"
                wire:confirm="{{ __('Are you sure you want to delete the selected items?') }}">
                {{ __('Delete Selected') }}
            </x-button>
        @endif
    </div>

    <div class="flex flex-wrap items-center gap-4">
        @if($extraFilters)
            {{ $extraFilters }}
        @endif
        
        @if($showSearch)
            <div class="min-w-[250px]">
                <x-input.text wire:model.live.debounce.500ms="search" 
                    placeholder="{{ $searchPlaceholder }}" 
                    icon="fas fa-search" 
                    autofocus />
            </div>
        @endif
    </div>
</div>
