@php
    $id = $id ?? $name ?? md5($attributes->wire('model'));
@endphp

<div
    x-data="appModal({
        show: @entangle($attributes->wire('model')),
        lazy: {{ $lazy ? 'true' : 'false' }},
        cacheContent: {{ $cacheContent ? 'true' : 'false' }},
        focusable: {{ $focusable ? 'true' : 'false' }},
        restoreScroll: {{ $restoreScroll ? 'true' : 'false' }},
        closeable: {{ $closeable ? 'true' : 'false' }},
        persistent: {{ $persistent ? 'true' : 'false' }}
    })"
    x-init="init()"
    x-on:close.stop="close()"
    @if($closeOnEscape)
    x-on:keydown.escape.window="close()"
    @endif
    @if($trapFocus)
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    @endif
    x-show="show"
    x-transition:enter="{{ $animationClasses }}"
    x-transition:enter-start="opacity-0 {{ $animation === 'slide' ? 'transform -translate-y-4' : '' }} {{ $animation === 'zoom' ? 'transform scale-95' : '' }}"
    x-transition:enter-end="opacity-100 {{ $animation === 'slide' ? 'transform translate-y-0' : '' }} {{ $animation === 'zoom' ? 'transform scale-100' : '' }}"
    x-transition:leave="{{ $animationClasses }}"
    x-transition:leave-start="opacity-100 {{ $animation === 'slide' ? 'transform translate-y-0' : '' }} {{ $animation === 'zoom' ? 'transform scale-100' : '' }}"
    x-transition:leave-end="opacity-0 {{ $animation === 'slide' ? 'transform -translate-y-4' : '' }} {{ $animation === 'zoom' ? 'transform scale-95' : '' }}"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 {{ $zIndex }}"
    style="display: none;"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-title"
    aria-describedby="modal-description"
>
    @if($backdrop)
    <!-- BACKDROP -->
    <div x-show="show" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 backdrop-blur-sm transition-all" 
         @if($closeable && !$persistent)
         x-on:click="close()"
         @endif
         aria-hidden="true">
    </div>
    @endif

    <!-- MODAL CONTENT -->
    <div x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 {{ $animation === 'slide' ? 'translate-y-4 sm:translate-y-0 sm:scale-95' : ($animation === 'zoom' ? 'scale-95' : '') }}"
         x-transition:enter-end="opacity-100 {{ $animation === 'slide' ? 'translate-y-0 sm:scale-100' : ($animation === 'zoom' ? 'scale-100' : '') }}" 
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 {{ $animation === 'slide' ? 'translate-y-0 sm:scale-100' : ($animation === 'zoom' ? 'scale-100' : '') }}"
         x-transition:leave-end="opacity-0 {{ $animation === 'slide' ? 'translate-y-4 sm:translate-y-0 sm:scale-95' : ($animation === 'zoom' ? 'scale-95' : '') }}"
         class="relative mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidthClass }} sm:mx-auto"
         x-trap.noscroll.inert="show && {{ $trapFocus ? 'true' : 'false' }}">
         
         @if($lazy)
         <!-- Loading State -->
         <div x-show="loading" class="flex items-center justify-center p-8">
             <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
             <span class="ml-3 text-gray-600 dark:text-gray-400">{{ __('Loading...') }}</span>
         </div>
         @endif
         
         <!-- Modal Content -->
         <div x-show="!loading || !{{ $lazy ? 'true' : 'false' }}" 
              @if($cacheContent) wire:ignore @endif>
             {{ $slot }}
         </div>
    </div>
</div>
