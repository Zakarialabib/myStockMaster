<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class SuppliersController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('access_suppliers'), 403);

        return view('admin.suppliers.index');
    }

    public function details(Supplier $supplier)
    {
        abort_if(Gate::denies('access_suppliers'), 403);

        return view('admin.suppliers.details', compact('supplier'));
    }
}
