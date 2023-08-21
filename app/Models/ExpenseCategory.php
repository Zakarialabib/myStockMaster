<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseCategory extends Model
{
    use HasAdvancedFilter;
    use HasFactory;

    public $orderable = [
        'id',
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'description',
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
        'description',
    ];

    /** @return HasMany<Expense> */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'category_id', 'id');
    }
}
