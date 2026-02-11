@props([
    'actions' => [],
    'alignment' => 'right' // left, center, right
])

@php
    $alignmentClasses = [
        'left' => 'justify-start',
        'center' => 'justify-center', 
        'right' => 'justify-end'
    ];
    $alignClass = $alignmentClasses[$alignment] ?? $alignmentClasses['right'];
@endphp

<div class="flex flex-wrap items-center gap-3 {{ $alignClass }}">
    @if(!empty($actions))
        @foreach($actions as $action)
            @if(isset($action['permission']) && !Gate::allows($action['permission']))
                @continue
            @endif
            
            @if($action['type'] === 'button')
                <x-button 
                    wire:click="{{ $action['action'] ?? '' }}"
                    variant="{{ $action['variant'] ?? 'primary' }}"
                    icon="{{ $action['icon'] ?? '' }}"
                    size="{{ $action['size'] ?? 'md' }}"
                    {{ isset($action['confirm']) ? 'wire:confirm="' . $action['confirm'] . '"' : '' }}
                    {{ isset($action['loading']) ? 'wire:loading.attr="disabled"' : '' }}
                    {{ isset($action['target']) ? 'wire:target="' . $action['target'] . '"' : '' }}>
                    {{ $action['label'] }}
                </x-button>
            @elseif($action['type'] === 'link')
                <a href="{{ $action['url'] ?? '#' }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-colors
                          {{ $action['variant'] === 'primary' ? 'bg-blue-600 hover:bg-blue-700 text-white' : '' }}
                          {{ $action['variant'] === 'secondary' ? 'bg-gray-600 hover:bg-gray-700 text-white' : '' }}
                          {{ $action['variant'] === 'success' ? 'bg-green-600 hover:bg-green-700 text-white' : '' }}
                          {{ $action['variant'] === 'danger' ? 'bg-red-600 hover:bg-red-700 text-white' : '' }}">
                    @if(isset($action['icon']))
                        <i class="{{ $action['icon'] }}"></i>
                    @endif
                    {{ $action['label'] }}
                </a>
            @elseif($action['type'] === 'dropdown')
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        @if(isset($action['icon']))
                            <i class="{{ $action['icon'] }}"></i>
                        @endif
                        {{ $action['label'] }}
                        <i class="fas fa-chevron-down text-xs" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div x-show="open" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                        @foreach($action['items'] as $item)
                            @if(isset($item['permission']) && !Gate::allows($item['permission']))
                                @continue
                            @endif
                            
                            @if($item['type'] === 'button')
                                <button wire:click="{{ $item['action'] }}"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 first:rounded-t-lg last:rounded-b-lg transition-colors">
                                    @if(isset($item['icon']))
                                        <i class="{{ $item['icon'] }} mr-2"></i>
                                    @endif
                                    {{ $item['label'] }}
                                </button>
                            @elseif($item['type'] === 'link')
                                <a href="{{ $item['url'] }}"
                                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 first:rounded-t-lg last:rounded-b-lg transition-colors">
                                    @if(isset($item['icon']))
                                        <i class="{{ $item['icon'] }} mr-2"></i>
                                    @endif
                                    {{ $item['label'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @else
        {{ $slot }}
    @endif
</div>