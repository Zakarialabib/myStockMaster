<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Language;
use App\Models\Setting;
use App\Observers\SettingsObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        if (config('app.force_https_scheme') || app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::share('languages', $this->getLanguages());

        Setting::observe(SettingsObserver::class);
        
        JsonResource::withoutWrapping();

        Model::shouldBeStrict( ! $this->app->isProduction());
    }

    private function getLanguages()
    {
        if ( ! app()->runningInConsole()) {
            if ( ! Schema::hasTable('languages')) {
                return;
            }

            return cache()->rememberForever('languages', function () {
                return Session::has('language')
                    ? Language::pluck('name', 'code')->toArray()
                    : Language::where('is_default', 1)->first();
            });
        }
    }
}
