<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('user_access'), 403);

        return view('admin.users.index');
    }
}
