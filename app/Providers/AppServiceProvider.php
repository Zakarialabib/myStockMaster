<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Language;
use App\Models\Setting;
use App\Observers\SettingsObserver;
use Exception;
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
     */
    public function register(): void
    {
        $this->app->singleton('cart', function ($app) {
            return new \App\Support\Cart\CartManager($app);
        });

        $this->app->alias('cart', \App\Support\Cart\CartManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.force_https_scheme') || app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::share('languages', $this->getLanguages());

        Setting::observe(SettingsObserver::class);

        JsonResource::withoutWrapping();

        try {
            if (Schema::hasTable('settings')) {
                $settings = Setting::first();
                if ($settings && $settings->smtp_host) {
                    config([
                        'mail.mailer' => $settings->mail_mailer ?? config('mail.mailer'),
                        'mail.mailers.smtp.host' => $settings->smtp_host,
                        'mail.mailers.smtp.port' => $settings->smtp_port,
                        'mail.mailers.smtp.username' => $settings->smtp_username,
                        'mail.mailers.smtp.password' => $settings->smtp_password,
                        'mail.mailers.smtp.encryption' => $settings->smtp_encryption,
                        'mail.from.address' => $settings->mail_from_address,
                        'mail.from.name' => $settings->mail_from_name ?? config('mail.from.name'),
                    ]);
                }
            }
        } catch (Exception $e) {
            // Log or ignore
        }
    }

    private function getLanguages()
    {
        try {
            if (! Schema::hasTable('languages')) {
                return;
            }

            return cache()->rememberForever('languages', function () {
                return Session::has('language')
                    ? Language::pluck('name', 'code')->toArray()
                    : Language::where('is_default', 1)->first();
            });
        } catch (Exception $e) {
            return;
        }
    }
}
