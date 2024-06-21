<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
