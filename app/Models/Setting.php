<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'analytics_control' => 'array',
    ];

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
}
