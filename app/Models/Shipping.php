<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int                             $id
 * @property int                             $is_pickup
 * @property string                          $title
 * @property string|null                     $subtitle
 * @property float                           $cost
 * @property int                             $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sale> $sales
 * @property-read int|null $sales_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereIsPickup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Shipping extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use SoftDeletes;

    protected const ATTRIBUTES = [
        'id',
        'is_pickup',
        'title',
        'subtitle',
        'cost',
        'status',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /** Fillable attributes for the model. */
    protected $fillable = [
        'is_pickup',
        'title',
        'subtitle',
        'cost',
    ];

    /** Attributes that should be cast to their respective types (Eloquent). */
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cost' => 'float',
        ];
    }

    /** Get the relationship with orders that use this shipping. */
    public function sales()
    {
        return $this->belongsToMany(Sale::class);
    }
}
