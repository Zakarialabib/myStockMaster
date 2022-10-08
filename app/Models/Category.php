<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id' , 'category_code' ,'category_name',
    ];

    public $filterable = [
        'id' , 'category_code' ,'category_name'
    ];

    protected $fillable = [
        'category_code' , 'category_name'
    ];

    public function __construct(array $attributes = array())
    {
        $this->setRawAttributes(array(
            'category_code' => Str::random(8)
        ), true);
        parent::__construct($attributes);
    }

    public function products() {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
