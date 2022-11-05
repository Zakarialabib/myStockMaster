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
use App\Models\Warehouse;

class Create extends Component
{
    use LivewireAlert, WithFileUploads;

    public $listeners = ['createProduct'];
    
    public $createProduct;

    public $image;

    public array $listsForFields = [];

    public $name;
    public $code;
    public $barcode_symbology;
    public $unit;
    public $quantity;
    public $cost;
    public $price;
    public $stock_alert;
    public $order_tax;
    public $tax_type;
    public $note;
    public $category_id;
    public $brand_id;
    public $warehouse_id;

    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'code' => ['required', 'string', 'max:255', 'unique:products,code'],
        'barcode_symbology' => ['required', 'string', 'max:255'],
        'unit' => ['required', 'string', 'max:255'],
        'quantity' => ['required', 'integer', 'min:1'],
        'cost' => ['required', 'numeric', 'max:2147483647'],
        'price' => ['required', 'numeric', 'max:2147483647'],
        'stock_alert' => ['nullable', 'integer', 'min:0'],
        'order_tax' => ['nullable', 'integer', 'min:0', 'max:100'],
        'tax_type' => ['nullable', 'integer'],
        'note' => ['nullable', 'string', 'max:1000'],
        'category_id' => ['required', 'integer'],
        'brand_id' => ['nullable', 'integer'],
        'warehouse_id' => ['nullable', 'integer']
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

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
        $validatedData = $this->validate();


        if($this->image){
            $imageName = Str::slug($this->product->name).'.'.$this->image->extension();
            $this->image->storeAs('products',$imageName);
            $this->product->image = $imageName;
        }

        Product::create($validatedData);
        
        $this->alert('success', 'Product created successfully');

        $this->emit('refreshIndex');

        $this->createProduct = false;

    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['categories'] = Category::pluck('name', 'id')->toArray();
        $this->listsForFields['brands'] = Brand::pluck('name', 'id')->toArray();
        $this->listsForFields['warehouses'] = Warehouse::pluck('name', 'id')->toArray();
    }

}
