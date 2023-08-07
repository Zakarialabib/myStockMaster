<!-- header.blade.php -->

<div class="header">
    <div class="logo">
        <x-theme.logo url="{{ $logoUrl }}" />
    </div>
    <div class="menu">
        @if(count($menuItems) > 0)
        @foreach ($menuItems as $key => $item)
        <x-theme.menu-widget-item label="{{ $item['label'] ?? '' }}" url="{{ $item['url'] ?? '' }}" />
        @endforeach
        @endif
    </div>
</div>

{{-- @push('scripts')
    <script>
        const draggable = document.querySelector('.menu-widget');

        new Sortable(draggable, {
            animation: 150,
            handle: '.drag-handle',
        });
    </script>
@endpush --}}
