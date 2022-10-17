<?php
namespace App\Http\Livewire\Products;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class Create extends Component
{
    use LivewireAlert, WithFileUploads;

    public $listeners = ['createProduct'];
    
    public $createProduct;

    public $image;

    public array $listsForFields = [];

    public array $rules = [
        'product.name' => ['required', 'string', 'max:255'],
        'product.code' => ['required', 'string', 'max:255', 'unique:products,code'],
        'product.barcode_symbology' => ['required', 'string', 'max:255'],
        'product.unit' => ['required', 'string', 'max:255'],
        'product.quantity' => ['required', 'integer', 'min:1'],
        'product.cost' => ['required', 'numeric', 'max:2147483647'],
        'product.price' => ['required', 'numeric', 'max:2147483647'],
        'product.stock_alert' => ['nullable', 'integer', 'min:0'],
        'product.order_tax' => ['nullable', 'integer', 'min:0', 'max:100'],
        'product.tax_type' => ['nullable', 'integer'],
        'product.note' => ['nullable', 'string', 'max:1000'],
        'product.category_id' => ['required', 'integer'],
        'product.brand_id' => ['nullable', 'integer']
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->product->stock_alert = 10;
        $this->product->order_tax = 0;
        $this->product->unit = 'pcs';
        $this->product->barcode_symbology = 'C128';
        $this->initListsForFields();
    }
    
    public function render()
    {
        abort_if(Gate::denies('create_products'), 403);

        return view('livewire.products.create');
    }

    public function createProduct()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createProduct = true;  
    }

    public function create()
    {
        $this->validate();

        if($this->image){
            $imageName = Str::slug($this->product->name).'.'.$this->image->extension();
            $this->image->storeAs('products',$imageName);
            $this->product->image = $imageName;
        }

        $this->product->save();
        
        $this->alert('success', 'Product created successfully');

        $this->emit('refreshIndex');

        $this->createProduct = false;

    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['categories'] = Category::pluck('name', 'id')->toArray();
        $this->listsForFields['brands'] = Brand::pluck('name', 'id')->toArray();
    }

}
