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
 * App\Models\Supplier
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $country
 * @property string|null $tax_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Purchase|null $purchases
 * @property-read \App\Models\Wallet|null $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereUpdatedAt($value)
 * @property string $uuid
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereUuid($value)
 * @method static \Database\Factories\SupplierFactory factory($count = null, $state = [])
 * @property-read mixed $debit
 * @property-read mixed $total_due
 * @property-read mixed $total_payments
 * @property-read mixed $total_purchase_returns
 * @property-read mixed $total_purchases
 * @mixin \Eloquent
 */
class Supplier extends Model
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
        'tax_number',
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
        'tax_number',
    ];

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
}
