<?php

declare(strict_types=1);

namespace App\Http\Livewire\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $createModal = false;

    /** @var mixed */
    public $brand;

    public $image;

    /** @var array<string> */
    public $listeners = ['createModal'];

    /** @var array */
    protected $rules = [
        'brand.name'        => 'required|min:3|max:255',
        'brand.description' => 'nullable|min:3',
    ];

    protected $messages = [
        'brand.name.required' => 'The name field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function createModal(): void
    {
        abort_if(Gate::denies('brand_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->brand = new Brand();

        $this->createModal = true;
    }

    public function create(): void
    {
        try {
            $validatedData = $this->validate();

            if ($this->image) {
                $imageName = Str::slug($this->name).'-'.Str::random(5).'.'.$this->image->extension();
                $this->image->storeAs('brands', $imageName);
                $this->image = $imageName;
            }

            $this->brand->save($validatedData);

            $this->emit('refreshIndex');

            $this->alert('success', __('Brand created successfully.'));

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('success', __('Error.').$th->getMessage());
        }
    }

    public function render()
    {
        abort_if(Gate::denies('brand_create'), 403);

        return view('livewire.brands.create');
    }
}
