@props(['field', 'direction'])
@if($direction === 'asc')
    <i wire:click="sortingBy('{{ $field }}')" class="fa fa-fw fa-sort-up cursor-pointer text-blue-500" aria-hidden="true"></i>
@elseif($direction === 'desc')
    <i wire:click="sortingBy('{{ $field }}')" class="fa fa-fw fa-sort-down cursor-pointer text-blue-500" aria-hidden="true"></i>
@else
    <i wire:click="sortingBy('{{ $field }}')" class="fa fa-fw fa-sort cursor-pointer" aria-hidden="true"></i>
@endif


