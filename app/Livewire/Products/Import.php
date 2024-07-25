<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Jobs\ImportJob;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
use Throwable;

class Import extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $listeners = [
        'import',
        'importUpdates',
    ];

    public $file;

    public $sheetLink;

    public $importModal = false;

    public function render(): View|Factory
    {
        return view('livewire.products.import');
    }

    public function downloadSample()
    {
        return Storage::disk('exports')->download('products_import_sample.xls');
    }

    #[On('importModal')]
    public function importModal(): void
    {
        abort_if(Gate::denies('product_access'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->importModal = true;
    }

    public function importUpdates(): void
    {
        abort_if(Gate::denies('product_access'), 403);

        if ($this->file->extension() === 'xlsx' || $this->file->extension() === 'xls') {
            $filename = time().'-product.'.$this->file->getClientOriginalExtension();
            $this->file->storeAs('products', $filename);

            ImportJob::dispatch($filename);

            $this->alert('success', __('Product imported successfully!'));
        } else {
            $this->alert('error', __('File is a '.$this->file->extension().' file.!! Please upload a valid xls/csv file..!!'));
        }

        $this->importModal = false;
    }

    public function import(): void
    {
        abort_if(Gate::denies('product_access'), 403);

        try {
            $this->validate([
                'file' => 'required|mimes:xls,xlsx',
            ]);

            Excel::import(new ProductImport(), $this->file);

            $this->alert('success', __('Product imported successfully!'));

            $this->importModal = false;
        } catch (Throwable $th) {
            throw $th;
            // $this->alert('error', __('File is a '.$this->file->extension().' file.!! Please upload a valid xls/csv file..!!'));
        }
    }

    public function googleSheetImport()
    {
        $response = Http::get($this->sheetLink);

        $data = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        foreach ($data as $row) {
            $product = Product::where('name', $row[0])->first();

            $warehouseId = $row['warehouse'];

            if ($product === null) {
                $product = Product::create([
                    'name'        => $row['name'],
                    'description' => $row['description'],
                    'slug'        => Str::slug($row['name'], '-').'-'.Str::random(5),
                    'code'        => $row['code'] ?? Str::random(10),
                    'category_id' => Category::where('name', $row['category'])->first()->id ?? null,
                    'brand_id'    => Brand::where('name', $row['brand'])->first()->id ?? null,
                    'image'       => '',
                    // 'gallery' => getGalleryFromUrl($row[7]) ?? null,
                    'status' => 0,
                ]);
                $product->warehouses()->attach($row['warehouse'], [
                    'qty'         => $row['quantity'],
                    'price'       => $row['price'],
                    'cost'        => $row['cost'],
                    'old_price'   => $row['old_price'] ?? null,
                    'stock_alert' => $row['stock_alert'] ?? null,
                    // ... other product warehouse attributes ...
                ]);
            }

            $warehouseData = [
                'qty'         => $row['quantity'],
                'price'       => $row['price'],
                'cost'        => $row['cost'],
                'old_price'   => $row['old_price'] ?? null,
                'stock_alert' => $row['stock_alert'] ?? null,
                // ... other product warehouse attributes ...
            ];
            $product->warehouses()->updateExistingPivot($warehouseId, $warehouseData);

            return null;
        }
    }
}
