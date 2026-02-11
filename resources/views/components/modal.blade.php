@props([
    'id' => null,
    'name' => 'modal',
    'show' => false,
    'maxWidth' => '2xl',
    'closeable' => true,
    'focusable' => true,
    'persistent' => false,
    'backdrop' => true,
    'animation' => 'fade',
    'zIndex' => 'z-50',
    'closeOnEscape' => true,
    'trapFocus' => true,
    'restoreScroll' => true,
    'lazy' => false,
    'cacheContent' => false,
])

@php
    $id = $id ?? $name ?? md5($attributes->wire('model'));
    
    $maxWidthClasses = [
        'xs' => 'sm:max-w-xs',
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
        '6xl' => 'sm:max-w-6xl',
        '7xl' => 'sm:max-w-7xl',
        'full' => 'sm:max-w-full',
    ][$maxWidth] ?? 'sm:max-w-2xl';
    
    $animationClasses = [
        'fade' => 'transition-opacity duration-300',
        'slide' => 'transition-transform duration-300',
        'zoom' => 'transition-all duration-300',
        'none' => '',
    ][$animation] ?? 'transition-opacity duration-300';
@endphp

<div
    x-data="{
        show: @entangle($attributes->wire('model')),
        loading: {{ $lazy ? 'true' : 'false' }},
        cached: {{ $cacheContent ? 'false' : 'true' }},
        scrollPosition: 0,
        
        // Enhanced focusable management
        focusables() {
            if (!this.cached) return [];
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
            return [...$el.querySelectorAll(selector)]
                .filter(el => !el.hasAttribute('disabled') && !el.hasAttribute('aria-hidden'));
        },
        
        firstFocusable() { return this.focusables()[0]; },
        lastFocusable() { return this.focusables().slice(-1)[0]; },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable(); },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable(); },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1); },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1; },
        
        // Performance optimizations
        init() {
            if ({{ $lazy ? 'true' : 'false' }}) {
                this.$watch('show', (value) => {
                    if (value && this.loading) {
                        this.loadContent();
                    }
                });
            }
        },
        
        loadContent() {
            setTimeout(() => {
                this.loading = false;
                this.cached = true;
            }, 100);
        },
        
        open() {
            if ({{ $restoreScroll ? 'true' : 'false' }}) {
                this.scrollPosition = window.pageYOffset;
            }
            
            this.show = true;
            document.body.classList.add('overflow-hidden');
            
            if ({{ $focusable ? 'true' : 'false' }}) {
                this.$nextTick(() => {
                    const firstFocusable = this.firstFocusable();
                    if (firstFocusable) firstFocusable.focus();
                });
            }
        },
        
        close() {
            if (!{{ $closeable ? 'true' : 'false' }} || {{ $persistent ? 'true' : 'false' }}) return;
            
            this.show = false;
            document.body.classList.remove('overflow-hidden');
            
            if ({{ $restoreScroll ? 'true' : 'false' }}) {
                window.scrollTo(0, this.scrollPosition);
            }
            
            if (!{{ $cacheContent ? 'true' : 'false' }}) {
                this.cached = false;
                this.loading = true;
            }
        }
    }"
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
         class="relative mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidthClasses }} sm:mx-auto"
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

@push('scripts')
<script>
    // Enhanced modal performance utilities
    document.addEventListener('alpine:init', () => {
        Alpine.data('modalUtils', () => ({
            // Prevent body scroll when modal is open
            preventBodyScroll(show) {
                if (show) {
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = this.getScrollbarWidth() + 'px';
                } else {
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
            },
            
            // Calculate scrollbar width to prevent layout shift
            getScrollbarWidth() {
                const outer = document.createElement('div');
                outer.style.visibility = 'hidden';
                outer.style.overflow = 'scroll';
                outer.style.msOverflowStyle = 'scrollbar';
                document.body.appendChild(outer);
                
                const inner = document.createElement('div');
                outer.appendChild(inner);
                
                const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;
                outer.parentNode.removeChild(outer);
                
                return scrollbarWidth;
            },
            
            // Enhanced focus management
            manageFocus(show, trapFocus) {
                if (!trapFocus) return;
                
                if (show) {
                    this.previousActiveElement = document.activeElement;
                } else if (this.previousActiveElement) {
                    this.previousActiveElement.focus();
                }
            }
        }));
    });
</script>
@endpush
