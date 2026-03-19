<div id="{!! $chartId !!}"></div>

@script
<script>
    Alpine.nextTick(() => {
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
        };
        const chart = new ApexCharts(document.getElementById(`{!! $chartId !!}`), options);
        chart.render();
        
        $wire.on(`refreshChartData-{!! $chartId !!}`, (event) => {
            const chartData = event[0] || event;
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
</script>
@endscript
