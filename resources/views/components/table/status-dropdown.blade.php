@props([
    'id',
    'value',
    'options',
    'action'
])

<div x-data="{ 
        open: false, 
        currentStatus: '{{ $value }}'
    }" 
    class="relative inline-block text-left"
    @click.outside="open = false"
>
    <div>
        <button type="button" @click="open = !open" 
            class="inline-flex justify-center w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
            id="options-menu-{{ $id }}" 
            aria-haspopup="true" 
            x-bind:aria-expanded="open"
        >
            <span x-text="currentStatus"></span>
            <!-- Heroicon name: solid/chevron-down -->
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div x-show="open" 
        x-transition:enter="transition ease-out duration-100" 
        x-transition:enter-start="transform opacity-0 scale-95" 
        x-transition:enter-end="transform opacity-100 scale-100" 
        x-transition:leave="transition ease-in duration-75" 
        x-transition:leave-start="transform opacity-100 scale-100" 
        x-transition:leave-end="transform opacity-0 scale-95" 
        class="origin-top-right absolute z-50 right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none" 
        role="menu" 
        aria-orientation="vertical" 
        aria-labelledby="options-menu-{{ $id }}"
        style="display: none;"
    >
        <div class="py-1" role="none">
            @foreach($options as $option)
                <button type="button" 
                    @click="
                        open = false; 
                        currentStatus = '{{ $option['label'] }}';
                        $wire.{{ $action }}({{ $id }}, '{{ $option['value'] }}');
                    " 
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100" 
                    role="menuitem"
                >
                    {{ $option['label'] }}
                </button>
            @endforeach
        </div>
    </div>
</div>
