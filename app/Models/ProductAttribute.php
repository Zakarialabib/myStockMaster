<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $fillable = ['name', 'type', 'is_required'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('value');
    }
}
