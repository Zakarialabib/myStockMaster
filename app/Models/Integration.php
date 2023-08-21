<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\IntegrationType;
use App\Enums\Status;
use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasAdvancedFilter;
    use GetModelByUuid;
    use UuidGenerator;
    use HasFactory;

    public const ATTRIBUTES = [
        'id',
        'type',
        'store_url',
        'last_sync',
        'products',
        'orders',
        'status',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'type',
        'store_url',
        'api_key',
        'sandbox',
        'api_secret',
        'last_sync',
        'products',
        'orders',
        'status',
    ];

    protected $casts = [
        'status' => Status::class,
        'type'   => IntegrationType::class,
    ];

    public function getTypeName(): string
    {
        return match ($this->type) {
            IntegrationType::CUSTOM      => 'Custom',
            IntegrationType::YOUCAN      => 'Youcan',
            IntegrationType::WOOCOMMERCE => 'WooCommerce',
            IntegrationType::SHOPIFY     => 'Shopify',
            default                      => 'Unknown'
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
