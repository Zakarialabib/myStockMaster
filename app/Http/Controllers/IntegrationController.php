<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class IntegrationController extends Controller
{
    public function __invoke()
    {
        return view('admin.integrations.index');
    }
}
