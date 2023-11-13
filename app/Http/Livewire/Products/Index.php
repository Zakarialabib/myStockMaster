<?php

declare(strict_types=1);

namespace App\Http\Livewire\Products;

use App\Exports\ProductExport;
use App\Http\Livewire\WithSorting;
use App\Imports\ProductImport;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductWarehouse;
use App\Notifications\ProductTelegram;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Index extends Component
{
    use WithSorting;
    use LivewireAlert;
    use WithPagination;
    use WithFileUploads;
    use Datatable;

    /** @var mixed */
    public $product;
    public $import_file;
    public $productIds;

    /** @var array<string> */
    public $listeners = [
        'refreshIndex' => '$refresh',
        'importModal', 'sendTelegram',
        'downloadAll', 'exportAll',
        'delete', 'deleteSelected',
    ];

    public $importModal = false;

    public $deleteModal = false;

    public $sendTelegram;

    public $selectAll;

    public $category_id;

    public function updatedCategoryId($value)
    {
        if ($value == 'all') {
            $this->category_id = null;
        } else {
            $this->category_id = $value;
        }
    }

    public function getCategoriesProperty()
    {
        return Category::pluck('name', 'id')->toArray();
    }

    /** @var array<array<string>> */
    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Product())->orderable;
    }

    public function deleteModal($product)
    {
        $confirmationMessage = __('Are you sure you want to delete this product? if something happens you can be recover it.');

        $this->confirm($confirmationMessage, [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'delete',
        ]);

        $this->product = $product;
    }

    public function deleteSelectedModal(): void
    {
        abort_if(Gate::denies('product_delete'), 403);

        $confirmationMessage = __('Are you sure you want to delete the selected products? items can be recovered.');

        $this->confirm($confirmationMessage, [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'deleteSelected',
        ]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('product_delete'), 403);

        Product::whereIn('id', $this->selected)->delete();
        ProductWarehouse::whereIn('product_id', $this->selected)->delete();

        $deletedCount = count($this->selected);

        if ($deletedCount > 0) {
            $this->alert(
                'success',
                __(':count selected products and related warehouses deleted successfully! These items can be recovered.', ['count' => $deletedCount])
            );
        }

        $this->resetSelected();
    }

    public function delete(): void
    {
        abort_if(Gate::denies('product_delete'), 403);

        $product = Product::findOrFail($this->product);
        
        $productWarehouse = ProductWarehouse::where('product_id', $product->id)->first();

        if ($productWarehouse) {
            $productWarehouse->delete();
        }
        $product->delete();
        $this->alert('success', __('Product and related warehouse deleted successfully!'));
    }

    public function render()
    {
        abort_if(Gate::denies('product_access'), 403);

        $query = Product::query()
            ->with([
                'category',
                'brand',
                'movements',
                'warehouses',
            ])
            ->when($this->category_id, function ($query) {
                return $query->where('category_id', $this->category_id);
            })
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $products = $query->paginate($this->perPage);

        return view('livewire.products.index', compact('products'));
    }

    public function selectPage()
    {

    }

    public function sendTelegram($product): void
    {
        $this->product = Product::find($product);

        // Specify Telegram channel
        $telegramChannel = settings()->telegram_channel;

        // Pass in product details
        $productName = $this->product->name;
        $productPrice = $this->product->price;

        $this->product->notify(new ProductTelegram($telegramChannel, $productName, $productPrice));
    }

    public function importModal(): void
    {
        abort_if(Gate::denies('product_access'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->importModal = true;
    }

    public function downloadSample()
    {
        return Storage::disk('exports')->download('products_import_sample.xls');
    }

    public function import(): void
    {
        $this->validate([
            'import_file' => [
                'required',
                'file',
            ],
        ]);

        Product::import(new ProductImport(), $this->import_file);

        $this->alert('success', __('Products imported successfully'));

        $this->importModal = false;
    }

    public function downloadAll(): BinaryFileResponse
    {
        abort_if(Gate::denies('product_access'), 403);

        return $this->callExport()->download('products.xlsx');
    }

    public function exportSelected(): BinaryFileResponse
    {
        abort_if(Gate::denies('product_access'), 403);

        // $customers = Product::whereIn('id', $this->selected)->get();

        return $this->callExport()->forModels($this->selected)->download('products.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function exportAll(): BinaryFileResponse
    {
        abort_if(Gate::denies('product_access'), 403);

        return $this->callExport()->download('products.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    private function callExport(): ProductExport
    {
        return new ProductExport();
    }
}
