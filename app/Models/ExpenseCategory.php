<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class ExpenseCategory extends Model
{
   use HasAdvancedFilter;

    public $orderable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function expenses() {
        return $this->hasMany(Expense::class, 'category_id', 'id');
    }
}
