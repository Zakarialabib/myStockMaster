<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class LanguageController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('access_languages'), 403);

        return view('admin.language.index');
    }
}
