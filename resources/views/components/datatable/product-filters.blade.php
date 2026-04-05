@props([
    'perPage' => 10,
    'paginationOptions' => [10, 25, 50, 100],
    'search' => '',
    'categoryId' => '',
    'categories' => [],
    'filterAvailability' => '',
    'filterSeasonality' => '',
    'selectedCount' => 0,
    'canDelete' => false,
    'searchPlaceholder' => 'Search...',
])

<div class="space-y-4">
    <!-- Main Filters Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Per Page -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('Show') }}
            </label>
            <x-input.select wire:model.live="perPage">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </x-input.select>
        </div>

        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('Search') }}
            </label>
            <x-input.text 
                wire:model.live.debounce.500ms="search" 
                placeholder="{{ $searchPlaceholder }}"
                icon="fas fa-search" 
            />
        </div>

        <!-- Category Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('Category') }}
            </label>
            <x-input.select wire:model.live="category_id">
                <option value="">{{ __('Select Category') }}</option>
                @foreach ($categories as $index => $category)
                    <option value="{{ $index }}">{{ $category }}</option>
                @endforeach
            </x-input.select>
        </div>

        <!-- Availability Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('Availability') }}
            </label>
            <x-input.select wire:model.live="filterAvailability">
                <option value="">{{ __('All') }}</option>
                <option value="1">{{ __('Available') }}</option>
                <option value="0">{{ __('Not Available') }}</option>
            </x-input.select>
        </div>

        <!-- Seasonality Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('Seasonality') }}
            </label>
            <x-input.text 
                wire:model.live="filterSeasonality" 
                placeholder="{{ __('Enter seasonality') }}" 
            />
        </div>
    </div>

    <!-- Selected Items Actions -->
    @if ($selectedCount > 0)
        <div class="flex items-center space-x-2 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
            <div class="flex items-center space-x-2 text-blue-700 dark:text-blue-300">
                <i class="fas fa-info-circle w-4 h-4"></i>
                <span class="text-sm font-medium">
                    {{ $selectedCount }} {{ __('selected') }}
                </span>
            </div>
            
            @if ($canDelete)
                <x-button 
                    wire:click="deleteSelectedModal" 
                    variant="danger" 
                    size="sm" 
                    icon="fas fa-trash"
                >
                    {{ __('Delete Selected') }}
                </x-button>
            @endif

            <x-button 
                wire:click="printSelectedModal" 
                variant="secondary" 
                size="sm" 
                icon="fas fa-print"
            >
                {{ __('Print Selected') }}
            </x-button>
            
            <x-button 
                wire:click="$set('selected', [])" 
                variant="secondary" 
                size="sm" 
                icon="fas fa-times"
            >
                {{ __('Clear Selected') }}
            </x-button>
        </div>
    @endif
</div>
