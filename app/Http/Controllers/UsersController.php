<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    public function index() {
        abort_if(Gate::denies('access_user_management'), 403);

        return view('admin.users.index');
    }
    
}
