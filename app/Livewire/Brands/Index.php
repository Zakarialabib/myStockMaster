<?php

declare(strict_types=1);

namespace App\Livewire\Brands;

use App\Imports\BrandsImport;
use App\Livewire\Utils\Datatable;
use App\Livewire\Utils\HasDelete;
use App\Models\Brand;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')]
#[Title('Brands')]
class Index extends Component
{
    use Datatable;
    use HasDelete;
    use WithAlert;
    use WithFileUploads;

    public mixed $brand;

    public mixed $file = null;

    public bool $importModal = false;

    public string $model = Brand::class;
    public string $deleteAbility = 'brand_delete';

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('brand_access'), 403);

        $query = Brand::query()->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $brands = $query->paginate($this->perPage);

        return view('livewire.brands.index', compact('brands'));
    }

    #[On('importModal')]
    public function openImportModal(): void
    {
        abort_if(Gate::denies('brand_import'), 403);

        $this->importModal = true;
    }

    public function downloadSample()
    {
        return Storage::disk('exports')->download('brands_import_sample.xls');
    }

    public function import(): void
    {
        abort_if(Gate::denies('brand_import'), 403);

        $this->validate([
            'import' => 'required|mimes:xlsx',
        ]);

        Excel::import(new BrandsImport, $this->file);

        $this->alert('success', __('Brand imported successfully.'));
    }
}
