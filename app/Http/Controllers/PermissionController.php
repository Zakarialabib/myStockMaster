<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('permission_access'), 403);

        return view('admin.permission.index');
    }
}
