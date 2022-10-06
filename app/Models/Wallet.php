<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class Wallet extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'name',
        'recieved_amount',
        'sent_amount',
        'balance',
        'customer_id',
        'supplier_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'recieved_amount',
        'sent_amount',
        'balance',
        'customer_id',
        'supplier_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'recieved_amount',
        'sent_amount',
        'balance',
        'customer_id',
        'supplier_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}