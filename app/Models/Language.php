<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const IS_DEFAULT = 1;
    const IS_NOT_DEFAULT = 0;

    protected $fillable = [
        'name',
        'code',
        'status',
        'is_default',
    ];

    public $timestamps = false;


    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'language_id');
    }


    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'language_id');
    }
}
