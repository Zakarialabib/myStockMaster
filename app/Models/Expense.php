<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * App\Models\Expense
 *
 * @property int $id
 * @property int $category_id
 * @property int|null $user_id
 * @property int|null $warehouse_id
 * @property \Illuminate\Support\Carbon $date
 * @property string $reference
 * @property string $details
 * @property float $amount
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ExpenseCategory $category
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Warehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|Expense advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereWarehouseId($value)
 * @mixin \Eloquent
 */
class Expense extends Model
{
    use HasAdvancedFilter;

    /** @var string[] */
    public $orderable = [
        'id',
        'category_id',
        'date',
        'reference',
        'details',
        'amount',
        'created_at',
        'updated_at',
    ];

    /** @var string[] */
    public $filterable = [
        'id',
        'category_id',
        'date',
        'reference',
        'details',
        'amount',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'user_id',
        'warehouse_id',
        'date',
        'reference',
        'details',
        'amount',
    ];

    /** @return BelongsTo<ExpenseCategory> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    /** @return BelongsTo<User> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** @return BelongsTo<Warehouse> */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes([
            'reference' => 'EXP-'.Carbon::now()->format('Ymd').'-'.Str::random(4),
        ], true);
        parent::__construct($attributes);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d M, Y');
    }

    /**
     * @param mixed $value
     * @return void
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = ($value * 100);
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getAmountAttribute($value)
    {
        return $value / 100;
    }
}
