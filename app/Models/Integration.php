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

/**
 * App\Models\Integration
 *
 * @property int $id
 * @property string $uuid
 * @property IntegrationType $type
 * @property string|null $store_url
 * @property string|null $api_key
 * @property string|null $sandbox
 * @property string|null $api_secret
 * @property string|null $last_sync
 * @property string|null $products
 * @property string|null $orders
 * @property Status $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Integration advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Integration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Integration query()
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereApiSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereLastSync($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereSandbox($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereStoreUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Integration whereUuid($value)
 * @mixin \Eloquent
 */
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
}
