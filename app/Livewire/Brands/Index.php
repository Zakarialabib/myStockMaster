<?php

declare(strict_types=1);

namespace App\Livewire\Brands;

use App\Livewire\Utils\Datatable;
use App\Imports\BrandsImport;
use App\Livewire\Utils\HasDelete;
use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class Index extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    use Datatable;
    use HasDelete;

    /** @var mixed */
    public $brand;

    public $file;

    /** @var bool */
    public $importModal = false;

    public $model = Brand::class;

    public function render()
    {
        abort_if(Gate::denies('brand_access'), 403);

        $query = Brand::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $brands = $query->paginate($this->perPage);

        return view('livewire.brands.index', ['brands' => $brands]);
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

        Excel::import(new BrandsImport(), $this->file);

        $this->alert('success', __('Brand imported successfully.'));
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('brand_delete'), 403);

        Brand::whereIn('id', $this->selected)->delete();

        $this->resetSelected();

        $this->alert('success', __('Brand deleted successfully.'));
    }

    public function delete(Brand $brand): void
    {
        abort_if(Gate::denies('brand_delete'), 403);

        $brand->delete();

        $this->alert('success', __('Brand deleted successfully.'));
    }

    public function deleteModal($brand): void
    {
        $confirmationMessage = __('Are you sure you want to delete this brand? if something happens you can be recover it.');

        $this->confirm($confirmationMessage, [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'delete',
        ]);

        $this->brand = $brand;
    }
}
