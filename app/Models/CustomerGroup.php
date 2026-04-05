<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                             $id
 * @property string                          $name
 * @property string                          $percentage
 * @property int                             $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Customer> $customers
 * @property-read int|null $customers_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CustomerGroup extends Model
{
    use HasAdvancedFilter;
    use HasFactory;

    protected $table = 'customer_groups';

    protected const ATTRIBUTES = [
        'id', 'name', 'percentage', 'status',

    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'percentage', 'status',
    ];

    /** @return HasMany<Customer> */
    public function customers(): HasMany
    {
        return $this->HasMany(Customer::class);
    }
}
