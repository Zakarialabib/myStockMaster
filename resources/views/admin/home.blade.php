@section('title', __('Dashboard'))

@section('breadcrumb')
    <div class="relative p-4 sm:p-6 ">
        <h1 class="text-2xl md:text-3xl text-gray-800 font-bold mb-1">{{ __('Hello') }}, {{ Auth::user()->name }} 👋
        </h1>
        <p>{{ __('What are you look for today ?') }}</p>
        <div class="py-5">
            <livewire:livesearch />
        </div>
    </div>
@endsection
<x-app-layout>
    <div class="px-2 mx-auto">
        {{-- <livewire:calculator /> --}}

        @can('dashboard_access')
            <livewire:stats.transactions />
        @endcan

    </div>
</x-app-layout>