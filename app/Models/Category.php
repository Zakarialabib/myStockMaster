<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasAdvancedFilter;
    use HasFactory;

    public $orderable = [
        'id', 'code', 'name',
    ];

    public $filterable = [
        'id', 'code', 'name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
