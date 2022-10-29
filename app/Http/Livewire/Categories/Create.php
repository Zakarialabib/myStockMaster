<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;
    
    public $listeners = ['createCategory'];
    
    public $code , $name;

    public $createCategory;
    
    protected $rules = [
        'code' => 'nullable|string|max:255',
        'name' => 'required|string|max:255',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount(Category $category)
    {
        $this->category = $category;
    }

    public function render()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        return view('livewire.categories.create');
    }

    public function createCategory()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createCategory = true;
    }

    public function create()
    {
        $validatedData = $this->validate();

        Category::create($validatedData);

        $this->alert('success', 'Category created successfully.');
        
        $this->emit('refreshIndex');
        
        $this->createCategory = false;
    }
}
