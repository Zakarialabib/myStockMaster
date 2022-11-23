<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id', 'code', 'name',
    ];

    public $filterable = [
        'id', 'code', 'name',
    ];

    protected $fillable = [
        'code', 'name',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes([
            'code' => Str::random(8),
        ], true);
        parent::__construct($attributes);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
