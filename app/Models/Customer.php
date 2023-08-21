<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'id',
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

    public function getTotalSalesAttribute()
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

    public function getProfit(): int|float
    {
        $sales = Sale::where('customer_id', $this->id)
            ->completed()->sum('total_amount');

        $sale_returns = SaleReturn::where('customer_id', $this->id)
            ->completed()->sum('total_amount');

        $product_costs = 0;

        foreach (Sale::where('customer_id', $this->id)->with('saleDetails', 'saleDetails.product')->get() as $sale) {
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
