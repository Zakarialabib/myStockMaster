<div id="{!! $chartId !!}"></div>

@push('scripts')
<script>
    (function () {
        const options = {
            chart: {
                id: `{!! $chartId !!}`,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    barHeight: '100%',
                    distributed: true,
                    dataLabels: {
                        // position: 'bottom'
                    },
                }
            },
            xaxis: {
                type: 'category',
                categories: {!! $categories !!}
            },
            series: [{
                name: `{!! $seriesName !!}`,
                data: {!! $seriesData !!}
            }],
            // colors: ['#5c6ac4', '#007ace'],
        }
        const chart = new ApexCharts(document.getElementById(`{!! $chartId !!}`), options);
        chart.render();
        document.addEventListener('livewire:load', () => {
            @this.on(`refreshChartData-{!! $chartId !!}`, (chartData) => {
                chart.updateOptions({
                    xaxis: {
                        categories: chartData.categories
                    }
                });
                chart.updateSeries([{
                    data: chartData.seriesData,
                    name: chartData.seriesName,
                }]);
            });
        });
    }());
</script>
@endpush
@prepend('scripts')
    {{-- Push ApexCharts to the top of the scripts stack --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endprepend