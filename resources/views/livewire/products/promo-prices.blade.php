<div>
    <x-modal wire:model="promoModal">
        <x-slot name="title">
            {{ __('Promotion for all products') }}
        </x-slot>
        <x-slot name="content">
            <form wire:submit="saveImage">
                <div class="flex flex-wrap">
                    <div>
                        <label for="percentage">{{ __('Percentage') }}</label>
                        <input type="number" wire:model="percentage" id="percentage">
                    </div>
                    <div>
                        <label for="copy_price_to_old_price">{{ __('Copy price to old price') }}</label>
                        <input type="checkbox" wire:model="copyPriceToOldPrice" id="copy_price_to_old_price">
                    </div>
                    <button type="submit">{{ __('Update') }}</button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
