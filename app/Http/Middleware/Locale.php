<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Schema::hasTable('languages')) {
            $language_default = Language::query()
                ->whereIsDefault(Language::IS_DEFAULT)
                ->first('code');
        }

        // $code = Session::get('code');

        $code = request()->cookie('lang', $language_default['code'] ?? 'en');

        App::setLocale($code);

        return $next($request);
    }
}
