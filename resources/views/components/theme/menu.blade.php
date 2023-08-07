<div class="menu">
    <x-menu-widget>
        @foreach ($items as $item)
            <x-menu-widget-item label="{{ $item['label'] }}" url="{{ $item['url'] }}" />
        @endforeach
    </x-menu-widget>
</div>

@push('scripts')
    <script>
        const draggable = document.querySelector('.menu-widget');

        new Sortable(draggable, {
            animation: 150,
            handle: '.drag-handle',
        });
    </script>
@endpush