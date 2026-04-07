<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int                             $id
 * @property string                          $code
 * @property string                          $name
 * @property string|null                     $description
 * @property string|null                     $title
 * @property string|null                     $image
 * @property int                             $status
 * @property string|null                     $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category advancedFilter($data)
 * @method static \Database\Factories\CategoryFactory                    factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasAdvancedFilter;
    use HasFactory;

    public const ATTRIBUTES = [
        'id', 'code', 'name',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code', 'name', 'category_id',
        'description', 'title',  'image',
        'status',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes([
            'code' => Str::random(8),
        ], true);
        parent::__construct($attributes);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }
}
