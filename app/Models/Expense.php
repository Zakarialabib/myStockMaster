<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Str;

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
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes([
            'reference' => 'EXP-'.Carbon::now()->format('Ymd').'-'.Str::random(4),
        ], true);
        parent::__construct($attributes);
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d M, Y');
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = ($value * 100);
    }

    public function getAmountAttribute($value)
    {
        return $value / 100;
    }
}
