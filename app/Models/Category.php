<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class Category extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id' , 'category_code' ,'category_name',
    ];

    public $filterable = [
        'id' , 'category_code' ,'category_name'
    ];

    protected $guarded = [];

    public function products() {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
