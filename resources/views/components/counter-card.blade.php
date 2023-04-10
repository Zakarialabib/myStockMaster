@props([ 'href' => '#' ,'color' =>'green', 'counter' ,'title'])

<div>
    <a class="p-3 group hover:bg-{{$color}}-500 cursor-pointer hover:shadow-xl border bg-white hover:border-{{$color}}-500 duration-200 ease-in-out transition-all flex items-center rounded-md overflow-hidden sm:rounded-md w-full"
        href="{{ $href }}">
        <div class="image bg-{{$color}}-500 group-hover:bg-transparent sm:p-4 p-2 mr-3 rounded-lg">
            <svg class="w-6 h-6 stroke-current group-hover:text-{{$color}}-500 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                {{$slot}}
            </svg>
        </div>
        <div class="title">
            <h3 class="sm:text-2xl text-lg antialiased group-hover:text-gray-50 font-bold">{{$counter}}</h3>
            <span class="sm:text-sm text-xs antialiased group-hover:text-gray-50">{{$title}}</span>
        </div>
    </a>
</div>