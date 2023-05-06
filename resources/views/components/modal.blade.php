@props(['id' => null, 'maxWidth' => null])

<div x-data="{
    show: @entangle($attributes->wire('model')),
    focusables() {
        // All focusable element types...
        let selector = 'a, button, input, textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'

        return [...$el.querySelectorAll(selector)]
            // All non-disabled elements...
            .filter(el => !el.hasAttribute('disabled'))
    },
    firstFocusable() { return this.focusables()[0] },
    lastFocusable() { return this.focusables().slice(-1)[0] },
    nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
    prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
    nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
    prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1 },
    autofocus() { let focusable = $el.querySelector('[autofocus]'); if (focusable) focusable.focus() },
}" x-init="$watch('show', value => value && setTimeout(autofocus, 50))" x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false" x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()" x-show="show"
    class="fixed inset-x-0 px-6 pt-28 mt-28 z-50 sm:fixed top-0 left-0 w-full h-full py-16 md:py-28 bg-opacity-50 overflow-y-auto"
    style="display: none;">
    <div class="fixed inset-0 transform" x-on:click="show = false">
        <div x-show="show" class="absolute inset-0 bg-zinc-500 opacity-75"></div>
    </div>
    <x-modal.card :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
        <div x-show="show"
            class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h2 class="text-lg font-bold text-gray-900">
                    {{ $title }}
                </h2>
            </div>
            <div class="mt-3 text-center sm:mt-0 mx-4 sm:text-left">
                <div class="mt-2">
                    {{ $content }}
                </div>
            </div>
        </div>
    </x-modal.card>
</div>

{{-- Example implementation --}}
