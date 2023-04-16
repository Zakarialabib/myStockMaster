@php
    $tableClasses = ' border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative ';
@endphp

<div class="my-5 bg-white shadow rounded-lg overflow-x-auto overflow-y-auto relative scrollbar__inverted" style="height:400px">

    <table {{ $attributes->merge(['class' => $tableClasses]) }}>
        <x-table.thead>
            {{ $thead }}
        </x-table.thead>

        {{ $slot }}

    </table>
</div>
