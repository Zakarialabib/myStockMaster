<div class="align-middle min-w-full overflow-x-auto overflow-hidden sm:rounded-md scrollbar__inverted shadow-sm dark:shadow-none">
    <table {{ $attributes->merge(['class' => 'table-auto min-w-full']) }}>
       
        {{ $slot }}
       
    </table>
</div>
