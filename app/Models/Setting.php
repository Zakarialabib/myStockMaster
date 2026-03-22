<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $guarded = [];

        /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'analytics_control'      => 'array',
            'installation_completed' => 'boolean'
        ];
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'default_currency_id', 'id');
    }

    public function getAnalyticsControlAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setAnalyticsControlAttribute($value)
    {
        $this->attributes['analytics_control'] = json_encode($value);
    }

    public function getInvoiceControlAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setInvoiceControlAttribute($value)
    {
        $this->attributes['invoice_control'] = json_encode($value);
    }

    /** Set a specific setting value */
    public static function set(string $key, $value): void
    {
        $setting = static::first();

        if ($setting) {
            $setting->update([$key => $value]);
            cache()->forget('settings');
        }
    }

    /** Get a specific setting value with default fallback */
    public static function get(string $key, $default = null)
    {
        $settings = settings();

        return $settings ? ($settings->{$key} ?? $default) : $default;
    }
}
