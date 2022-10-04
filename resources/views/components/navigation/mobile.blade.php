<nav class="grid gap-y-8">
   
    <a href="{{ route('front.about') }}" title="About" class="-m-3 p-3 flex items-center rounded-md hover:bg-sky-200">
        <svg class="flex-shrink-0 h-6 w-6 text-sky-500 dark:text-sky-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <span class="ml-3 text-base font-medium text-gray-900 dark:text-slate-400">{{ __('About') }}</span>
    </a>
    {{-- TODO -->> @if ($blog_active == true) --}}
    <a href="{{ route('front.blogs') }}" title="Blogs" class="-m-3 p-3 flex items-center rounded-md hover:bg-sky-200">
        <svg class="flex-shrink-0 h-6 w-6 text-sky-500 dark:text-sky-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <span class="ml-3 text-base font-medium text-gray-900 dark:text-slate-400">{{ __('Blog') }}</span>
    </a>
    {{-- @endif --}}
    <a href="{{ route('front.contact') }}" title="Contact" class="-m-3 p-3 flex items-center rounded-md hover:bg-sky-200">
        <svg class="flex-shrink-0 h-6 w-6 text-sky-500 dark:text-sky-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <span class="ml-3 text-base font-medium text-gray-900 dark:text-slate-400">{{ __('Contact') }}</span>
    </a>

</nav>
