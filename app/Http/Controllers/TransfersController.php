<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controller;

class TransfersController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('transfers_access'), 403);

        return view('admin.transfers.index');
    }
}
