<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class BarcodeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('print_barcodes'), 403);

        return view('admin.barcode.index');
    }
}
