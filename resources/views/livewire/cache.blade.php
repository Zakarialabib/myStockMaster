<div>
    <form wire:submit.prevent="onClearCache">
        <button type="submit" wire:loading.attr="disabled">
                {{ __('Clear all Cache') }}
        </button>
    </form>
</div>
