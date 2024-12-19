<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasAdvancedFilter;
    use HasUuid;
    use HasFactory;
    use SoftDeletes;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'address',
        'created_at',
        'tax_number',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'name', 'email', 'phone', 'address', 'city', 'country', 'tax_number',
    ];

    /** @return HasOne<Purchase> */
    public function purchases(): HasOne
    {
        return $this->hasOne(Purchase::class);
    }


    public function scopeSearchByName($query, $name)
    {
        return $query->when( ! empty($name), function ($query) use ($name) {
            return $query->where('name', 'like', '%'.$name.'%');
        });
    }

    private function supplierSum($column, $model)
    {
        return $model::where('supplier_id', $this->id)->sum($column);
    }
}
