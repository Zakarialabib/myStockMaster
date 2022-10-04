<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class Currency extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'name',
        'code',
        'symbol',
        'rate',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'code',
        'symbol',
        'rate',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

}
