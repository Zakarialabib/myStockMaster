<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Supplier extends Model
{
    use HasAdvancedFilter;
    use GetModelByUuid;
    use UuidGenerator;
    use HasFactory;

    public const ATTRIBUTES = [

        'id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'address',
        'created_at',
        'tax_number',
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
        'id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'address',
    ];

    /** @return HasOne<Wallet> */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /** @return HasOne<Purchase> */
    public function purchases(): HasOne
    {
        return $this->HasOne(Purchase::class);
    }

    public function getTotalPurchasesAttribute()
    {
        return Purchase::where('supplier_id', $this->id)->sum('total_amount');
    }

    public function getTotalPurchaseReturnsAttribute()
    {
        return PurchaseReturn::where('supplier_id', $this->id)->sum('total_amount');
    }

    public function getTotalDueAttribute()
    {
        return Purchase::where('supplier_id', $this->id)->sum('due_amount') / 100;
    }

    public function getTotalPaymentsAttribute()
    {
        return Purchase::where('supplier_id', $this->id)->sum('paid_amount');
    }

    public function getDebitAttribute()
    {
        $purchases = Purchase::where('supplier_id', $this->id)
            ->completed()->sum('total_amount');
        $purchase_returns = PurchaseReturn::where('supplier_id', $this->id)
            ->completed()->sum('total_amount');

        $product_costs = 0;

        foreach (Purchase::completed()->purchaseDetails()->get() as $purchase) {
            foreach ($purchase->purchaseDetails as $purchaseDetail) {
                $product_costs += $purchaseDetail->product->cost;
            }
        }

        $debt = ($purchases - $purchase_returns) / 100;

        return $debt - $product_costs;
    }

    private function supplierSum($column, $model)
    {
        return $model::where('supplier_id', $this->id)->sum($column);
    }
}
