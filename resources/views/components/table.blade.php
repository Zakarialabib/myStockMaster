@php
    $tableClasses = ' table-auto w-full ';
@endphp

<div class="my-4 bg-white h-56 shadow rounded-lg overflow-x-auto scrollbar__inverted">

    <table {{ $attributes->merge(['class' => $tableClasses]) }}>
        <x-table.thead>
            {{ $thead }}
        </x-table.thead>

        {{ $slot }}

    </table>
</div>
