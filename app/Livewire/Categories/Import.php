<?php

declare(strict_types=1);

namespace App\Livewire\Categories;

use App\Imports\CategoriesImport;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{
    use WithAlert;
    use WithFileUploads;

    public $file;

    /** @var bool */
    public $importModal = false;

    #[On('importModal')]
    public function openImportModal(): void
    {
        abort_if(Gate::denies('category_import'), 403);

        $this->importModal = true;
    }

    public function downloadSample()
    {
        return Storage::disk('exports')->download('categories_import_sample.xls');
    }

    public function import(): void
    {
        abort_if(Gate::denies('category_import'), 403);

        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
        ]);

        Excel::import(new CategoriesImport, $this->file);

        $this->alert('success', __('Categories imported successfully.'));

        $this->importModal = false;
    }

    public function render()
    {
        return view('livewire.categories.import');
    }
}
