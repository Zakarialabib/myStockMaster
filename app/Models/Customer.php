<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use App\Support\HasAdvancedFilter;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use HasFactory;
    use HasUuid;
    use Notifiable;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'email',
        'phone',
        'customer_group_id',
        'user_id',
        'city',
        'country',
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
        'id', 'name', 'phone', 'email', 'city', 'country',
        'address', 'tax_number', 'password', 'status',
        'customer_group_id', 'user_id',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'customer_id', 'id');
    }

    private function customerSum($column, $model)
    {
        return $model::where('customer_id', $this->id)->sum($column);
    }

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->attributes['first_name'].' '.$this->attributes['last_name'];
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeSearchByName($query, $name)
    {
        return $query->when( ! empty($name), function ($query) use ($name) {
            return $query->where('name', 'like', '%'.$name.'%');
        });
    }

    public function getTotalSalesAmount(): float
    {
        return $this->sales()->sum('total_amount');
    }
}
