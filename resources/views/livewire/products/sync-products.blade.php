<div>
    <!-- Create Modal -->
    <x-modal wire:model="syncModal">
        <x-slot name="title">
            {{ __('Create Product') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="sync">
            </form>
        </x-slot>
    </x-modal>
</div>
