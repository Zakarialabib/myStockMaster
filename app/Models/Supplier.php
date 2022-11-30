<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Supplier extends Model
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
        'tax_number',
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
        'tax_number',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
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

    public function purchases(): HasOne
    {
        return $this->HasOne(Purchase::class);
    }
}
