<div class="draggable-widget" x-data="{ dragging: false }" x-init="() => {
    $watch('dragging', (value) => {
        if (value) {
            document.querySelector('html').classList.add('dragging');
        } else {
            document.querySelector('html').classList.remove('dragging');
        }
    });
}">
    @foreach ($items as $item)
        <x-theme.menu-widget-item>{{ $item['label'] }}</x-theme.menu-widget-item>
    @endforeach
</div>

@push('scripts')
    <script>
        const draggable = document.querySelector('.draggable-widget');

        new Sortable(draggable, {
            animation: 150,
            handle: '.drag-handle',
        });
    </script>
@endpush
