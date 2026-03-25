<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'quantity',
        'price',
        'date',
        'movable_id',
        'movable_type',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MovementType::class,
        ];
    }

    public function movable()
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    public function productWarehouse(): BelongsTo
    {
        return $this->belongsTo(ProductWarehouse::class);
    }
}
