<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ProductExport implements FromView
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    public function query()
    {
        if ($this->models) {
            return Product::query()->whereIn('id', $this->models);
        }

        return Product::query()->with('category');
    }

    public function view(): View
    {
        return view('pdf.products', [
            'data' => $this->query()->get(),
        ]);
    }
}
