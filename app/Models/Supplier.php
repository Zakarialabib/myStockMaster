<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class Supplier extends Model
{
     use HasAdvancedFilter;

    public $orderable = [
        'id',
        'supplier_name',
        'supplier_email',
        'supplier_phone',
        'city',
        'country',
        'address',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'supplier_name',
        'supplier_email',
        'supplier_phone',
        'city',
        'country',
        'address',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

   
}
