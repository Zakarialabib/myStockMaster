<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{

    public function index(Request $request)
    {
        abort_if(Gate::denies('access_warehouses'), 403);

        return view('admin.warehouses.index');
    }

}
