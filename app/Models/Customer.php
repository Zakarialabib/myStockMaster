<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class Customer extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'city',
        'country',
        'address',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'city',
        'country',
        'address',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'city',
        'country',
        'address',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

}
