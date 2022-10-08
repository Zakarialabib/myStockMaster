<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class Brand extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
       'id', 'name', 'description', 'image',
    ];

    public $filterable = [
        'id', 'name', 'description', 'image',
    ];


    protected $fillable = [
        'name', 'description', 'image',
    ];

}
