@if ($paginator->hasPages())
        
    {!! __('Showing') !!}
    {{ $paginator->firstItem() }}
    {!! __('to') !!}
    {{ $paginator->lastItem() }}
    {!! __('of') !!}
    {{ $paginator->total() }}
    {!! __('results') !!}
                
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        
            
        
    @else
        
            
        
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            
                {{ $element }}
            
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    
                        {{ $page }}
                    
                @else
                    
                        {{ $page }}
                    
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        
    @else
        
    @endif
            
@endif