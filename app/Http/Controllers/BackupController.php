<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class BackupController extends Controller
{
    public function __invoke()
    {
        if ( ! Gate::allows('backup_access')) {
            return abort(401);
        }

        return view('admin.backup.index');
    }
}
