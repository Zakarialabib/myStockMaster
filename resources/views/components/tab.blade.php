@props(['name'])

<div x-data="{ id: '', name: '{{ $name }}' }"
     x-init="id = addTab('{{ $name }}')"
     x-show="tabState(id)"
     role="tabpanel"
     :aria-labelledby="`tab-${id}`"
     :id="`tab-panel-${id}`">
    {{ $slot }}
</div>      