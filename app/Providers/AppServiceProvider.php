<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Language;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Setting;
use App\Observers\PurchaseObserver;
use App\Observers\SaleObserver;
use App\Observers\SettingsObserver;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Override;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->singleton('cart', fn ($app) => new \App\Support\Cart\CartManager($app));

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
        Sale::observe(SaleObserver::class);
        Purchase::observe(PurchaseObserver::class);

        JsonResource::withoutWrapping();

        try {
            if (Schema::hasTable('settings')) {
                $settings = Setting::query()->first();
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
        } catch (Exception) {
            // Log or ignore
        }
    }

    private function getLanguages()
    {
        try {
            if (! Schema::hasTable('languages')) {
                return;
            }

            return cache()->rememberForever('languages', fn () => Session::has('language')
                ? Language::query()->pluck('name', 'code')->toArray()
                : Language::query()->where('is_default', 1)->first());
        } catch (Exception) {
            return;
        }
    }
}
