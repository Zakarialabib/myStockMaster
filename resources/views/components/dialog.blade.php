<div
    role="presentation"
    class="tablet:items-center fixed inset-0 z-50 grid items-end justify-center overflow-hidden"
    x-data="dialog($el)"
    {{ $attributes }}>
    <div x-bind="backdrop"
        class="fixed inset-0 z-10 bg-black/40"
        x-show="show"
        x-transition.opacity
        aria-hidden="true"></div>

    <div
        x-bind="dialogEl"
        {{ $body->attributes->merge([
            'class' =>
                'relative z-20 max-h-[calc(100vh-20px)] w-full max-w-[100vw] tablet:w-[600px] rounded-t-md tablet:rounded-b-md bg-white py-6 px-6 shadow-lg overflow-auto overscroll-contain',
        ]) }}
        role="dialog"
        aria-modal="true"
        x-show="show"
        x-transition>
        {{ $body }}
        <button class="tablet:block !absolute right-1.5 top-1.5 hidden p-2" type="button"
            x-on:click="closeDialog">
            <svg class="h-5 w-5 stroke-gray-900" aria-hidden="true">
                <use href="#close" />
            </svg>
        </button>
        <span
            class="tablet:hidden pointer-events-none absolute left-0 right-0 top-2 m-auto h-1 w-7 rounded-lg bg-gray-300"></span>
    </div>
</div>