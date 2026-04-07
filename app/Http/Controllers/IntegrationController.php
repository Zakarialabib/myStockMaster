<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class IntegrationController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.integrations.index');
    }
}
