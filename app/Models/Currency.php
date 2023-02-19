<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $symbol
 * @property string $thousand_separator
 * @property string $decimal_separator
 * @property int|null $exchange_rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Currency advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereDecimalSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereExchangeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereThousandSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 *
 * @property string|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereDeletedAt($value)
 *
 * @mixin \Eloquent
 */
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
