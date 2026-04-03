<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use Illuminate\Routing\Controller;

class IntegrationController extends Controller
{
    #[Get('/admin/integrations', name: 'integrations.index')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function __invoke(): View
    {
        return view('admin.integrations.index');
    }
}
