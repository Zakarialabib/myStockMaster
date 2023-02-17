<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreSmtpSettingsRequest;
use App\Models\Setting;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Throwable;

class SettingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('setting_access'), 403);

        $settings = Setting::firstOrFail();

        return view('admin.settings.index', compact('settings'));
    }

  
}
