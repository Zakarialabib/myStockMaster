<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property Carbon      $date
 * @property string      $reference
 * @property string|null $user_id
 * @property int|null    $warehouse_id
 * @property string|null $note
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, AdjustedProduct> $adjustedProducts
 * @property-read int|null $adjusted_products_count
 * @property-read User|null $user
 * @property-read Warehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment whereWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Adjustment withoutTrashed()
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Adjustment extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    protected const ATTRIBUTES = [
        'id',
        'reference_no',
        'warehouse_id',
        'date',
        'created_at',
        'updated_at',
    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    protected $guarded = [];

    /** Get ajustement date attribute */
    protected function date(): Attribute
    {
        return Attribute::make(
            get: static fn (\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $value): string => \Illuminate\Support\Facades\Date::parse($value)->format('d M, Y'),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Warehouse, $this>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(
            related: Warehouse::class,
            foreignKey: 'warehouse_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\AdjustedProduct, $this>
     */
    public function adjustedProducts(): HasMany
    {
        return $this->hasMany(AdjustedProduct::class, 'adjustment_id', 'id');
    }

    #[\Override]
    protected static function boot()
    {
        parent::boot();

        static::creating(static function ($model): void {
            $number = Adjustment::query()->max('id') + 1;
            $model->reference = make_reference_id('ADJ', $number);
        });
    }
    #[\Override]
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
