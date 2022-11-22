<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class WarehouseController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('access_warehouses'), 403);

        return view('admin.warehouses.index');
    }
}
