<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Supplier;

class SuppliersController extends Controller
{
    public function index()
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
