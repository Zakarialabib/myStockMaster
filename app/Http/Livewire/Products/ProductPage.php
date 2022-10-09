<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Exports\ProductExport;
use App\Imports\ProductImport;

class ProductPage extends Component
{
    use WithPagination, WithSorting, WithFileUploads, LivewireAlert;

    public $product;

    public $listeners = ['confirmDelete', 'delete', 'showModal', 'editModal', 'refreshIndex'];

    public int $perPage;
    
    public $showModal;
    
    public $editModal;
    
    public $refreshIndex;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public array $listsForFields = [];
    
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

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function refreshIndex()
    {
        $this->resetPage();
    }

    public array $rules = [
        'product.name' => ['required', 'string', 'max:255'],
        'product.code' => ['required', 'string', 'max:255', 'unique:products,code'],
        'product.barcode_symbology' => ['required', 'string', 'max:255'],
        'product.unit' => ['required', 'string', 'max:255'],
        'product.quantity' => ['required', 'integer', 'min:1'],
        'product.cost' => ['required', 'numeric', 'max:2147483647'],
        'product.price' => ['required', 'numeric', 'max:2147483647'],
        'product.stock_alert' => ['required', 'integer', 'min:0'],
        'product.order_tax' => ['nullable', 'integer', 'min:0', 'max:100'],
        'product.tax_type' => ['nullable', 'integer'],
        'product.note' => ['nullable', 'string', 'max:1000'],
        'product.category_id' => ['required', 'integer']
    ];

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Product())->orderable;
        $this->initListsForFields();
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('delete_products'), 403);

        Product::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Product $product)
    {
        abort_if(Gate::denies('delete_products'), 403);

        $product->delete();
    }

    public function render()
    {
        abort_if(Gate::denies('access_products'), 403);

        $query = Product::advancedFilter([
                            's'               => $this->search ?: null,
                            'order_column'    => $this->sortBy,
                            'order_direction' => $this->sortDirection,
                        ]);

        $products = $query->paginate($this->perPage);

        return view('livewire.products.product', compact('products'));
    }

    public function showModal(Product $product)
    {
        abort_if(Gate::denies('show_products'), 403);

        $this->product = $product;

        $this->showModal = true;  
    }

    public function editModal(Product $product)
    {
        abort_if(Gate::denies('edit_products'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->product = $product;

        $this->editModal = true;  
    }

    public function update()
    {
        abort_if(Gate::denies('edit_products'), 403);

        $this->validate();

        $this->product->save();

        $this->editModal = false;

        $this->alert('success', 'Product updated successfully.');
    }


    public function import()
    {
        abort_if(Gate::denies('import_products'), 403);

        $this->validate([
            'import_file' => [
                'required',
                'file',
            ],
        ]);

        Product::import(new ProductImport, request()->file('import_file'));

        $this->alert('success', 'Products imported successfully');
    }


    public function exportExcel()
    {
        abort_if(Gate::denies('export_products'), 403);

        return (new ProductExport)->download('products.xlsx');
    }

    public function exportPdf()
    {
        abort_if(Gate::denies('export_products'), 403);

        return (new ProductExport)->download('products.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['categories'] = Category::pluck('name', 'id')->toArray();
    }
}