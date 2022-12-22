<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\Adjustment
 *
 * @property int $id
 * @property string $date
 * @property string $reference
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AdjustedProduct[] $adjustedProducts
 * @property-read int|null $adjusted_products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Adjustment extends Model
{
    use HasAdvancedFilter;

    /** @var string[] */
    public $orderable = [
        'id',
        'reference_no',
        'warehouse_id',
        'date',
        'created_at',
        'updated_at',
    ];

    /** @var string[] */
    public $filterable = [
        'id',
        'reference_no',
        'warehouse_id',
        'date',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    /**
     * Get ajustement date attribute
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function date(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d M, Y'),
        );
    }

    /** @return HasMany<AdjustedProduct> */
    public function adjustedProducts(): HasMany
    {
        return $this->hasMany(AdjustedProduct::class, 'adjustment_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $number = Adjustment::max('id') + 1;
            $model->reference = make_reference_id('ADJ', $number);
        });
    }
}
