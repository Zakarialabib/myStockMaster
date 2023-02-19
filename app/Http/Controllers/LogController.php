<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function __invoke()
    {
        $logs = File::files(storage_path('logs'));

        return view('admin.log.index', compact('logs'));
    }
}
