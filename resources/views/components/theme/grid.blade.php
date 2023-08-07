@props(['gutter', 'items', 'colWidth'])
<div class="flex flex-wrap -mx-{{ $gutter }} justify-center">
    @foreach ($items as $item)
        <div class="w-full md:w-{{ $colWidth }} px-{{ $gutter }}">
            {{ $item }}
        </div>
    @endforeach
</div>


{{-- 
<x-grid :items="$menuItems" colWidth="3" gutter="4">
</x-grid>
 --}}