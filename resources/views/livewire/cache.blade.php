<div>
    <form wire:submit.prevent="onClearCache">
        <button
            class="px-3 py-2 leading-4 text-xs bg-blue-900 text-white hover:text-blue-800 hover:bg-blue-100 active:bg-blue-200 focus:ring-blue-300 font-medium uppercase rounded-md shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150">
            <span>
                <div wire:loading wire:target="onClearCache">
                    <x-loading />
                </div>
                <span>{{ __('Clear all Cache') }}</span>
            </span>
        </button>
    </form>
</div>
