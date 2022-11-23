<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasAdvancedFilter;

    public $table = 'currencies';

    public $orderable = [
        'id',
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'thousand_separator',
        'decimal_separator',
        'exchange_rate',
    ];
}
