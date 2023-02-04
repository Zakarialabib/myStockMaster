<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class CurrencyController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('currency_access'), 403);

        return view('admin.currency.index');
    }
}
