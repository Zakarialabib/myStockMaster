<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Support\HasAdvancedFilter;

class Expense extends Model
{
    use HasAdvancedFilter;

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
    
    public $fillable = [
        'category_id',
        'user_id',
        'warehouse_id',
        'date',
        'reference',
        'details',
        'amount',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Expense::max('id') + 1;
            $model->reference = make_reference_id('EXP', $number);
        });
    }

    public function getDateAttribute($value) {
        return Carbon::parse($value)->format('d M, Y');
    }

    public function setAmountAttribute($value) {
        $this->attributes['amount'] = ($value * 100);
    }

    public function getAmountAttribute($value) {
        return ($value / 100);
    }
}
