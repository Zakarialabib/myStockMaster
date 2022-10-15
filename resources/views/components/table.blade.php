<div class="align-middle min-w-full overflow-x-auto overflow-hidden sm:rounded-md scrollbar__inverted shadow-sm dark:shadow-none">

    <table class="table items-center w-full mb-0 align-top border-grey-200 text-[#67748e] pt-5">
        <x-table.thead>
            {{ $thead }}
        </x-table.thead>

        {{ $slot }}

    </table>
</div>
