<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartStorage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartStorage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartStorage query()
 *
 * @mixin \Eloquent
 */
class CartStorage extends Model
{
    protected $fillable = ['session_key', 'cart_data'];

    protected $casts = [
        'cart_data' => 'array',
    ];
}
