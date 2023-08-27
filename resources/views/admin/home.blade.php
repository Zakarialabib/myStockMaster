@section('title', __('Dashboard'))

@section('breadcrumb')
    <div class="relative p-4 sm:p-6 ">
        <h1 class="text-2xl md:text-3xl text-gray-800 font-bold mb-1">{{ __('Hello') }}, {{ Auth::user()->name }} 👋
        </h1>
        <p>{{ 'What are you look for today ?' }}</p>
        <div class="py-5">
            <livewire:livesearch />
        </div>
    </div>
@endsection
<x-app-layout>
    <div class="px-2 mx-auto">
        {{-- <livewire:calculator /> --}}

        @can('dashboard_access')
            <div class="bg-gray-50 mb-4 px-4 rounded">
                <div class="flex flex-wrap justify-center lg:text-lg sm:text-sm gap-4 py-4">
                    <x-button type="button" primary data-date="today" class="js-date mr-2 active:bg-indigo-800">
                        {{ __('Today') }}
                    </x-button>

                    <x-button type="button" primary data-date="month" class="js-date mr-2">
                        {{ __('Last month') }}
                    </x-button>

                    <x-button type="button" primary data-date="semi" class="js-date mr-2">
                        {{ __('Last 6 month') }}
                    </x-button>

                    <x-button type="button" primary data-date="year" class="js-date">
                        {{ __('Last year') }}
                    </x-button>
                </div>
                @foreach ($data as $key => $d)
                    @if ($loop->first)
                        <div class="w-full flex flex-wrap align-center mb-4 js-date-row" id="{{ $key }}">
                            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 w-full">
                                <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                                    <div class="p-5 mr-4 text-blue-500 bg-blue-100 rounded-full">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="mb-2 text-lg font-medium text-gray-800 dark:text-gray-300">
                                            {{ __('Sales') }}
                                        </p>
                                        <p class="text-3xl sm:text-lg font-bold text-gray-700">
                                            {{ format_currency($d['salesTotal']) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                                    <div class="p-5 mr-4 text-blue-500 bg-blue-100 rounded-full">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="mb-2 text-lg font-medium text-gray-600">
                                            {{ __('Stock Value') }}
                                        </p>
                                        <p class="text-3xl sm:text-lg font-bold text-gray-700">
                                            {{ format_currency($d['stockValue']) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="w-full flex flex-wrap align-center mb-4 js-date-row" style="display: none"
                            id="{{ $key }}">
                            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 w-full">
                                <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                                    <div class="p-5 mr-4 text-blue-500 bg-blue-100 rounded-full">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="mb-2 text-lg font-medium text-gray-600">
                                            {{ __('Sales') }}
                                        </p>
                                        <p class="text-3xl sm:text-lg font-bold text-gray-700">
                                            {{ format_currency($d['salesTotal']) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                                    <div class="p-5 mr-4 text-blue-500 bg-blue-100 rounded-full">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="mb-2 text-lg font-medium text-gray-600">
                                            {{ __('Stock Value') }}
                                        </p>
                                        <p class="text-3xl sm:text-lg font-bold text-gray-700">
                                            {{ format_currency($d['stockValue']) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div>
                <livewire:stats.transactions />
            </div>
        @endcan

    </div>
</x-app-layout>


@push('scripts')
    <script>
        document.querySelectorAll('.js-date').forEach(el => {
            el.addEventListener('click', event => {
                clearActive();
                hideAll();
                el.classList.add('active:bg-indigo-800');
                document.querySelector(`#${el.dataset.date}`).style.display = 'flex';
            });
        });

        const clearActive = () => {
            document.querySelectorAll('.js-date').forEach(el => {
                el.classList.remove('active');
            });
        };

        const hideAll = () => {
            document.querySelectorAll('.js-date-row').forEach(el => {
                el.style.display = 'none';
            });
        };
    </script>
@endpush
