<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ExpenseExport implements FromView
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    public function query()
    {
        if ($this->models) {
            return Expense::query()->whereIn('id', $this->models);
        }

        return Expense::query();
    }

    public function view(): View
    {
        return view('pdf.expenses', [
            'data' => $this->query()->get(),
        ]);
    }
}
