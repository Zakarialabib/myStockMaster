<div x-data="loadingMask" 
     x-show="!pageLoaded" 
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: flex;"
     class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white dark:bg-gray-900">
    <div class="relative flex flex-col items-center">
        <x-loading class="w-16 h-16 text-primary-600" />
        <div class="mt-4 text-gray-500 dark:text-gray-400 font-medium animate-pulse">
            {{ __('Loading...') }}
        </div>
    </div>
</div>
