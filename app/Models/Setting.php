<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $guarded = [];

    protected $with = ['currency'];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'default_currency_id', 'id');
    }
}
