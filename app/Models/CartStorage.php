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
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $fillable = ['session_key', 'cart_data'];

    #[\Override]
    protected function casts(): array
    {
        return [
            'cart_data' => 'array',
        ];
    }
}
