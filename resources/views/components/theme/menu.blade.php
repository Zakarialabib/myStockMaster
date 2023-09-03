<div class="menu">
    <x-theme.menu-widget>
        @foreach ($items as $item)
            <x-theme.menu-widget-item label="{{ $item['label'] }}" url="{{ $item['url'] }}" />
        @endforeach
    </x-theme.menu-widget>
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