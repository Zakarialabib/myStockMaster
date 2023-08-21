<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\Status;

class Language extends Model
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public const IS_DEFAULT = 1;
    public const IS_NOT_DEFAULT = 0;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'status',
        'is_default',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'language_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'language_id');
    }
}
