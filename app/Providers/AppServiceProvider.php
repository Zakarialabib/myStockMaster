<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;
use App\Models\Language;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        // Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        
        if (Session::has('language')) {
        $languages = cache()->rememberForever('languages', function () {
            return Language::pluck('name', 'code')->toArray();
        });
        View::share('languages', $languages);
        } else {
        $languages = cache()->rememberForever('languages', function () {
            return Language::where('is_default', 1)->first();
        });
        
        View::share('languages', $languages);
        }
        
        Model::shouldBeStrict(! $this->app->isProduction());

        //
    }
}
