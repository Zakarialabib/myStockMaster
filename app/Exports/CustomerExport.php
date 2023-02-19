<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    /**  */
    public function __construct()
    {
    }

    public function query()
    {
        if ($this->models) {
            return Customer::query()->whereIn('id', $this->models);
        }

        return Customer::query();
    }

    public function view(): View
    {
        return view('pdf.customers', [
            'data' => $this->query()->get(),
        ]);
    }
}
