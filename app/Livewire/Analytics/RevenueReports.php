<?php

declare(strict_types=1);

namespace App\Livewire\Analytics;

use App\Actions\Analytics\GenerateRevenueReportAction;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Exception;

class RevenueReports extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $reportType = 'daily';
    public $categoryFilter = null;
    public $productFilter = null;
    public $revenueData = [];
    public $loading = false;
    public $includeProductBreakdown = true;
    public $includeCategoryBreakdown = true;
    public $includeTimeBreakdown = true;

    protected $rules = [
        'dateFrom'       => 'required|date',
        'dateTo'         => 'required|date|after_or_equal:dateFrom',
        'reportType'     => 'required|in:daily,weekly,monthly,yearly',
        'categoryFilter' => 'nullable|exists:categories,id',
        'productFilter'  => 'nullable|exists:products,id',
    ];

    public function mount()
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadRevenueReport();
    }

    public function updatedDateFrom()
    {
        $this->validateOnly('dateFrom');
        $this->loadRevenueReport();
    }

    public function updatedDateTo()
    {
        $this->validateOnly('dateTo');
        $this->loadRevenueReport();
    }

    public function updatedReportType()
    {
        $this->validateOnly('reportType');
        $this->loadRevenueReport();
    }

    public function updatedCategoryFilter()
    {
        $this->loadRevenueReport();
    }

    public function updatedProductFilter()
    {
        $this->loadRevenueReport();
    }

    public function updatedIncludeProductBreakdown()
    {
        $this->loadRevenueReport();
    }

    public function updatedIncludeCategoryBreakdown()
    {
        $this->loadRevenueReport();
    }

    public function updatedIncludeTimeBreakdown()
    {
        $this->loadRevenueReport();
    }

    public function loadRevenueReport()
    {
        $this->loading = true;

        try {
            $dateFrom = Carbon::parse($this->dateFrom);
            $dateTo = Carbon::parse($this->dateTo);

            $options = [
                'time_breakdown'             => $this->reportType,
                'include_product_breakdown'  => $this->includeProductBreakdown,
                'include_category_breakdown' => $this->includeCategoryBreakdown,
                'include_time_breakdown'     => $this->includeTimeBreakdown,
            ];

            // Add filters if specified
            if ($this->categoryFilter) {
                $options['category_filter'] = $this->categoryFilter;
            }

            if ($this->productFilter) {
                $options['product_filter'] = $this->productFilter;
            }

            $revenueAction = new GenerateRevenueReportAction();
            $options = array_merge([
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
            ], $options);
            $this->revenueData = $revenueAction($options);
        } catch (Exception $e) {
            session()->flash('error', 'Failed to load revenue report: '.$e->getMessage());
            $this->revenueData = [];
        } finally {
            $this->loading = false;
        }
    }

    public function resetFilters()
    {
        $this->categoryFilter = null;
        $this->productFilter = null;
        $this->includeProductBreakdown = true;
        $this->includeCategoryBreakdown = true;
        $this->includeTimeBreakdown = true;
        $this->loadRevenueReport();
    }

    public function exportReport($format = 'json')
    {
        try {
            $filename = 'revenue_report_'.$this->reportType.'_'.now()->format('Y-m-d_H-i-s');

            switch ($format) {
                case 'csv':
                    return $this->exportCSV($filename);
                case 'json':
                default:
                    return $this->exportJSON($filename);
            }
        } catch (Exception $e) {
            session()->flash('error', 'Failed to export report: '.$e->getMessage());
        }
    }

    private function exportJSON($filename)
    {
        return response()->streamDownload(function () {
            echo json_encode([
                'report_type' => $this->reportType,
                'date_range'  => [
                    'from' => $this->dateFrom,
                    'to'   => $this->dateTo,
                ],
                'filters' => [
                    'category' => $this->categoryFilter,
                    'product'  => $this->productFilter,
                ],
                'options' => [
                    'include_product_breakdown'  => $this->includeProductBreakdown,
                    'include_category_breakdown' => $this->includeCategoryBreakdown,
                    'include_time_breakdown'     => $this->includeTimeBreakdown,
                ],
                'data'         => $this->revenueData,
                'generated_at' => now()->toISOString(),
            ], JSON_PRETTY_PRINT);
        }, $filename.'.json', [
            'Content-Type' => 'application/json',
        ]);
    }

    private function exportCSV($filename)
    {
        return response()->streamDownload(function () {
            $output = fopen('php://output', 'w');

            // Write headers
            fputcsv($output, ['Date', 'Total Revenue', 'Total Sales', 'Average Order Value']);

            // Write time breakdown data if available
            if (isset($this->revenueData['time_breakdown'])) {
                foreach ($this->revenueData['time_breakdown'] as $period => $data) {
                    fputcsv($output, [
                        $period,
                        $data['total_revenue'] ?? 0,
                        $data['total_sales'] ?? 0,
                        $data['average_order_value'] ?? 0,
                    ]);
                }
            }

            fclose($output);
        }, $filename.'.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function getChartData()
    {
        if ( ! isset($this->revenueData['time_breakdown'])) {
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
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Revenue',
                    'data'            => $revenues,
                    'borderColor'     => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label'           => 'Sales Count',
                    'data'            => $sales,
                    'borderColor'     => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'yAxisID'         => 'y1',
                ],
            ],
        ];
    }

    public function render()
    {
        $categories = Category::select('id', 'name')
            ->orderBy('name')
            ->get();

        $products = Product::select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        return view('livewire.analytics.revenue-reports', [
            'categories' => $categories,
            'products'   => $products,
            'chartData'  => $this->getChartData(),
        ]);
    }
}
