@props(['hfull' => false])

@php
    $tableClasses = ' table-auto w-full sm:table-fixed ';
    if ($hfull) {
        $tableClasses .= ' h-full';
    }
@endphp

<div class="mb-4 bg-white align-middle shadow rounded overflow-x-auto scrollbar__inverted">

    <table {{ $attributes->merge(['class' => $tableClasses]) }}>
        <x-table.thead>
            {{ $thead }}
        </x-table.thead>

        {{ $slot }}

    </table>
</div>
