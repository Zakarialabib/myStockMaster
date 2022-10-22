@php
    $tableClasses = ' table-auto w-full ';
@endphp

<div class="my-4 bg-white align-middle shadow rounded overflow-x-auto scrollbar__inverted">

    <table {{ $attributes->merge(['class' => $tableClasses]) }}>
        <x-table.thead>
            {{ $thead }}
        </x-table.thead>

        {{ $slot }}

    </table>
</div>
