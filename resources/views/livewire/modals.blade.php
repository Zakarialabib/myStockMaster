<div class="modals">
    @foreach ($components as $modal)
        <x-dialog
            wire:key="{{ $modal['component'] }}"
            id="{{ $modal['component'] }}"
        >
            <x-slot:body
                :style="$modal['maxWidth'] ? 'width: ' . $modal['maxWidth'] : ''"
            >
                @livewire($modal['component'], $modal['attributes'], key($modal['component']))
            </x-slot:body>
        </x-dialog>
    @endforeach
</div>