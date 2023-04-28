<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Adjustment;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class AdjustmentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('adjustment_access'), 403);

        return view('admin.adjustment.index');
    }

    public function create()
    {
        abort_if(Gate::denies('adjustment_create'), 403);

        return view('admin.adjustment.create');
    }

    public function edit(Adjustment $adjustment)
    {
        abort_if(Gate::denies('adjustment_edit'), 403);

        return view('admin.adjustment.edit', compact('adjustment'));
    }
}
