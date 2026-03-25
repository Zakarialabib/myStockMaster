<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferDetails extends Model
{
    use HasAdvancedFilter;
    use HasFactory;

    protected const ATTRIBUTES = [
        'id',
        'product_id',
        'warehouse_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
