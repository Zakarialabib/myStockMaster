<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasAdvancedFilter;
    use HasFactory;

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'thousand_separator',
        'decimal_separator',
        'exchange_rate',
    ];
}
