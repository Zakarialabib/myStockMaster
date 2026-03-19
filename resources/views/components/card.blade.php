<div class="flex flex-col justify-center items-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-soft w-full z-0">
    @if(isset($header))
        <div class="w-full px-8 py-5 border-b border-gray-200 dark:border-gray-800">
            {{ $header }}
        </div>
    @endif
    
    <div class="w-full p-8 overflow-hidden">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="w-full px-8 py-5 border-t border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 rounded-b-2xl">
            {{ $footer }}
        </div>
    @endif
</div>
