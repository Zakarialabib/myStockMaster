<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'address',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'address',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'city',
        'tax_number',
        'name',
        'email',
        'phone',
        'country',
        'address',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function sales(): HasOne
    {
        return $this->HasOne(Sale::class);
    }
}
