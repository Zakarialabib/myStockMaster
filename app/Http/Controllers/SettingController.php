<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('setting_access'), 403);

        $settings = Setting::firstOrFail();

        return view('admin.settings.index', compact('settings'));
    }
}
