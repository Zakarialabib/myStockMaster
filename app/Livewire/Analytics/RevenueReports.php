<?php

declare(strict_types=1);

namespace App\Livewire\Analytics;

use App\Actions\Analytics\GenerateRevenueReportAction;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class RevenueReports extends Component
{
    use WithPagination;

    #[Validate('required|date')]
    public string $dateFrom;

    #[Validate('required|date|after_or_equal:dateFrom')]
    public string $dateTo;

    #[Validate('required|in:daily,weekly,monthly,yearly')]
    public string $reportType = 'daily';

    #[Validate('nullable|exists:categories,id')]
    public ?int $categoryFilter = null;

    #[Validate('nullable|exists:products,id')]
    public ?int $productFilter = null;

    public array $revenueData = [];

    public bool $includeProductBreakdown = true;

    public bool $includeCategoryBreakdown = true;

    public bool $includeTimeBreakdown = true;

    public function placeholder(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadRevenueReport();
    }

    public function updatedDateFrom(): void
    {
        $this->validateOnly('dateFrom');
        $this->loadRevenueReport();
    }

    public function updatedDateTo(): void
    {
        $this->validateOnly('dateTo');
        $this->loadRevenueReport();
    }

    public function updatedReportType(): void
    {
        $this->validateOnly('reportType');
        $this->loadRevenueReport();
    }

    public function updatedCategoryFilter(): void
    {
        $this->loadRevenueReport();
    }

    public function updatedProductFilter(): void
    {
        $this->loadRevenueReport();
    }

    public function updatedIncludeProductBreakdown(): void
    {
        $this->loadRevenueReport();
    }

    public function updatedIncludeCategoryBreakdown(): void
    {
        $this->loadRevenueReport();
    }

    public function updatedIncludeTimeBreakdown(): void
    {
        $this->loadRevenueReport();
    }

    public function loadRevenueReport(): void
    {
        try {
            $dateFrom = \Illuminate\Support\Facades\Date::parse($this->dateFrom);
            $dateTo = \Illuminate\Support\Facades\Date::parse($this->dateTo);

            $options = [
                'time_breakdown' => $this->reportType,
                'include_product_breakdown' => $this->includeProductBreakdown,
                'include_category_breakdown' => $this->includeCategoryBreakdown,
                'include_time_breakdown' => $this->includeTimeBreakdown,
            ];

            // Add filters if specified
            if ($this->categoryFilter) {
                $options['category_filter'] = $this->categoryFilter;
            }

            if ($this->productFilter) {
                $options['product_filter'] = $this->productFilter;
            }

            $generateRevenueReportAction = new GenerateRevenueReportAction;
            $options = array_merge([
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ], $options);
            $this->revenueData = $generateRevenueReportAction($options);
        } catch (Exception $exception) {
            session()->flash('error', 'Failed to load revenue report: ' . $exception->getMessage());
            $this->revenueData = [];
        }
    }

    public function resetFilters(): void
    {
        $this->categoryFilter = null;
        $this->productFilter = null;
        $this->includeProductBreakdown = true;
        $this->includeCategoryBreakdown = true;
        $this->includeTimeBreakdown = true;
        $this->loadRevenueReport();
    }

    public function exportReport(mixed $format = 'json')
    {
        try {
            $filename = 'revenue_report_' . $this->reportType . '_' . now()->format('Y-m-d_H-i-s');

            return match ($format) {
                'csv' => $this->exportCSV($filename),
                default => $this->exportJSON($filename),
            };
        } catch (Exception $exception) {
            session()->flash('error', 'Failed to export report: ' . $exception->getMessage());
        }
    }

    private function exportJSON(string $filename)
    {
        return response()->streamDownload(function (): void {
            $exportData = [
                'report_type' => $this->reportType,
                'date_range' => [
                    'from' => $this->dateFrom,
                    'to' => $this->dateTo,
                ],
                'filters' => [
                    'category' => $this->categoryFilter,
                    'product' => $this->productFilter,
                ],
                'options' => [
                    'include_product_breakdown' => $this->includeProductBreakdown,
                    'include_category_breakdown' => $this->includeCategoryBreakdown,
                    'include_time_breakdown' => $this->includeTimeBreakdown,
                ],
                'data' => $this->revenueData,
                'generated_at' => now()->toISOString(),
            ];

            $generator = function () use ($exportData) {
                yield '{';
                $first = true;
                foreach ($exportData as $key => $value) {
                    if (! $first) {
                        yield ',';
                    }

                    yield '"' . $key . '":' . json_encode($value);
                    $first = false;
                }

                yield '}';
            };

            foreach ($generator() as $chunk) {
                echo $chunk;
            }
        }, $filename . '.json', [
            'Content-Type' => 'application/json',
        ]);
    }

    private function exportCSV(string $filename)
    {
        return response()->streamDownload(function (): void {
            $output = fopen('php://output', 'w');

            // Write headers
            fputcsv($output, ['Date', 'Total Revenue', 'Total Sales', 'Average Order Value']);

            $generator = function () {
                if (isset($this->revenueData['time_breakdown'])) {
                    foreach ($this->revenueData['time_breakdown'] as $period => $data) {
                        yield [
                            $period,
                            $data['total_revenue'] ?? 0,
                            $data['total_sales'] ?? 0,
                            $data['average_order_value'] ?? 0,
                        ];
                    }
                }
            };

            foreach ($generator() as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, $filename . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * @return mixed[]|array<'labels', list>
     */
    #[Computed]
    public function chartData(): array
    {
        if (! isset($this->revenueData['time_breakdown'])) {
            return [];
        }

        $labels = [];
        $revenues = [];
        $sales = [];

        foreach ($this->revenueData['time_breakdown'] as $period => $data) {
            $labels[] = $period;
            $revenues[] = $data['total_revenue'] ?? 0;
            $sales[] = $data['total_sales'] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenues,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Sales Count',
                    'data' => $sales,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'yAxisID' => 'y1',
                ],
            ],
        ];
    }

    #[Computed]
    public function categories()
    {
        return Category::query()->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function products()
    {
        return Product::query()->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.analytics.revenue-reports');
    }
}
