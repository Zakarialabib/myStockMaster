<nav class="hidden md:flex space-x-10">
    <a href="{{ route('front.about') }}" title="About"
        class="text-base font-semibold text-gray-500 hover:text-sky-800 dark:text-slate-400 dark:hover:text-sky-400">
        {{ __('About') }}
    </a>
    {{-- TODO -->> @if ($blog_active == true) --}}
    <a href="{{ route('front.blogs') }}" title="Blog"
        class="text-base font-semibold text-gray-500 hover:text-sky-800 dark:text-slate-400 dark:hover:text-sky-400">
        {{ __('Blog') }}
    </a>
    {{-- @endif --}}
    <a href="{{ route('front.contact') }}" title="Contact"
        class="text-base font-semibold text-gray-500 hover:text-sky-800 dark:text-slate-400 dark:hover:text-sky-400">
        {{ __('Contact') }}
    </a>
</nav>
