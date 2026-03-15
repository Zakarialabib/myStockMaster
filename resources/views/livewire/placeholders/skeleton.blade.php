<div class="animate-pulse flex flex-col space-y-4 p-4">
    <div class="h-8 bg-gray-200 rounded w-1/4"></div>
    <div class="space-y-3">
        @foreach(range(1, 5) as $i)
            <div class="h-10 bg-gray-100 rounded w-full"></div>
        @endforeach
    </div>
</div>
