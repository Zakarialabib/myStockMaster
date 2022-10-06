# Create a sort-field like sort in resources/views/components/table/sort.blade.php:
# <div class="inline-flex items-center">
#     <button wire:click="sortBy('{{ $field }}')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
#         <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
#             <path class="heroicon-ui"
#                 d="M7 11l5-5 5 5m-5-5v12" />
#         </svg>
#     </button>
#     <button wire:click="sortBy('{{ $field }}')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
#         <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
#             <path class="heroicon-ui" 
#                 d="M17 13l-5 5-5-5m5 5V6" />
#         </svg>
#     </button>
# </div>
#
# Create a sort-field like sort in resources/views/components/sort/sort-field.blade.php:

# # Path: resources/views/components/sort/sort-field.blade.php
# <div class="flex items-center">
#     <div class="w-4 h-4 mr-1">
#         <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
#             <path
#                 d="M12.293 6.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L14.586 12l-3.293-3.293a1 1 0 0 1 0-1.414z" />
#             <path
#                 d="M7.707 13.707a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414l4-4a1 1 0 0 1 1.414 1.414L5.414 8l3.293 3.293a1 1 0 0 1 0 1.414z" />
#         </svg>
#     </div>
#     <div class="w-4 h-4 mr-1">
#         <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
#             <path
#                 d="M12.293 6.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L14.586 12l-3.293-3.293a1 1 0 0 1 0-1.414z" />
#         </svg>
#     </div>
# </div>
#
# Compare this snippet from resources/views/components/table/sort.blade.php:

@props(['field', 'direction'])
@if($direction === 'asc')
    <i wire:click="sortBy('{{ $field }}')" class="fa fa-fw fa-sort-up cursor-pointer text-blue-500" aria-hidden="true"></i>
@elseif($direction === 'desc')
    <i wire:click="sortBy('{{ $field }}')" class="fa fa-fw fa-sort-down cursor-pointer text-blue-500" aria-hidden="true"></i>
@else
    <i wire:click="sortBy('{{ $field }}')" class="fa fa-fw fa-sort cursor-pointer" aria-hidden="true"></i>
@endif


