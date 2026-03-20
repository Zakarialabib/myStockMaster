<x-page-container title="{{ __('Dashboard') }}" :show-filters="false">

    <div class="py-5 px-4">
        <livewire:livesearch />
    </div>

    {{-- <livewire:calculator /> --}}

    <livewire:date-range dispatch-event="dashboard-date-range-updated" />

    @can('show total stats')
        @island
            <div wire:transition>
                <livewire:dashboard.kpi-cards :start-date="$startDate" :end-date="$endDate" />
            </div>
        @endisland
    @endcan


    @can('dashboard_access')
        @island
            <div wire:transition>
                <livewire:dashboard.transactions />
            </div>
        @endisland
    @endcan
</x-page-container>
