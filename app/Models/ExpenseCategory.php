<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseCategory extends Model
{
    use HasAdvancedFilter;

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

    protected $guarded = [];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'category_id', 'id');
    }
}
