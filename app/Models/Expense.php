<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                             $id
 * @property int                             $category_id
 * @property string|null                     $user_id
 * @property int|null                        $warehouse_id
 * @property int|null                        $cash_register_id
 * @property \Illuminate\Support\Carbon      $date
 * @property string                          $reference
 * @property string|null                     $description
 * @property int|float                       $amount
 * @property string|null                     $document
 * @property string|null                     $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $start_date
 * @property string|null                     $end_date
 * @property string                          $frequency
 * @property-read CashRegister|null $cashRegister
 * @property-read ExpenseCategory $category
 * @property-read User|null $user
 * @property-read Warehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCashRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereWarehouseId($value)
 *
 * @mixin \Eloquent
 */
class Expense extends Model
{
    protected $casts = [
        'date' => 'date',
    ];

    use HasAdvancedFilter;

    protected const ATTRIBUTES = [
        'id',
        'category_id',
        'date',
        'reference',
        'amount',
        'created_at',
        'updated_at',
    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

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
        'description',
        'amount',
        'cash_register_id',
        'start_date',
        'end_date',
        'frequency',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(static function ($expense): void {
            $prefix = settings()->expense_prefix;
            $latestExpense = self::latest()->first();
            $number = $latestExpense ? (int) substr((string) $latestExpense->reference, -3) + 1 : 1;
            $expense->reference = $prefix . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            related: ExpenseCategory::class,
            foreignKey: 'category_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(
            related: Warehouse::class,
            foreignKey: 'warehouse_id',
        );
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id', 'id');
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: static fn ($value): int|float => $value / 100,
            set: static fn ($value): int|float => $value * 100,
        );
    }
}
