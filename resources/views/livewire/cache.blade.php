<div>
    <form wire:submit.prevent="onClearCache">
        <button type="submit" 
            class="px-3 py-2 leading-4 text-xs bg-indigo-900 text-white hover:text-indigo-800 hover:bg-indigo-100 active:bg-indigo-200 focus:ring-indigo-300 font-medium uppercase rounded-md shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150">
            <span>
                <span>{{ __('Clear all Cache') }}</span>
            </span>
        </button>
    </form>
</div>
