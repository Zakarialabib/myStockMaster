<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('role_access'), 403);

        return view('admin.roles.index');
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), 403);

        return view('admin.roles.create');
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_edit'), 403);

        return view('admin.roles.edit', compact('role'));
    }
}
