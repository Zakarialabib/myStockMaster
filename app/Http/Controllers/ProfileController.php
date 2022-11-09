<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Upload;
use App\Rules\MatchCurrentPassword;

class ProfileController extends Controller
{

    public function index() {
        return view('admin.users.profile');
    }


}
