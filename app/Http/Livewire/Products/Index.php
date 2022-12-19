<?php

declare(strict_types=1);

namespace App\Http\Livewire\Products;

use App\Exports\ProductExport;
use App\Http\Livewire\WithSorting;
use App\Imports\ProductImport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use App\Notifications\ProductTelegram;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Index extends Component
{
    use WithSorting;
    use LivewireAlert;
    use WithPagination;
    use WithFileUploads;

    /** @var mixed */
    public $product;

    /** @var string[] */
    public $listeners = [
        'showModal', 'editModal',
        'refreshIndex' => '$refresh',
        'importModal', 'sendTelegram',
    ];

    public int $perPage;

    public $showModal = false;

    public $importModal = false;

    public $editModal = false;

    public $refreshIndex;

    public $sendTelegram;
    /** @var array */
    public array $orderable;

    public $image;

    /** @var string */
    public string $search = '';

    /** @var array */
    public array $selected = [];

    /** @var array */
    public array $paginationOptions;

    public array $listsForFields = [];

    /** @var string[][] */
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

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetSelected(): void
    {
        $this->selected = [];
    }

    public array $rules = [
        'product.name'              => ['required', 'string', 'max:255'],
        'product.code'              => ['required', 'string', 'max:255'],
        'product.barcode_symbology' => ['required', 'string', 'max:255'],
        'product.unit'              => ['required', 'string', 'max:255'],
        'product.quantity'          => ['required', 'integer', 'min:1'],
        'product.cost'              => ['required', 'numeric', 'max:2147483647'],
        'product.price'             => ['required', 'numeric', 'max:2147483647'],
        'product.stock_alert'       => ['required', 'integer', 'min:0'],
        'product.order_tax'         => ['nullable', 'integer', 'min:0', 'max:100'],
        'product.tax_type'          => ['nullable', 'integer'],
        'product.note'              => ['nullable', 'string', 'max:1000'],
        'product.category_id'       => ['required', 'integer'],
        'product.brand_id'          => ['nullable', 'integer'],
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Product())->orderable;
        $this->initListsForFields();
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('product_delete'), 403);

        Product::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Product $product): void
    {
        abort_if(Gate::denies('product_delete'), 403);

        $product->delete();
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('product_access'), 403);

        $query = Product::query()
            ->with([
                'category' => fn ($query) => $query->select('id', 'name'),
                'brand'    => fn ($query) => $query->select('id', 'name'),
            ])
            ->select('products.*')
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $products = $query->paginate($this->perPage);

        return view('livewire.products.index', compact('products'));
    }

    public function showModal(Product $product): void
    {
        abort_if(Gate::denies('product_access'), 403);

        $this->product = Product::find($product->id);

        $this->showModal = true;
    }

    public function sendTelegram($product): void
    {
        $this->product = Product::find($product);

        // Specify Telegram channel
        $telegramChannel = '-877826769';

        // Pass in product details
        $productName = $this->product->name;
        $productPrice = $this->product->price;
        $productImage = $this->product->image;

        $this->product->notify(new ProductTelegram($telegramChannel, $productName, $productPrice, $productImage));
    }

    public function editModal(Product $product): void
    {
        abort_if(Gate::denies('product_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->product = Product::find($product);

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        if ($this->image) {
            $imageName = Str::slug($this->product->name).'-'.date('Y-m-d H:i:s').'.'.$this->image->extension();
            $this->image->storeAs('products', $imageName);
            $this->product->image = $imageName;
        }

        $this->product->save();

        $this->editModal = false;

        $this->alert('success', __('Product updated successfully.'));
    }

    public function importModal(): void
    {
        abort_if(Gate::denies('product_access'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->importModal = true;
    }

    public function import(): void
    {
        $this->validate([
            'import_file' => [
                'required',
                'file',
            ],
        ]);

        Product::import(new ProductImport(), $this->file('import_file'));

        $this->alert('success', __('Products imported successfully'));

        $this->importModal = false;
    }

    public function exportExcel(): BinaryFileResponse
    {
        abort_if(Gate::denies('product_access'), 403);

        return $this->callExport()->download('products.xlsx');
    }

    public function exportPdf()
    {
        return $this->callExport()->download('products.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    private function callExport(): ProductExport
    {
        return (new ProductExport());
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['categories'] = Category::pluck('name', 'id')->toArray();
        $this->listsForFields['brands'] = Brand::pluck('name', 'id')->toArray();
        $this->listsForFields['warehouses'] = Warehouse::pluck('name', 'id')->toArray();
    }
}
