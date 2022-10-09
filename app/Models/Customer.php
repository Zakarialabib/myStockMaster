<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

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

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

}
