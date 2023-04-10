<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class SuppliersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('supplier_access'), 403);

        return view('admin.suppliers.index');
    }

    public function show($supplier)
    {
        abort_if(Gate::denies('supplier_access'), 403);

        $supplier = Supplier::whereUuid($supplier)->first();

        return view('admin.suppliers.details', compact('supplier'));
    }
}
