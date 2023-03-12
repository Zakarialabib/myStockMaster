<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SupplierExport implements FromView
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    public function query()
    {
        if ($this->models) {
            return Supplier::query()->whereIn('id', $this->models);
        }

        return Supplier::query();
    }

    public function view(): View
    {
        return view('pdf.suppliers', [
            'data' => $this->query()->get(),
        ]);
    }
}
