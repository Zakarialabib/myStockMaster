<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Exports\ProductExport;
use App\Livewire\Utils\Datatable;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Notifications\ProductTelegram;
use App\Traits\WithAlert;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Products')]
class Index extends Component
{
    use Datatable;
    use WithAlert;
    use WithFileUploads;

    public bool $hasMorePages = true;

    public function loadMore(): void
    {
        $this->perPage += 25;
    }

    public mixed $productWarehouse = null;

    public mixed $sendTelegram = null;

    public mixed $promoAllProducts = null;

    public mixed $copyPriceToOldPrice = null;

    public mixed $copyOldPriceToPrice = null;

    public mixed $percentageMethod = null;

    public mixed $percentage = null;

    public mixed $product = null;

    public mixed $category_id = null;

    public string $model = Product::class;

    #[Url(history: true)]
    public string $filterAvailability = '';

    #[Url(history: true)]
    public string $filterSeasonality = '';

    public bool $previewBulkAction = false;

    public string $bulkActionType = '';

    #[Computed]
    public function categories()
    {
        return Category::all()->pluck('name', 'id')->toArray();
    }

    public function deleteModal(int|string $product): void
    {
        $confirmationMessage = __('Are you sure you want to delete this product? if something happens you can be recover it.');

        $this->confirm($confirmationMessage, [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'delete',
        ]);

        $this->product = $product;
    }

    public function deleteSelectedModal(): void
    {
        $this->bulkActionType = 'delete';
        $this->previewBulkAction = true;
    }

    public function printSelectedModal(): void
    {
        $this->bulkActionType = 'print';
        $this->previewBulkAction = true;
    }

    public function confirmBulkAction()
    {
        $this->previewBulkAction = false;

        if ($this->bulkActionType === 'delete') {
            $this->deleteSelected();
        } elseif ($this->bulkActionType === 'print') {
            return $this->printSelected();
        }
    }

    public function printSelected()
    {
        abort_if(Gate::denies('product_export'), 403);

        return $this->callExport()->forModels($this->selected)->download('products.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    #[On('deleteSelected')]
    public function deleteSelected(): void
    {
        abort_if(Gate::denies('product_delete'), 403);

        Product::query()->whereIn('id', $this->selected)->delete();
        ProductWarehouse::query()->whereIn('product_id', $this->selected)->delete();

        $deletedCount = count($this->selected);

        if ($deletedCount > 0) {
            $this->alert(
                'success',
                __(':count selected products and related warehouses deleted successfully! These items can be recovered.', ['count' => $deletedCount])
            );
        }

        $this->resetSelected();
    }

    #[On('delete')]
    public function delete(): void
    {
        abort_if(Gate::denies('product_delete'), 403);

        $product = Product::query()->findOrFail($this->product);
        $productWarehouse = ProductWarehouse::query()->where('product_id', $product->id)->first();

        if ($productWarehouse) {
            $productWarehouse->delete();
        }

        $product->delete();
        $this->alert('success', __('Product and related warehouse deleted successfully!'));
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('product_access'), 403);

        $query = Product::query()
            ->with([
                'warehouses',
                'category',
                'brand',
                'movements',
            ])
            ->withSum('warehouses as total_qty', 'product_warehouse.qty')
            ->withAvg('warehouses as avg_price', 'product_warehouse.price')
            ->withAvg('warehouses as avg_cost', 'product_warehouse.cost')
            ->select('products.*')
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ])
            ->when($this->filterAvailability !== '', function ($query): void {
                $query->where('availability', $this->filterAvailability);
            })
            ->when($this->filterSeasonality, function ($query): void {
                $query->where('seasonality', 'like', '%' . $this->filterSeasonality . '%');
            });

        $products = $query->paginate($this->perPage);

        $this->hasMorePages = $products->hasMorePages();

        return view('livewire.products.index', ['products' => $products]);
    }

    #[On('sendTelegram')]
    public function sendToTelegram(mixed $product): void
    {
        $this->productWarehouse = ProductWarehouse::query()->find($product->id);

        // Specify Telegram channel
        $telegramChannel = settings()->telegram_channel;

        // Pass in product details
        $productName = $this->productWarehouse->product->name;
        $productPrice = $this->productWarehouse->price;

        $this->product->notify(new ProductTelegram($telegramChannel, $productName, $productPrice));
    }

    #[On('downloadAll')]
    public function ExcelDownloadAll(): StreamedResponse|Response
    {
        abort_if(Gate::denies('product_export'), 403);

        return $this->callExport()->download('products.xlsx');
    }

    public function exportSelected(): StreamedResponse|Response
    {
        abort_if(Gate::denies('product_export'), 403);

        // $customers = Product::whereIn('id', $this->selected)->get();

        return $this->callExport()->forModels($this->selected)->download('products.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    #[On('exportAll')]
    public function ExcelExportAll(): StreamedResponse|Response
    {
        abort_if(Gate::denies('product_export'), 403);

        return $this->callExport()->download('products.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    private function callExport(): ProductExport
    {
        return new ProductExport;
    }

    public function downloadSelected()
    {
        abort_if(Gate::denies('product_export'), 403);

        return $this->callExport()->forModels($this->selected)->download('products.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function promoAllProducts(): void
    {
        $this->promoAllProducts = true;
    }

    public function discountSelected(): void
    {
        $warehouseProducts = ProductWarehouse::query()->whereIn('product_id', $this->selected)->get();

        foreach ($warehouseProducts as $warehouseProduct) {
            if ($this->copyPriceToOldPrice) {
                $warehouseProduct->old_price = $warehouseProduct->price;
            } elseif ($this->copyOldPriceToPrice) {
                $warehouseProduct->price = $warehouseProduct->old_price;
                $warehouseProduct->old_price = null;
            } elseif ($this->percentageMethod === '+') {
                $warehouseProduct->price = round((float) $warehouseProduct->price * (1 + $this->percentage / 100));
            } else {
                $warehouseProduct->price = round((float) $warehouseProduct->price * (1 - $this->percentage / 100));
            }

            $warehouseProduct->save();
        }

        $this->alert('success', __('Product Prices changed successfully!'));

        $this->resetSelected();

        $this->promoAllProducts = false;

        $this->copyPriceToOldPrice = '';
        $this->copyOldPriceToPrice = '';
        $this->percentage = '';
    }
}
