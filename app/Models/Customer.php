<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property string|null $city
 * @property string|null $country
 * @property string|null $address
 * @property string|null $tax_number
 * @property int|null $wallet_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Sale|null $sales
 * @property-read \App\Models\Wallet|null $wallet
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Customer advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereWalletId($value)
 *
 * @property string $uuid
 * @property string|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUuid($value)
 * @method static \Database\Factories\CustomerFactory factory($count = null, $state = [])
 *
 * @property-read int|float $profit
 * @property-read int|float $total_due
 * @property-read int|float $total_payments
 * @property-read int|float $total_sale_returns
 * @property-read int|float $total_sales
 *
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HasAdvancedFilter;
    use GetModelByUuid;
    use UuidGenerator;
    use HasFactory;

    public $orderable = [
        'id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'address',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'address',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'city',
        'tax_number',
        'name',
        'email',
        'phone',
        'country',
        'address',
    ];

    /** @return HasOne<Wallet> */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /** @return HasOne<Sale> */
    public function sales(): HasOne
    {
        return $this->HasOne(Sale::class);
    }

    public function getTotalSalesAttribute(): int|float
    {
        return $this->customerSum('total_amount', Sale::class);
    }

    public function getTotalSaleReturnsAttribute(): int|float
    {
        return $this->customerSum('total_amount', SaleReturn::class);
    }

    public function getTotalPaymentsAttribute(): int|float
    {
        return $this->customerSum('paid_amount', Sale::class);
    }

    public function getTotalDueAttribute(): int|float
    {
        return $this->customerSum('due_amount', Sale::class);
    }

    public function getProfitAttribute(): int|float
    {
        $sales = Sale::where('customer_id', $this->id)
            ->completed()->sum('total_amount');

        $sale_returns = SaleReturn::where('customer_id', $this->id)
            ->completed()->sum('total_amount');

        $product_costs = 0;

        foreach (Sale::where('customer_id', $this->id)->with('saleDetails.product')->get() as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                $product_costs += $saleDetail->product->cost;
            }
        }

        $revenue = ($sales - $sale_returns) / 100;

        return $revenue - $product_costs;
    }

    private function customerSum($column, $model)
    {
        return $model::where('customer_id', $this->id)->sum($column);
    }
}
