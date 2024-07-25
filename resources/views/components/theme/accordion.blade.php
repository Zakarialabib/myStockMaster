<div>
    <div wire:click="$set('isActive', ! $isActive)" class="cursor-pointer border-b border-gray-400 py-2">
        <h3 class="font-bold">{{ $title }}</h3>
        <svg wire:ignore class="w-6 h-6 inline-block" viewBox="0 0 24 24">
            <path :class="{ 'hidden': isActive }" fill="currentColor" d="M19,13H5V11H19V13Z"></path>
            <path :class="{ 'hidden': ! isActive }" fill="currentColor" d="M13,19L7,13H10V11H14V13H17L13,17V19Z"></path>
        </svg>
    </div>
    <div :class="{ 'hidden': ! isActive }" class="border-l border-r border-b border-gray-400 py-2">
        <p>{{ $description }}</p>
        @if ($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="my-2">
        @endif
    </div>
</div>
