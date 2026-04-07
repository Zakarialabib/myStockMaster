<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 *
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    public const SALE_TYPE = 1;

    public const POS_TYPE = 2;

    public const PURCHASE_TYPE = 3;

    public const RETURN_TYPE = 4;

    public const QUOTATION_TYPE = 5;

    public const PREVIEW_ACTION = 1;

    public const DOWNLOAD_ACTION = 2;

    public const EMAIL_ACTION = 3;
}
