<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.users.profile');
    }
}
