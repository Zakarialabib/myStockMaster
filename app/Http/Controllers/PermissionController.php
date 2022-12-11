<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('permission_access'), 403);

        return view('admin.permission.index');
    }

    public function create()
    {
        abort_if(Gate::denies('permission_create'), 403);

        return view('admin.permission.create');
    }

    public function edit(Permission $permission)
    {
        abort_if(Gate::denies('permission_edit'), 403);

        return view('admin.permission.edit', compact('permission'));
    }

    public function show(Permission $permission)
    {
        abort_if(Gate::denies('permission_show'), 403);

        return view('admin.permission.show', compact('permission'));
    }
}
