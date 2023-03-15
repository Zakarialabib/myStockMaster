<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Invoice
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    public const SALE_TYPE = 1;

    public const POS_TYPE = 2;

    public const PURCHASE_TYPE = 3;

    public const RETURN_TYPE = 4;

    public const QUOTATION_TYPE = 5;

    public const PREVIEW_ACTION = 1;

    public const DOWNLOAD_ACTION = 2;

    public const EMAIL_ACTION = 3;
}
